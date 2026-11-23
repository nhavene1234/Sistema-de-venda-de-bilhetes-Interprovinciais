<?php
// includes/notificacoes.php - notificações do sino (todos os perfis)
//
// As notificações são calculadas a partir do que já existe na base de dados (reservas, pagamentos,
// viagens, utilizadores e o log de auditoria), por isso não é preciso alterar as páginas existentes.
// "Nova" = aconteceu depois da última vez que o utilizador abriu o sino (utilizador.notificacoes_vistas_em).

const NOTIF_DIAS_JANELA = 30;   // só se consideram acontecimentos dos últimos 30 dias
const NOTIF_DIAS_PRIMEIRA = 7;  // na primeira abertura, são "novas" as dos últimos 7 dias
const NOTIF_MAX = 25;           // máximo de itens no painel
require_once __DIR__ . '/suporte.php';

/** Botão do sino (o painel e o número de novas são tratados por assets/js/notificacoes.js). */
function sino_notificacoes(string $classe, string $iconeHtml): string {
    return '<button type="button" class="' . e($classe) . '" data-notif'
         . ' data-url="' . e(url('api/notificacoes.php')) . '"'
         . ' data-base="' . e(url('')) . '"'
         . ' data-csrf="' . e(csrf_token()) . '"'
         . ' aria-label="Notificações" aria-haspopup="true" aria-expanded="false">'
         . $iconeHtml . '<b class="notif-badge" hidden></b></button>';
}

/** Cria a coluna de controlo na primeira utilização (o ficheiro sql/09_notificacoes.sql faz o mesmo). */
function notif_garantir_coluna(PDO $pdo): void {
    if (!empty($_SESSION['notif_coluna_ok'])) return;
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'pgsql') {
        $n = $pdo->query("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = current_schema()
                          AND table_name = 'utilizador' AND column_name = 'notificacoes_vistas_em'")->fetchColumn();
        if (!(int)$n) {
            $pdo->exec('ALTER TABLE utilizador ADD COLUMN notificacoes_vistas_em TIMESTAMP NULL');
        }
    } else {
        $n = $pdo->query("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE()
                          AND TABLE_NAME = 'utilizador' AND COLUMN_NAME = 'notificacoes_vistas_em'")->fetchColumn();
        if (!(int)$n) {
            $pdo->exec('ALTER TABLE utilizador ADD COLUMN notificacoes_vistas_em DATETIME NULL');
        }
    }
    $_SESSION['notif_coluna_ok'] = true;
}


/** "agora mesmo", "há 5 min", "há 3 h", "ontem às 14:30", "8 Out". */
function notif_quando(string $ts): string {
    static $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    $t = strtotime($ts);
    $d = time() - $t;
    if ($d < 60)    return 'agora mesmo';
    if ($d < 3600)  return 'há ' . intdiv($d, 60) . ' min';
    if (date('Y-m-d', $t) === date('Y-m-d')) return 'há ' . intdiv($d, 3600) . ' h';
    if (date('Y-m-d', $t) === date('Y-m-d', strtotime('-1 day'))) return 'ontem às ' . date('H:i', $t);
    return date('j', $t) . ' ' . $meses[(int)date('n', $t) - 1] . ' às ' . date('H:i', $t);
}

/** Acrescenta uma notificação à lista. tipo: ok | aviso | erro | info. */
function notif_add(array &$l, ?string $ts, string $tipo, string $icone, string $titulo, string $texto, string $link): void {
    if (!$ts) return;
    $l[] = ['ts' => $ts, 'tipo' => $tipo, 'icone' => $icone, 'titulo' => $titulo, 'texto' => $texto, 'link' => $link];
}

/** Resumo curto de uma viagem: "Maputo → Beira · 12/10 às 06:00". */
function notif_viagem(array $r): string {
    return $r['origem'] . ' → ' . $r['destino'] . ' · ' . date('d/m', strtotime($r['data'])) . ' às ' . substr($r['hora_partida'], 0, 5);
}

/** Tira o nome do início do "detalhes" do log ("Nome · 3 viagem(ns)" ou "Nome → Transportadora"). */
function notif_apos_nome(?string $d): string {
    $d = (string)$d;
    foreach ([' → ', ' · '] as $sep) {
        $p = mb_strpos($d, $sep);
        if ($p !== false) return mb_substr($d, $p + mb_strlen($sep));
    }
    return $d;
}

// ------------------------------------------------------------------ passageiro
function notif_passageiro(PDO $pdo, array $u, string $desde, array &$l): void {
    $id = (int)$u['id_utilizador'];
    $agora = time();

    // bilhetes emitidos (pagamento confirmado)
    $st = $pdo->prepare("SELECT p.data_pagamento AS ts, rs.id_reserva, rs.referencia, ro.origem, ro.destino, v.data, v.hora_partida, a.numero AS lugar
                         FROM pagamento p JOIN reserva rs ON rs.id_reserva = p.id_reserva
                         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN assento a ON a.id_assento = rs.id_assento
                         WHERE rs.id_passageiro = ? AND p.estado = 'confirmado' AND rs.estado = 'confirmada' AND p.data_pagamento >= ?
                         ORDER BY p.data_pagamento DESC LIMIT 10");
    $st->execute([$id, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'ok', 'bilhete', 'Bilhete emitido',
            notif_viagem($r) . ' · lugar ' . $r['lugar'] . '. O seu bilhete digital já está disponível.',
            'passageiro/bilhete.php?reserva=' . (int)$r['id_reserva']);
    }

    // pagamentos recusados
    $st = $pdo->prepare("SELECT l.data_hora AS ts, ro.origem, ro.destino, v.data, v.hora_partida
                         FROM log_transaccao l JOIN reserva rs ON rs.id_reserva = l.id_entidade
                         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         WHERE l.accao = 'Pagamento recusado' AND l.entidade = 'reserva' AND rs.id_passageiro = ? AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 5");
    $st->execute([$id, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'erro', 'alerta', 'Pagamento recusado',
            notif_viagem($r) . '. O pagamento não foi concluído; pode tentar novamente.', 'passageiro/historico.php');
    }

    // reservas que expiraram sem pagamento
    $st = $pdo->prepare("SELECT rs.data_expiracao AS ts, ro.origem, ro.destino, v.data, v.hora_partida, a.numero AS lugar
                         FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN assento a ON a.id_assento = rs.id_assento
                         WHERE rs.id_passageiro = ? AND rs.estado = 'expirada' AND rs.data_expiracao >= ?
                         ORDER BY rs.data_expiracao DESC LIMIT 5");
    $st->execute([$id, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'aviso', 'relogio', 'Reserva expirada',
            notif_viagem($r) . '. O lugar ' . $r['lugar'] . ' foi libertado por falta de pagamento.', 'passageiro/historico.php');
    }

    notif_suporte_passageiro($pdo, $id, $desde, $l);

    // lembretes de viagem (24 h e 3 h antes da partida)
    $st = $pdo->prepare("SELECT rs.id_reserva, rs.data_reserva, ro.origem, ro.destino, v.data, v.hora_partida, a.numero AS lugar
                         FROM reserva rs JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN assento a ON a.id_assento = rs.id_assento
                         WHERE rs.id_passageiro = ? AND rs.estado = 'confirmada' AND v.estado = 'agendada' AND v.data BETWEEN ? AND ?");
    $st->execute([$id, date('Y-m-d'), date('Y-m-d', strtotime('+1 day'))]);
    foreach ($st as $r) {
        $partida = strtotime($r['data'] . ' ' . $r['hora_partida']);
        if ($partida <= $agora) continue;
        $reservou = strtotime($r['data_reserva']);
        $link = 'passageiro/bilhete.php?reserva=' . (int)$r['id_reserva'];
        if ($partida - 86400 <= $agora && $partida - 86400 >= $reservou) {
            notif_add($l, date('Y-m-d H:i:s', $partida - 86400), 'info', 'relogio', 'A sua viagem é em breve',
                notif_viagem($r) . ' · lugar ' . $r['lugar'] . '. Tenha o bilhete à mão.', $link);
        }
        if ($partida - 10800 <= $agora && $partida - 10800 >= $reservou) {
            notif_add($l, date('Y-m-d H:i:s', $partida - 10800), 'aviso', 'relogio', 'Embarque dentro de poucas horas',
                'Parte às ' . substr($r['hora_partida'], 0, 5) . ': ' . $r['origem'] . ' → ' . $r['destino'] . '. Chegue com antecedência.', $link);
        }
    }
}

/** Respostas do suporte aos pedidos do passageiro. */
function notif_suporte_passageiro(PDO $pdo, int $id, string $desde, array &$l): void {
    suporte_garantir_tabelas($pdo);
    $st = $pdo->prepare("SELECT m.data_hora AS ts, m.texto, p.id_pedido, p.estado FROM suporte_mensagem m
                         JOIN suporte_pedido p ON p.id_pedido = m.id_pedido
                         WHERE p.id_passageiro = ? AND m.autor = 'administrador' AND m.data_hora >= ?
                         ORDER BY m.data_hora DESC LIMIT 6");
    $st->execute([$id, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'info', 'suporte', 'Resposta do suporte', mb_strimwidth((string)$r['texto'], 0, 110, '…'),
                  'passageiro/suporte_ver.php?id=' . (int)$r['id_pedido']);
    }
}

// ------------------------------------------------------------------ cobrador / motorista
function notif_cobrador(PDO $pdo, array $u, string $desde, array &$l): void {
    $id = (int)$u['id_utilizador'];

    // acções do gestor sobre esta conta
    $mapa = [
        'Cobrador atribuído a viagens'           => ['ok',    'bus',    'Novas viagens atribuídas', 'Atribuição: {det}.', 'cobrador/index.php'],
        'Cobrador removido de viagens'           => ['aviso', 'alerta', 'Removido de viagens',      'Foi retirado de: {det}.', 'cobrador/index.php'],
        'Cobrador associado à transportadora'    => ['ok',    'user',   'Associado a uma transportadora', 'Foi associado: {det}.', 'cobrador/index.php'],
        'Cobrador desassociado da transportadora'=> ['aviso', 'alerta', 'Desassociado da transportadora', 'O gestor retirou a sua associação.', 'cobrador/index.php'],
        'Palavra-passe de cobrador reposta'      => ['aviso', 'escudo', 'Palavra-passe reposta',     'O gestor repôs a sua palavra-passe. Altere-a no seu perfil.', 'cobrador/senha.php'],
        'Cobrador desbloqueado'                  => ['ok',    'escudo', 'Conta desbloqueada',        'A sua conta voltou a estar activa.', 'cobrador/index.php'],
    ];
    $in = implode(',', array_fill(0, count($mapa), '?'));
    $st = $pdo->prepare("SELECT data_hora AS ts, accao, detalhes FROM log_transaccao
                         WHERE entidade = 'utilizador' AND id_entidade = ? AND accao IN ($in)
                           AND (id_utilizador IS NULL OR id_utilizador <> ?) AND data_hora >= ?
                         ORDER BY data_hora DESC LIMIT 15");
    $st->execute(array_merge([$id], array_keys($mapa), [$id, $desde]));
    foreach ($st as $r) {
        [$tipo, $ico, $tit, $txt, $link] = $mapa[$r['accao']];
        notif_add($l, $r['ts'], $tipo, $ico, $tit, str_replace('{det}', notif_apos_nome($r['detalhes']), $txt), $link);
    }

    // viagens suas que foram canceladas
    $st = $pdo->prepare("SELECT l.data_hora AS ts, l.detalhes FROM log_transaccao l
                         JOIN viagem v ON v.id_viagem = l.id_entidade
                         WHERE l.accao = 'Viagem cancelada' AND l.entidade = 'viagem' AND v.id_cobrador = ? AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 5");
    $st->execute([$id, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'erro', 'alerta', 'Viagem cancelada', (string)$r['detalhes'], 'cobrador/index.php');
    }

    // viagens de hoje e de amanhã
    $st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, ro.origem, ro.destino, au.matricula
                         FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota JOIN autocarro au ON au.id_autocarro = v.id_autocarro
                         WHERE v.id_cobrador = ? AND v.data IN (?, ?) AND v.estado IN ('agendada','em_curso')
                         ORDER BY v.data, v.hora_partida LIMIT 6");
    $st->execute([$id, date('Y-m-d'), date('Y-m-d', strtotime('+1 day'))]);
    foreach ($st as $r) {
        $hoje = $r['data'] === date('Y-m-d');
        notif_add($l, date('Y-m-d') . ' 00:00:00', 'info', 'bus',
            ($hoje ? 'Viagem hoje às ' : 'Viagem amanhã às ') . substr($r['hora_partida'], 0, 5),
            $r['origem'] . ' → ' . $r['destino'] . ' · autocarro ' . $r['matricula'] . '.',
            'cobrador/index.php?v=' . (int)$r['id_viagem']);
    }
}

// ------------------------------------------------------------------ bilheteiro / gestor (transportadora)
function notif_transportadora_do(PDO $pdo, int $id): int {
    $st = $pdo->prepare('SELECT id_transportadora FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$id]);
    return (int)$st->fetchColumn();
}

function notif_bilheteiro(PDO $pdo, array $u, string $desde, array &$l): void {
    $id = (int)$u['id_utilizador'];
    $idT = notif_transportadora_do($pdo, $id);
    if (!$idT) return;

    // o que o gestor da transportadora fez e afecta a venda
    $mapa = [
        'Viagens criadas'     => ['ok',    'bus',    'Novas viagens para vender', 'bilheteiro/index.php'],
        'Viagens programadas' => ['ok',    'bus',    'Novas viagens para vender', 'bilheteiro/index.php'],
        'Viagem cancelada'    => ['erro',  'alerta', 'Viagem cancelada',          'bilheteiro/lugares.php'],
        'Rota criada'         => ['info',  'rota',   'Nova rota',                 'bilheteiro/index.php'],
        'Rota activada'       => ['ok',    'rota',   'Rota activada',             'bilheteiro/index.php'],
        'Rota desactivada'    => ['aviso', 'rota',   'Rota desactivada',          'bilheteiro/index.php'],
        'Estado do autocarro alterado' => ['info', 'bus', 'Autocarro actualizado', 'bilheteiro/lugares.php'],
    ];
    $in = implode(',', array_fill(0, count($mapa), '?'));
    $st = $pdo->prepare("SELECT l.data_hora AS ts, l.accao, l.detalhes FROM log_transaccao l
                         JOIN utilizador x ON x.id_utilizador = l.id_utilizador
                         WHERE x.id_transportadora = ? AND x.perfil = 'gestor' AND l.accao IN ($in) AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 12");
    $st->execute(array_merge([$idT], array_keys($mapa), [$desde]));
    foreach ($st as $r) {
        [$tipo, $ico, $tit, $link] = $mapa[$r['accao']];
        notif_add($l, $r['ts'], $tipo, $ico, $tit, (string)$r['detalhes'], $link);
    }

    // reservas feitas online (ocupam lugares que ele vê no mapa)
    $st = $pdo->prepare("SELECT p.data_pagamento AS ts, ro.origem, ro.destino, v.data, v.hora_partida, a.numero AS lugar
                         FROM pagamento p JOIN reserva rs ON rs.id_reserva = p.id_reserva
                         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         JOIN assento a ON a.id_assento = rs.id_assento
                         WHERE ro.id_transportadora = ? AND p.estado = 'confirmado' AND rs.id_bilheteiro IS NULL AND p.data_pagamento >= ?
                         ORDER BY p.data_pagamento DESC LIMIT 8");
    $st->execute([$idT, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'info', 'bilhete', 'Reserva online',
            'Lugar ' . $r['lugar'] . ' reservado em ' . notif_viagem($r) . '.', 'bilheteiro/lugares.php');
    }

    notif_do_administrador($pdo, $u, $idT, $desde, $l, 'bilheteiro/index.php');
}

function notif_gestor(PDO $pdo, array $u, string $desde, array &$l): void {
    $id = (int)$u['id_utilizador'];
    $idT = notif_transportadora_do($pdo, $id);
    if (!$idT) return;

    // reservas pagas online
    $st = $pdo->prepare("SELECT p.data_pagamento AS ts, rs.referencia, us.nome AS passageiro, ro.origem, ro.destino, v.data, v.hora_partida, p.valor
                         FROM pagamento p JOIN reserva rs ON rs.id_reserva = p.id_reserva
                         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                         WHERE ro.id_transportadora = ? AND p.estado = 'confirmado' AND rs.id_bilheteiro IS NULL AND p.data_pagamento >= ?
                         ORDER BY p.data_pagamento DESC LIMIT 10");
    $st->execute([$idT, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'ok', 'dinheiro', 'Nova reserva paga',
            ($r['passageiro'] ?: 'Passageiro') . ' · ' . notif_viagem($r) . ' · ' . number_format((float)$r['valor'], 0, ',', '.') . ' MT.',
            'gestor/reservas.php');
    }

    // vendas presenciais dos bilheteiros
    $st = $pdo->prepare("SELECT l.data_hora AS ts, x.nome AS ator, l.detalhes FROM log_transaccao l
                         JOIN utilizador x ON x.id_utilizador = l.id_utilizador
                         WHERE l.accao = 'Venda presencial registada' AND x.id_transportadora = ? AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 10");
    $st->execute([$idT, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'ok', 'dinheiro', 'Venda presencial', $r['ator'] . ' vendeu ' . $r['detalhes'] . '.', 'gestor/vendas.php');
    }

    // cancelamentos feitos pelos passageiros
    $st = $pdo->prepare("SELECT l.data_hora AS ts, l.detalhes AS referencia, us.nome AS passageiro, ro.origem, ro.destino, v.data, v.hora_partida
                         FROM log_transaccao l JOIN reserva rs ON rs.id_reserva = l.id_entidade
                         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                         LEFT JOIN utilizador us ON us.id_utilizador = rs.id_passageiro
                         WHERE l.accao = 'Reserva cancelada' AND l.entidade = 'reserva' AND ro.id_transportadora = ? AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 8");
    $st->execute([$idT, $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'aviso', 'alerta', 'Reserva cancelada',
            ($r['passageiro'] ?: 'Passageiro') . ' cancelou ' . notif_viagem($r) . ' (' . $r['referencia'] . ').', 'gestor/reservas.php');
    }

    // alerta operacional: viagens dos próximos 2 dias sem cobrador
    $st = $pdo->prepare("SELECT COUNT(*) FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                         WHERE ro.id_transportadora = ? AND v.estado = 'agendada' AND v.id_cobrador IS NULL AND v.data BETWEEN ? AND ?");
    $st->execute([$idT, date('Y-m-d'), date('Y-m-d', strtotime('+2 days'))]);
    $sem = (int)$st->fetchColumn();
    if ($sem > 0) {
        notif_add($l, date('Y-m-d') . ' 00:00:00', 'aviso', 'bus', 'Viagens sem cobrador',
            $sem . ($sem === 1 ? ' viagem dos próximos 2 dias ainda não tem' : ' viagens dos próximos 2 dias ainda não têm') . ' cobrador atribuído.',
            'gestor/viagens.php');
    }

    notif_do_administrador($pdo, $u, $idT, $desde, $l, 'gestor/index.php');
}

/** Decisões do administrador que afectam a transportadora e o perfil (activar/desactivar, permissões). */
function notif_do_administrador(PDO $pdo, array $u, int $idT, string $desde, array &$l, string $link): void {
    $st = $pdo->prepare("SELECT data_hora AS ts, accao FROM log_transaccao
                         WHERE accao IN ('Transportadora activada','Transportadora desactivada') AND entidade = 'transportadora'
                           AND id_entidade = ? AND data_hora >= ? ORDER BY data_hora DESC LIMIT 3");
    $st->execute([$idT, $desde]);
    foreach ($st as $r) {
        $on = $r['accao'] === 'Transportadora activada';
        notif_add($l, $r['ts'], $on ? 'ok' : 'erro', 'escudo', $on ? 'Transportadora activada' : 'Transportadora desactivada',
            $on ? 'A sua transportadora voltou a estar activa.' : 'O administrador desactivou a sua transportadora.', $link);
    }
    $st = $pdo->prepare("SELECT data_hora AS ts, detalhes FROM log_transaccao
                         WHERE accao = 'Permissão alterada' AND detalhes LIKE ? AND data_hora >= ? ORDER BY data_hora DESC LIMIT 4");
    $st->execute([nome_perfil($u['perfil']) . ':%', $desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'info', 'escudo', 'Permissões alteradas', 'O administrador alterou o seu perfil: ' . $r['detalhes'] . '.', $link);
    }
}

// ------------------------------------------------------------------ administrador
function notif_administrador(PDO $pdo, array $u, string $desde, array &$l): void {
    $id = (int)$u['id_utilizador'];

    // pedidos de ajuda dos passageiros e novas mensagens
    suporte_garantir_tabelas($pdo);
    $st = $pdo->prepare("SELECT data_criacao AS ts, id_pedido, nome_contacto, assunto FROM suporte_pedido WHERE data_criacao >= ? AND id_admin IS NULL ORDER BY data_criacao DESC LIMIT 8");
    $st->execute([$desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'aviso', 'suporte', 'Novo pedido de ajuda', $r['nome_contacto'] . ' · ' . $r['assunto'] . ' (#' . (int)$r['id_pedido'] . ')',
                  'administrador/suporte_ver.php?id=' . (int)$r['id_pedido']);
    }
    $st = $pdo->prepare("SELECT m.data_hora AS ts, m.texto, p.id_pedido, p.nome_contacto FROM suporte_mensagem m
                         JOIN suporte_pedido p ON p.id_pedido = m.id_pedido
                         WHERE m.autor = 'passageiro' AND m.data_hora >= ?
                           AND m.id_mensagem > (SELECT MIN(m2.id_mensagem) FROM suporte_mensagem m2 WHERE m2.id_pedido = m.id_pedido)
                         ORDER BY m.data_hora DESC LIMIT 8");
    $st->execute([$desde]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'aviso', 'suporte', 'Nova mensagem de ' . $r['nome_contacto'], mb_strimwidth((string)$r['texto'], 0, 110, '…'),
                  'administrador/suporte_ver.php?id=' . (int)$r['id_pedido']);
    }

    // novos utilizadores
    $st = $pdo->prepare('SELECT data_criacao AS ts, nome, perfil FROM utilizador WHERE data_criacao >= ? AND id_utilizador <> ? ORDER BY data_criacao DESC LIMIT 8');
    $st->execute([$desde, $id]);
    foreach ($st as $r) {
        notif_add($l, $r['ts'], 'info', 'user', 'Novo utilizador', $r['nome'] . ' · ' . nome_perfil($r['perfil']) . '.', 'administrador/utilizadores.php');
    }

    // segurança e acontecimentos de outras contas
    $mapa = [
        'Conta bloqueada por tentativas falhadas' => ['erro', 'escudo', 'Conta bloqueada', '{ator} foi bloqueado por tentativas de login falhadas.', 'administrador/utilizadores.php'],
        'Recuperação de palavra-passe solicitada' => ['aviso', 'escudo', 'Recuperação de palavra-passe', '{ator} pediu para recuperar a palavra-passe.', 'administrador/auditoria.php'],
        'Transportadora adicionada'   => ['info', 'edificio', 'Transportadora adicionada', '{ator}: {det}.', 'administrador/transportadoras.php'],
        'Transportadora activada'     => ['info', 'edificio', 'Transportadora activada',   '{ator}: {det}.', 'administrador/transportadoras.php'],
        'Transportadora desactivada'  => ['aviso', 'edificio', 'Transportadora desactivada', '{ator}: {det}.', 'administrador/transportadoras.php'],
        'Permissão alterada'          => ['info', 'escudo', 'Permissão alterada', '{ator}: {det}.', 'administrador/permissoes.php'],
        'Backup executado'            => ['ok',   'documento', 'Backup executado', '{ator} · {det}.', 'administrador/auditoria.php'],
        'Viagem cancelada'            => ['aviso', 'alerta', 'Viagem cancelada', '{ator}: {det}.', 'administrador/auditoria.php'],
    ];
    $in = implode(',', array_fill(0, count($mapa), '?'));
    $st = $pdo->prepare("SELECT l.data_hora AS ts, l.accao, l.detalhes, x.nome AS ator
                         FROM log_transaccao l JOIN utilizador x ON x.id_utilizador = l.id_utilizador
                         WHERE l.accao IN ($in) AND l.id_utilizador <> ? AND l.data_hora >= ?
                         ORDER BY l.data_hora DESC LIMIT 15");
    $st->execute(array_merge(array_keys($mapa), [$id, $desde]));
    foreach ($st as $r) {
        [$tipo, $ico, $tit, $txt, $link] = $mapa[$r['accao']];
        notif_add($l, $r['ts'], $tipo, $ico, $tit, str_replace(['{ator}', '{det}'], [$r['ator'], (string)$r['detalhes']], $txt), $link);
    }
}

// ------------------------------------------------------------------ API pública do módulo
/**
 * Lista as notificações do utilizador (as mais recentes primeiro).
 * Devolve ['itens' => [...], 'novas' => n, 'agora' => 'Y-m-d H:i:s', 'desactivadas' => bool].
 */
function notif_lista(PDO $pdo, array $u): array {
    notif_garantir_coluna($pdo);
    $agora = date('Y-m-d H:i:s');   // fixado antes das consultas (usado para marcar como vistas)
    $id = (int)$u['id_utilizador'];

    $st = $pdo->prepare('SELECT notificacoes_vistas_em, notificacoes, activo FROM utilizador WHERE id_utilizador = ?');
    $st->execute([$id]);
    $conta = $st->fetch() ?: [];
    $vistas = $conta['notificacoes_vistas_em'] ?? null;
    $limiteNovas = $vistas ?: date('Y-m-d H:i:s', strtotime('-' . NOTIF_DIAS_PRIMEIRA . ' days'));

    // o passageiro pode desligar as notificações no perfil
    if ($u['perfil'] === 'passageiro' && array_key_exists('notificacoes', $conta) && !(int)$conta['notificacoes']) {
        return ['itens' => [], 'novas' => 0, 'agora' => $agora, 'desactivadas' => true];
    }

    $desde = date('Y-m-d H:i:s', strtotime('-' . NOTIF_DIAS_JANELA . ' days'));
    $l = [];
    switch ($u['perfil']) {
        case 'passageiro':     notif_passageiro($pdo, $u, $desde, $l);     break;
        case 'cobrador':
        case 'motorista':      notif_cobrador($pdo, $u, $desde, $l);       break;
        case 'bilheteiro':     notif_bilheteiro($pdo, $u, $desde, $l);     break;
        case 'gestor':         notif_gestor($pdo, $u, $desde, $l);         break;
        case 'administrador':  notif_administrador($pdo, $u, $desde, $l);  break;
    }

    // só ficam as que chegaram depois da última abertura do sino: o que já foi visto desaparece
    $l = array_values(array_filter($l, fn($i) => $i['ts'] > $limiteNovas));
    usort($l, fn($a, $b) => strcmp($b['ts'], $a['ts']));
    $l = array_slice($l, 0, NOTIF_MAX);

    foreach ($l as &$i) {
        $i['nova'] = true;
        $i['quando'] = notif_quando($i['ts']);
        $i['link'] = url($i['link']);
        unset($i['ts']);
    }
    unset($i);
    return ['itens' => $l, 'novas' => count($l), 'agora' => $agora, 'desactivadas' => false];
}

/** Marca como vistas as notificações até ao instante $ate (só avança, nunca recua). */
function notif_marcar_vistas(PDO $pdo, int $idUtilizador, ?string $ate): void {
    notif_garantir_coluna($pdo);
    $agora = date('Y-m-d H:i:s');
    if (!$ate || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $ate) || $ate > $agora) $ate = $agora;
    $pdo->prepare('UPDATE utilizador SET notificacoes_vistas_em = ?
                   WHERE id_utilizador = ? AND (notificacoes_vistas_em IS NULL OR notificacoes_vistas_em < ?)')
        ->execute([$ate, $idUtilizador, $ate]);
}
