<?php
// includes/suporte.php - suporte ao passageiro (pedidos de ajuda, conversa e ficha do passageiro para o administrador)

const SP_CATEGORIAS = [
    'senha'          => ['Esqueci a palavra-passe / não consigo entrar', 'Recuperar o acesso à conta',
                         'Se ainda consegue entrar, altere a palavra-passe em Perfil. Se não, descreva o que acontece e o suporte repõe o seu acesso.'],
    'pagamento'      => ['Problema com o pagamento', 'Debitado sem bilhete, pagamento recusado',
                         'Indique o método (M-Pesa, e-Mola ou cartão) e a reserva afectada, para o suporte verificar.'],
    'bilhete'        => ['Bilhete perdido ou com erro', 'Não encontra o bilhete ou o QR não funciona',
                         'O bilhete fica sempre em Bilhetes. Se mudou de telemóvel ou o QR não lê, o suporte pode reemitir o código.'],
    'reserva'        => ['Cancelar ou remarcar uma reserva', 'Mudar a data ou desistir da viagem', ''],
    'transportadora' => ['Localizar uma transportadora', 'Contactos, endereço e rotas',
                         'Diga qual a transportadora ou a cidade que procura e o suporte envia o contacto e o endereço.'],
    'conta'          => ['Conta e dados pessoais', 'Telefone, nome, conta bloqueada', ''],
    'outro'          => ['Outro assunto', 'Qualquer outra dúvida', ''],
];

const SP_ESTADOS = ['aberto' => 'Aberto', 'em_curso' => 'Em curso', 'resolvido' => 'Resolvido'];

/** Respostas rápidas do administrador ({nome} = primeiro nome do passageiro). */
const SP_RESPOSTAS = [
    'senha'          => 'Olá {nome}, recebemos o seu pedido. Vamos repor o seu acesso e contactá-lo(a) com uma palavra-passe temporária. Depois de entrar, altere-a em Perfil › Alterar palavra-passe.',
    'pagamento'      => 'Olá {nome}, estamos a verificar o seu pagamento. Se o valor foi debitado e o bilhete não aparece, envie-nos o comprovativo da operação (M-Pesa/e-Mola) com a referência da transacção para confirmarmos.',
    'bilhete'        => 'Olá {nome}, reemitimos o código QR da sua reserva. Abra Bilhetes na aplicação e o bilhete mostra o novo código; o anterior deixou de ser válido.',
    'reserva'        => 'Olá {nome}, pode cancelar ou remarcar em Bilhetes › Detalhes da reserva, dentro do prazo definido (até 24 horas antes da partida). Se não conseguir, diga-nos e ajudamos.',
    'transportadora' => 'Olá {nome}, segue o contacto da transportadora que procura: ',
    'conta'          => 'Olá {nome}, actualizámos os dados da sua conta. Confirme em Perfil › Dados pessoais e diga-nos se está tudo certo.',
    'outro'          => 'Olá {nome}, obrigado pelo contacto. Estamos a tratar do seu pedido e voltamos a falar consigo em breve.',
];

function suporte_garantir_tabelas(PDO $pdo): void {
    if (!empty($_SESSION['sp_tabelas_ok'])) return;
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'pgsql') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS suporte_pedido (
          id_pedido SERIAL PRIMARY KEY,
          id_passageiro INTEGER NULL REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
          nome_contacto VARCHAR(100) NOT NULL,
          contacto VARCHAR(120) NOT NULL,
          categoria VARCHAR(20) NOT NULL CHECK (categoria IN ('senha','pagamento','bilhete','reserva','transportadora','conta','outro')),
          id_reserva INTEGER NULL REFERENCES reserva (id_reserva) ON UPDATE CASCADE ON DELETE SET NULL,
          assunto VARCHAR(150) NOT NULL,
          estado VARCHAR(20) NOT NULL DEFAULT 'aberto' CHECK (estado IN ('aberto','em_curso','resolvido')),
          id_admin INTEGER NULL REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
          ip VARCHAR(45) NULL,
          data_criacao TIMESTAMP NOT NULL,
          data_actualizacao TIMESTAMP NOT NULL
        )");
        $pdo->exec("CREATE TABLE IF NOT EXISTS suporte_mensagem (
          id_mensagem SERIAL PRIMARY KEY,
          id_pedido INTEGER NOT NULL REFERENCES suporte_pedido (id_pedido) ON UPDATE CASCADE ON DELETE CASCADE,
          autor VARCHAR(20) NOT NULL CHECK (autor IN ('passageiro','administrador')),
          id_utilizador INTEGER NULL,
          texto TEXT NOT NULL,
          data_hora TIMESTAMP NOT NULL
        )");
    } else {
        $pdo->exec("CREATE TABLE IF NOT EXISTS suporte_pedido (
          id_pedido INT UNSIGNED NOT NULL AUTO_INCREMENT, id_passageiro INT UNSIGNED NULL,
          nome_contacto VARCHAR(100) NOT NULL, contacto VARCHAR(120) NOT NULL,
          categoria ENUM('senha','pagamento','bilhete','reserva','transportadora','conta','outro') NOT NULL,
          id_reserva INT UNSIGNED NULL, assunto VARCHAR(150) NOT NULL,
          estado ENUM('aberto','em_curso','resolvido') NOT NULL DEFAULT 'aberto', id_admin INT UNSIGNED NULL,
          ip VARCHAR(45) NULL, data_criacao DATETIME NOT NULL, data_actualizacao DATETIME NOT NULL,
          PRIMARY KEY (id_pedido), KEY idx_sp_estado (estado, data_actualizacao), KEY idx_sp_passageiro (id_passageiro), KEY idx_sp_ip (ip, data_criacao),
          CONSTRAINT fk_sp_passageiro FOREIGN KEY (id_passageiro) REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
          CONSTRAINT fk_sp_admin FOREIGN KEY (id_admin) REFERENCES utilizador (id_utilizador) ON UPDATE CASCADE ON DELETE SET NULL,
          CONSTRAINT fk_sp_reserva FOREIGN KEY (id_reserva) REFERENCES reserva (id_reserva) ON UPDATE CASCADE ON DELETE SET NULL
        ) ENGINE=InnoDB");
        $pdo->exec("CREATE TABLE IF NOT EXISTS suporte_mensagem (
          id_mensagem INT UNSIGNED NOT NULL AUTO_INCREMENT, id_pedido INT UNSIGNED NOT NULL,
          autor ENUM('passageiro','administrador') NOT NULL, id_utilizador INT UNSIGNED NULL,
          texto TEXT NOT NULL, data_hora DATETIME NOT NULL,
          PRIMARY KEY (id_mensagem), KEY idx_spm_pedido (id_pedido, id_mensagem), KEY idx_spm_data (autor, data_hora),
          CONSTRAINT fk_spm_pedido FOREIGN KEY (id_pedido) REFERENCES suporte_pedido (id_pedido) ON UPDATE CASCADE ON DELETE CASCADE
        ) ENGINE=InnoDB");
    }
    $_SESSION['sp_tabelas_ok'] = true;
}

function sp_etq(string $estado): string {
    return '<span class="sp-etq ' . e($estado) . '">' . e(SP_ESTADOS[$estado] ?? $estado) . '</span>';
}

function sp_data(string $ts): string {
    return date('d/m/Y H:i', strtotime($ts));
}

function sp_texto_valido(string $t): ?string {
    $t = trim(preg_replace("/\r\n?/", "\n", $t));
    $t = mb_substr($t, 0, 2000);
    return mb_strlen($t) >= 5 ? $t : null;
}

/** Cria um pedido com a primeira mensagem. Devolve o id do pedido. */
function sp_criar_pedido(PDO $pdo, ?int $idPass, string $nome, string $contacto, string $cat, ?int $idReserva,
                         string $texto, string $autor = 'passageiro', ?int $idAutor = null): int {
    $agora = date('Y-m-d H:i:s');
    $estado = $autor === 'administrador' ? 'em_curso' : 'aberto';
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'pgsql') {
        $st = $pdo->prepare('INSERT INTO suporte_pedido (id_passageiro, nome_contacto, contacto, categoria, id_reserva, assunto, estado, id_admin, ip, data_criacao, data_actualizacao)
                             VALUES (?,?,?,?,?,?,?,?,?,?,?) RETURNING id_pedido');
        $st->execute([$idPass, $nome, $contacto, $cat, $idReserva, SP_CATEGORIAS[$cat][0], $estado,
                      $autor === 'administrador' ? $idAutor : null, ip_cliente(), $agora, $agora]);
        $id = (int)$st->fetchColumn();
    } else {
        $pdo->prepare('INSERT INTO suporte_pedido (id_passageiro, nome_contacto, contacto, categoria, id_reserva, assunto, estado, id_admin, ip, data_criacao, data_actualizacao)
                       VALUES (?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$idPass, $nome, $contacto, $cat, $idReserva, SP_CATEGORIAS[$cat][0], $estado,
                       $autor === 'administrador' ? $idAutor : null, ip_cliente(), $agora, $agora]);
        $id = (int)$pdo->lastInsertId();
    }

    sp_add_mensagem($pdo, $id, $autor, $idAutor ?? $idPass, $texto);
    return $id;
}


function sp_add_mensagem(PDO $pdo, int $idPedido, string $autor, ?int $idUtilizador, string $texto): void {
    $agora = date('Y-m-d H:i:s');
    $pdo->prepare('INSERT INTO suporte_mensagem (id_pedido, autor, id_utilizador, texto, data_hora) VALUES (?,?,?,?,?)')
        ->execute([$idPedido, $autor, $idUtilizador, $texto, $agora]);
    $pdo->prepare('UPDATE suporte_pedido SET data_actualizacao = ? WHERE id_pedido = ?')->execute([$agora, $idPedido]);
}

/** Desenha a conversa. $eu = autor que é "eu" (fica à direita, a azul). */
function sp_conversa(PDO $pdo, int $idPedido, string $eu): void {
    $st = $pdo->prepare('SELECT m.*, u.nome FROM suporte_mensagem m LEFT JOIN utilizador u ON u.id_utilizador = m.id_utilizador
                         WHERE m.id_pedido = ? ORDER BY m.id_mensagem');
    $st->execute([$idPedido]);
    echo '<div class="sp-conversa">';
    foreach ($st as $m) {
        $meu = $m['autor'] === $eu;
        $quem = $m['autor'] === 'administrador' ? 'Suporte MozTransport' : ($m['nome'] ?: 'Passageiro');
        echo '<div class="sp-msg ' . ($meu ? 'eu' : 'outro') . '">' . e($m['texto'])
           . '<small>' . e($meu ? 'Você' : $quem) . ' · ' . e(sp_data($m['data_hora'])) . '</small></div>';
    }
    echo '</div>';
}

/** Reservas do passageiro para escolher num formulário. */
function sp_reservas_do_passageiro(PDO $pdo, int $idPass, int $limite = 15): array {
    $st = $pdo->prepare("SELECT rs.id_reserva, rs.referencia, rs.estado, ro.origem, ro.destino, v.data
                         FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         WHERE rs.id_passageiro = ? ORDER BY rs.data_reserva DESC LIMIT $limite");
    $st->execute([$idPass]);
    return $st->fetchAll();
}

/**
 * Ficha do passageiro para o administrador: conta, acções de ajuda e reservas (com pagamento e bilhete).
 * Os formulários enviam para administrador/passageiro.php (que trata as acções e volta a $voltar).
 */
function sp_ficha_passageiro(PDO $pdo, array $p, string $voltar, ?int $destacar = null): void {
    $id = (int)$p['id_utilizador'];
    $bloq = !(int)$p['activo'] || (!empty($p['bloqueado_ate']) && strtotime($p['bloqueado_ate']) > time());
    $campos = function (string $acao, array $extra = []) use ($id, $voltar): string {
        $h = csrf_campo() . '<input type="hidden" name="acao" value="' . e($acao) . '"><input type="hidden" name="id" value="' . $id . '">'
           . '<input type="hidden" name="voltar" value="' . e($voltar) . '">';
        foreach ($extra as $k => $v) $h .= '<input type="hidden" name="' . e($k) . '" value="' . e($v) . '">';
        return $h;
    };
    $acao = e(url('administrador/passageiro.php'));
    ?>
<section class="cartao sp-ficha">
  <div class="sp-ficha-cab"><?= avatar_html($p) ?>
    <div><h2><?= e($p['nome']) ?></h2><small><?= e($p['email']) ?> · <?= e($p['telefone'] ?: 'sem telefone') ?></small></div>
    <span class="etq <?= $bloq ? 'etq-bloqueado' : 'etq-activo' ?>"><?= $bloq ? 'Conta bloqueada' : 'Conta activa' ?></span></div>
  <p class="sp-nota">Conta criada em <?= e(date('d/m/Y', strtotime($p['data_criacao']))) ?>
    <?= (int)$p['tentativas_falhadas'] > 0 ? ' · ' . (int)$p['tentativas_falhadas'] . ' tentativa(s) de login falhada(s)' : '' ?></p>
  <div class="sp-acoes">
    <form method="post" action="<?= $acao ?>" data-confirmar="Repor a palavra-passe de <?= e($p['nome']) ?>? Será gerada uma palavra-passe temporária.">
      <?= $campos('repor_senha') ?><button class="btn btn-sec btn-pequeno" type="submit">Repor palavra-passe</button></form>
    <form method="post" action="<?= $acao ?>" data-confirmar="<?= $bloq ? 'Desbloquear' : 'Bloquear' ?> a conta de <?= e($p['nome']) ?>?">
      <?= $campos('alternar_estado') ?><button class="btn btn-sec btn-pequeno" type="submit"><?= $bloq ? 'Desbloquear conta' : 'Bloquear conta' ?></button></form>
  </div>
  <form method="post" action="<?= $acao ?>" class="sp-linha-form">
    <?= $campos('actualizar_telefone') ?>
    <input type="text" name="telefone" value="<?= e($p['telefone']) ?>" placeholder="Telefone (ex.: 84 123 4567)" aria-label="Telefone" inputmode="tel">
    <button class="btn btn-sec btn-pequeno" type="submit">Corrigir telefone</button>
  </form>
</section>

<?php
    $st = $pdo->prepare("SELECT rs.id_reserva, rs.referencia, rs.estado, rs.data_reserva, ro.origem, ro.destino, t.nome AS transportadora,
                                v.data, v.hora_partida, a.numero AS lugar, p.estado AS pag_estado, p.metodo, p.motivo_falha, p.valor,
                                b.estado AS bil_estado, b.codigo_qr
                         FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN transportadora t ON t.id_transportadora = ro.id_transportadora JOIN assento a ON a.id_assento = rs.id_assento
                         LEFT JOIN pagamento p ON p.id_reserva = rs.id_reserva LEFT JOIN bilhete b ON b.id_reserva = rs.id_reserva
                         WHERE rs.id_passageiro = ? ORDER BY rs.data_reserva DESC LIMIT 10");
    $st->execute([$id]);
    $res = $st->fetchAll();
    $metodos = ['mpesa' => 'M-Pesa', 'emola' => 'e-Mola', 'cartao' => 'Cartão', 'numerario' => 'Numerário'];
    ?>
<section class="cartao">
  <h2>Reservas recentes</h2>
  <p class="sub">Estado da reserva, do pagamento e do bilhete — o necessário para ajudar com pagamentos e bilhetes perdidos.</p>
  <?php if (!$res): ?><p class="sp-nota">Este passageiro ainda não tem reservas.</p><?php else: ?>
  <div class="tabela-rolar"><table class="tabela">
    <thead><tr><th>Reserva</th><th>Viagem</th><th>Pagamento</th><th>Bilhete</th><th class="acoes">Acção</th></tr></thead><tbody>
    <?php foreach ($res as $r):
        $partida = strtotime($r['data'] . ' ' . $r['hora_partida']);
        $podeReemitir = $r['estado'] === 'confirmada' && $r['bil_estado'] === 'valido' && $partida > time();
    ?>
      <tr class="<?= $destacar === (int)$r['id_reserva'] ? 'sp-destaque' : '' ?>">
        <td><b class="mono"><?= e($r['referencia']) ?></b><br><small><?= e($r['estado']) ?> · lugar <?= e($r['lugar']) ?></small></td>
        <td><?= e($r['origem'] . ' → ' . $r['destino']) ?><br><small><?= e($r['transportadora']) ?> · <?= e(date('d/m/Y', $partida)) ?> <?= e(substr($r['hora_partida'], 0, 5)) ?></small></td>
        <td><?php if ($r['pag_estado']): ?><?= e(['confirmado' => 'Confirmado', 'falhado' => 'Falhado', 'pendente' => 'Pendente'][$r['pag_estado']] ?? $r['pag_estado']) ?>
              <br><small><?= e($metodos[$r['metodo']] ?? '—') ?> · <?= e(number_format((float)$r['valor'], 0, ',', '.')) ?> MT<?= $r['motivo_falha'] ? ' · ' . e($r['motivo_falha']) : '' ?></small>
            <?php else: ?><small>Sem pagamento</small><?php endif; ?></td>
        <td><?php if ($r['bil_estado']): ?><?= e(ucfirst($r['bil_estado'])) ?><br><small class="mono"><?= e(substr($r['codigo_qr'], -6)) ?></small><?php else: ?><small>Não emitido</small><?php endif; ?></td>
        <td class="acoes"><?php if ($podeReemitir): ?>
          <form method="post" action="<?= $acao ?>" data-confirmar="Reemitir o QR da reserva <?= e($r['referencia']) ?>? O código antigo deixa de funcionar.">
            <?= $campos('reemitir_qr', ['id_reserva' => $r['id_reserva']]) ?><button class="btn-tabela" type="submit">Reemitir QR</button></form>
        <?php else: ?><small class="pequeno">—</small><?php endif; ?></td>
      </tr>
    <?php endforeach; ?></tbody></table></div>
  <?php endif; ?>
</section>
<?php
}
