<?php
require_once __DIR__ . '/../includes/sessao.php';

if ($u = utilizador_logado()) {
    redirecionar(pagina_inicial_perfil($u['perfil']));
}

$erro = null;
$bloqueioSegundos = 0;
$emailPost = '';
$maxTentativas  = (int)config_valor($pdo, 'tentativas_login', 5);
$minutosBloqueio = (int)config_valor($pdo, 'bloqueio_minutos', 5);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailPost = trim($_POST['email'] ?? '');
    $senha     = $_POST['senha'] ?? '';

    if (!csrf_valido()) {
        $erro = 'Pedido inválido. Actualize a página e tente novamente.';
    } elseif ($emailPost === '' || $senha === '') {
        $erro = 'Preencha o email e a palavra-passe.';
    } else {
        $st = $pdo->prepare('SELECT * FROM utilizador WHERE email = ? LIMIT 1');
        $st->execute([$emailPost]);
        $utilizador = $st->fetch();
        $agora = time();

        if (!$utilizador) {
            $erro = 'Email ou palavra-passe incorrectos.';
        } else {
            // o bloqueio já terminou? então repõe o contador
            if ($utilizador['bloqueado_ate'] && strtotime($utilizador['bloqueado_ate']) <= $agora) {
                $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = NULL WHERE id_utilizador = ?')
                    ->execute([$utilizador['id_utilizador']]);
                $utilizador['tentativas_falhadas'] = 0;
                $utilizador['bloqueado_ate'] = null;
            }

            if ($utilizador['bloqueado_ate']) {
                $bloqueioSegundos = strtotime($utilizador['bloqueado_ate']) - $agora;
            } elseif (!(int)$utilizador['activo']) {
                $erro = 'A sua conta foi desactivada. Contacte o administrador.';
            } elseif (password_verify($senha, $utilizador['senha'])) {
                // ----- login com sucesso -----
                session_regenerate_id(true);
                $_SESSION['utilizador'] = [
                    'id_utilizador'     => (int)$utilizador['id_utilizador'],
                    'nome'              => $utilizador['nome'],
                    'email'             => $utilizador['email'],
                    'perfil'            => $utilizador['perfil'],
                    'id_transportadora' => $utilizador['id_transportadora'],
                    'foto'              => $utilizador['foto'],
                    'agencia'           => $utilizador['agencia'],
                ];
                if (!empty($_POST['lembrar'])) {
                    setcookie(session_name(), session_id(), [
                        'expires' => time() + 30 * 86400, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
                    ]);
                }
                $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = NULL, ultimo_acesso = ? WHERE id_utilizador = ?')
                    ->execute([date('Y-m-d H:i:s'), $utilizador['id_utilizador']]);
                registar_log($pdo, (int)$utilizador['id_utilizador'], 'Login realizado', 'utilizador', (int)$utilizador['id_utilizador']);
                redirecionar(pagina_inicial_perfil($utilizador['perfil']));
            } else {
                // ----- palavra-passe errada -----
                $n = (int)$utilizador['tentativas_falhadas'] + 1;
                if ($n >= $maxTentativas) {
                    $ate = date('Y-m-d H:i:s', $agora + $minutosBloqueio * 60);
                    $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = 0, bloqueado_ate = ? WHERE id_utilizador = ?')
                        ->execute([$ate, $utilizador['id_utilizador']]);
                    registar_log($pdo, (int)$utilizador['id_utilizador'], 'Conta bloqueada por tentativas falhadas', 'utilizador', (int)$utilizador['id_utilizador']);
                    $bloqueioSegundos = $minutosBloqueio * 60;
                } else {
                    $pdo->prepare('UPDATE utilizador SET tentativas_falhadas = ? WHERE id_utilizador = ?')
                        ->execute([$n, $utilizador['id_utilizador']]);
                    $erro = 'Email ou palavra-passe incorrectos.';
                }
            }
        }
    }
}

$mensagem = flash_obter();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Entrar · <?= e(APP_NOME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('assets/css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('assets/css/auth.css')) ?>">
</head>
<body class="auth">
<div class="auth-grelha">

  <!-- ===== LADO ESQUERDO: título + mapa de Moçambique ===== -->
  <section class="auth-lado">
    <div>
      <h2>Sistema de Vendas de Bilhetes interprovinciais de Mocambique</h2>
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

  <!-- ===== LADO DIREITO: cartão de login ===== -->
  <section class="auth-form">
    <div class="auth-form-interior">
      <?= logo_html() ?>

      <h1>Iniciar sessão</h1>
      <p class="auth-sub">Entre na sua conta para continuar.</p>

      <?php if ($bloqueioSegundos > 0): ?>
        <div class="alerta alerta-erro">
          <strong>Conta bloqueada temporariamente</strong>
          Excedeu as <?= (int)$maxTentativas ?> tentativas falhadas. Tente novamente em:
          <strong class="mono" id="contagem" data-segundos="<?= (int)$bloqueioSegundos ?>" style="display:inline"></strong>
        </div>
      <?php elseif ($erro): ?>
        <div class="alerta alerta-erro"><?= e($erro) ?></div>
      <?php endif; ?>
      <?php if ($mensagem): ?>
        <div class="alerta alerta-<?= e($mensagem['tipo']) ?>"><?= e($mensagem['msg']) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="on">
        <?= csrf_campo() ?>

        <div class="campo">
          <label for="email">Endereço de Email</label>
          <input type="email" id="email" name="email" value="<?= e($emailPost) ?>" placeholder="nome@empresa.co.mz" required>
        </div>
        <div class="campo">
          <label for="senha">Palavra-passe</label>
          <div class="campo-senha">
            <input type="password" id="senha" name="senha" placeholder="••••••••" required>
            <button type="button" class="alternar" data-alternar-senha="senha">Mostrar</button>
          </div>
        </div>
        <div class="auth-linha">
          <label><input type="checkbox" name="lembrar" value="1"> Lembrar-me</label>
          <a href="<?= e(url('auth/recuperar_senha.php?reiniciar=1')) ?>">Esqueceu a Palavra-passe?</a>
        </div>

        <button class="btn btn-primario btn-bloco" type="submit">Entrar</button>
      </form>

      <div class="auth-criar">
        Não tem uma conta? <a href="<?= e(url('passageiro/registar.php')) ?>">Criar conta</a>
      </div>

     
    </div>
  </section>
</div>
<script src="<?= e(url('assets/js/auth.js')) ?>"></script>
</body>
</html>