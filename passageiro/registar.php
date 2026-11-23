<?php
// passageiro/registar.php - cadastro de passageiros, feito pelo próprio passageiro (sem depender do administrador)
require_once __DIR__ . '/../includes/sessao.php';

if (utilizador_logado()) {
    redirecionar('index.php');   // quem já tem sessão vai para o seu painel
}

// Verifica se a coluna 'documento' existe (compatível com PostgreSQL e MySQL)
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
if ($driver === 'pgsql') {
    $temDocumento = (bool)$pdo->query("SELECT 1 FROM information_schema.columns WHERE table_name = 'utilizador' AND column_name = 'documento'")->fetch();
} else {
    $temDocumento = (bool)$pdo->query("SHOW COLUMNS FROM utilizador LIKE 'documento'")->fetch();
}

$erro = null;
$sucesso = null;
$v = ['nome' => '', 'telefone' => '', 'email' => '', 'documento' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v['nome'] = trim($_POST['nome'] ?? '');
    $v['telefone'] = substr(preg_replace('/\D/', '', $_POST['telefone'] ?? ''), -9);
    $v['email'] = trim($_POST['email'] ?? '');
    $v['documento'] = strtoupper(trim($_POST['documento'] ?? ''));
    $senha = $_POST['senha'] ?? '';
    $conf = $_POST['confirmar'] ?? '';

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif (mb_strlen($v['nome']) < 3) {
        $erro = 'Indique o seu nome completo.';
    } elseif (!preg_match('/^8[2-7]\d{7}$/', $v['telefone'])) {
        $erro = 'Indique um número de telemóvel moçambicano válido (9 dígitos, ex.: 84 123 4567).';
    } elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $erro = 'Indique um email válido.';
    } elseif ($temDocumento && !preg_match('/^[A-Z0-9]{5,20}$/', $v['documento'])) {
        $erro = 'O número de documento deve ter entre 5 e 20 letras ou números.';
    } elseif ($falha = validar_nova_senha($senha)) {
        $erro = $falha;
    } elseif ($senha !== $conf) {
        $erro = 'As palavras-passe não coincidem.';
    } elseif (empty($_POST['termos'])) {
        $erro = 'Tem de aceitar os termos de utilização e a política de privacidade.';
    } else {
        $sql = 'SELECT COUNT(*) FROM utilizador WHERE email = ?' . ($temDocumento ? ' OR documento = ?' : '');
        $st = $pdo->prepare($sql);
        $st->execute($temDocumento ? [$v['email'], $v['documento']] : [$v['email']]);
        if ($st->fetchColumn() > 0) {
            $erro = $temDocumento ? 'Já existe uma conta com este email ou número de documento.' : 'Já existe uma conta com este email.';
        } else {
            $telefone = '+258 ' . substr($v['telefone'], 0, 2) . ' ' . substr($v['telefone'], 2, 3) . ' ' . substr($v['telefone'], 5);
            try {
                if ($driver === 'pgsql') {
                    if ($temDocumento) {
                        $stIns = $pdo->prepare("INSERT INTO utilizador (nome, email, senha, telefone, documento, perfil) VALUES (?,?,?,?,?,'passageiro') RETURNING id_utilizador");
                        $stIns->execute([$v['nome'], $v['email'], password_hash($senha, PASSWORD_DEFAULT), $telefone, $v['documento']]);
                    } else {
                        $stIns = $pdo->prepare("INSERT INTO utilizador (nome, email, senha, telefone, perfil) VALUES (?,?,?,?,'passageiro') RETURNING id_utilizador");
                        $stIns->execute([$v['nome'], $v['email'], password_hash($senha, PASSWORD_DEFAULT), $telefone]);
                    }
                    $id = (int)$stIns->fetchColumn();
                } else {
                    if ($temDocumento) {
                        $pdo->prepare("INSERT INTO utilizador (nome, email, senha, telefone, documento, perfil) VALUES (?,?,?,?,?,'passageiro')")
                            ->execute([$v['nome'], $v['email'], password_hash($senha, PASSWORD_DEFAULT), $telefone, $v['documento']]);
                    } else {
                        $pdo->prepare("INSERT INTO utilizador (nome, email, senha, telefone, perfil) VALUES (?,?,?,?,'passageiro')")
                            ->execute([$v['nome'], $v['email'], password_hash($senha, PASSWORD_DEFAULT), $telefone]);
                    }
                    $id = (int)$pdo->lastInsertId();
                }

                registar_log($pdo, $id, 'Conta de passageiro criada', 'utilizador', $id);
                $sucesso = explode(' ', $v['nome'])[0];
            } catch (PDOException $e) {
                $erro = 'Não foi possível criar a conta. Tente novamente.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Criar conta · <?= e(APP_NOME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/auth.css')) ?>">
</head>
<body class="auth">
<div class="auth-grelha">
  <section class="auth-lado">
    <div>
      <h2>Sistema de Bilhetes e Gestão de Frotas de Moçambique</h2>
    </div>

    <svg class="auth-mapa" viewBox="385 305 850 905" preserveAspectRatio="xMidYMid meet" style="width:100%;max-width:none;height:auto;max-height:82vh;flex:1" aria-hidden="true">
      <!-- Províncias (formas simplificadas; substitua pelo SVG real se tiver) -->
      <g stroke="#1e5b86" stroke-width="2" stroke-linejoin="round">
        <!-- Cabo Delgado -->
        <polygon fill="#bde8f8" points="915,362 960,355 1010,330 1022,320 1020,370 1018,415 1025,440 1020,480 940,513 915,515 895,480 905,460 893,458 898,425 918,395"/>
        <!-- Niassa -->
        <polygon fill="#a5dcee" points="712,405 725,378 770,378 800,370 820,388 835,380 865,385 885,362 915,365 918,395 898,425 893,458 905,462 895,480 915,515 885,520 835,548 815,572 788,588 785,550 760,520 738,480 722,478 718,430"/>
        <!-- Nampula -->
        <polygon fill="#bde8f8" points="940,513 1020,480 1030,540 1025,575 990,630 948,660 945,625 928,598 890,570 860,562 830,560 835,548 885,520 915,515"/>
        <!-- Zambézia -->
        <polygon fill="#a5dcee" points="830,560 860,562 890,570 928,598 945,625 948,660 915,680 858,705 820,752 800,745 775,715 760,705 755,650 770,625 785,590 815,572"/>
        <!-- Tete -->
        <polygon fill="#bde8f8" points="503,565 655,510 678,540 718,532 722,580 705,610 718,630 745,660 760,705 742,690 720,660 690,637 665,640 640,650 615,640 580,632 560,615 512,615"/>
        <!-- Manica -->
        <polygon fill="#a5dcee" points="665,640 690,637 720,660 700,690 697,725 708,735 682,745 690,770 692,800 690,820 665,835 668,860 700,885 690,900 650,915 612,900 630,860 645,830 635,775 645,740 640,700"/>
        <!-- Sofala -->
        <polygon fill="#bde8f8" points="720,660 745,660 760,705 775,715 800,745 820,752 800,770 765,795 745,820 728,822 725,860 745,880 715,893 700,885 668,860 665,835 690,820 692,800 690,770 682,745 708,735 697,725 700,690"/>
        <!-- Inhambane -->
        <polygon fill="#a5dcee" points="700,885 715,893 745,880 755,920 770,950 772,990 760,1040 748,1070 715,1090 650,1115 655,1090 685,1050 690,1010 685,990 660,960 650,920 690,900"/>
        <!-- Gaza -->
        <polygon fill="#bde8f8" points="612,900 650,915 690,900 650,920 660,960 685,990 690,1010 685,1050 655,1090 650,1115 640,1130 605,1055 585,1055 570,1020 558,960"/>
        <!-- Maputo -->
        <polygon fill="#a5dcee" points="592,1058 625,1068 640,1090 650,1115 632,1130 640,1165 635,1195 600,1197 595,1150 590,1100"/>
      </g>

      <!-- Linhas de ligação -->
      <g stroke="#1ea1e6" stroke-width="1.5" opacity=".8">
        <line x1="623"  y1="432"  x2="717"  y2="432"/>   <!-- Niassa -->
        <line x1="1018" y1="416"  x2="1065" y2="416"/>   <!-- Cabo Delgado -->
        <line x1="475"  y1="576"  x2="510"  y2="576"/>   <!-- Tete -->
        <line x1="1028" y1="559"  x2="1065" y2="559"/>   <!-- Nampula -->
        <line x1="935"  y1="672"  x2="968"  y2="672"/>   <!-- Zambézia -->
        <line x1="510"  y1="779"  x2="635"  y2="779"/>   <!-- Manica -->
        <line x1="765"  y1="795"  x2="828"  y2="795"/>   <!-- Sofala -->
        <line x1="770"  y1="983"  x2="828"  y2="983"/>   <!-- Inhambane -->
        <line x1="484"  y1="1004" x2="570"  y2="1004"/>  <!-- Gaza -->
        <line x1="534"  y1="1113" x2="592"  y2="1113"/>  <!-- Maputo -->
      </g>

      <!-- Nomes das províncias -->
      <g fill="#ffffff" font-size="24" font-family="'Plus Jakarta Sans', sans-serif">
        <text x="512"  y="441">Niassa</text>
        <text x="1078" y="427">Cabo Delgado</text>
        <text x="400"  y="586">Tete</text>
        <text x="1078" y="570">Nampula</text>
        <text x="981"  y="682">Zambézia</text>
        <text x="400"  y="789">Manica</text>
        <text x="845"  y="805">Sofala</text>
        <text x="845"  y="993">Inhambane</text>
        <text x="403"  y="1015">Gaza</text>
        <text x="395"  y="1110">Maputo</text>
      </g>
    </svg>
  </section>

  <section class="auth-form">
    <div class="auth-form-interior">
<?php if ($sucesso): ?>
      <h1>Conta criada com sucesso!</h1>
      <p class="auth-sub">Bem-vindo à <?= e(APP_NOME) ?>, <?= e($sucesso) ?>. Já pode reservar a sua primeira viagem.</p>
      <div class="alerta alerta-ok">A sua conta está activa. Entre com o email e a palavra-passe que acabou de definir.</div>
      <a class="btn btn-primario btn-bloco" href="<?= e(url('auth/login.php')) ?>">Entrar na minha conta</a>
<?php else: ?>
      <h1>Criar conta</h1>
      <p class="auth-sub">Preencha os dados para começar a viajar.</p>

      <?php if ($erro): ?><div class="alerta alerta-erro"><?= e($erro) ?></div><?php endif; ?>

      <form method="post" autocomplete="on">
        <?= csrf_campo() ?>
        <div class="campo"><label for="nome">Nome completo</label>
          <input type="text" id="nome" name="nome" value="<?= e($v['nome']) ?>" required autofocus></div>
        <div class="linha-2">
          <div class="campo"><label for="telefone">Telemóvel (+258)</label>
            <input type="tel" id="telefone" name="telefone" value="<?= e($v['telefone']) ?>" inputmode="numeric" maxlength="12" placeholder="84 123 4567" required></div>
          <div class="campo"><label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($v['email']) ?>" placeholder="nome@email.co.mz" required></div>
        </div>
        <?php if ($temDocumento): ?>
        <div class="campo"><label for="documento">Número de documento (BI ou passaporte)</label>
          <input type="text" id="documento" name="documento" value="<?= e($v['documento']) ?>" maxlength="20" required></div>
        <?php endif; ?>
        <div class="linha-2">
          <div class="campo"><label for="senha">Palavra-passe</label>
            <div class="campo-senha"><input type="password" id="senha" name="senha" minlength="8" required>
              <button type="button" class="alternar" data-alternar-senha="senha">Mostrar</button></div></div>
          <div class="campo"><label for="confirmar">Confirmar</label>
            <div class="campo-senha"><input type="password" id="confirmar" name="confirmar" minlength="8" required>
              <button type="button" class="alternar" data-alternar-senha="confirmar">Mostrar</button></div></div>
        </div>
        <p class="auth-sub" style="margin:-4px 0 12px;font-size:12px">Mínimo de 8 caracteres, com letras e números.</p>
        <label style="display:flex;gap:10px;align-items:flex-start;font-size:13px;color:var(--cinza);margin-bottom:16px">
          <input type="checkbox" name="termos" value="1" style="margin-top:3px" <?= !empty($_POST['termos']) ? 'checked' : '' ?>>
          <span>Aceito os termos de utilização e a política de privacidade.</span></label>
        <button class="btn btn-primario btn-bloco" type="submit">Criar conta</button>
      </form>
      <p class="auth-sub" style="margin-top:18px;text-align:center">Já tem conta? <a href="<?= e(url('auth/login.php')) ?>"><b>Entrar</b></a></p>
<?php endif; ?>
    </div>
  </section>
</div>
<script src="<?= e(url('assets/js/auth.js')) ?>"></script>
</body>
</html>