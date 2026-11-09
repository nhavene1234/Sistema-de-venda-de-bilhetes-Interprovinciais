<?php
// includes/cobradores.php - associação cobrador ↔ transportadora ↔ motorista ↔ viagem.
// Só o Gestor da transportadora faz esta associação (RN13); o cobrador apenas valida bilhetes
// das viagens que lhe foram atribuídas.

/** Estados de viagem em que ainda faz sentido (re)atribuir cobrador. */
const VIAGEM_ESTADOS_ABERTOS = "('agendada','em_curso')";

/** Cobrador que pertence à transportadora; caso contrário null. */
function cobrador_da_transportadora(PDO $pdo, int $idCobrador, int $idT): ?array {
    $st = $pdo->prepare("SELECT id_utilizador, nome, email, telefone, activo FROM utilizador
                         WHERE id_utilizador = ? AND perfil = 'cobrador' AND id_transportadora = ?");
    $st->execute([$idCobrador, $idT]);
    return $st->fetch() ?: null;
}

/** [início, fim] (timestamps) de uma viagem; a duração vem da rota ("26:00:00"). */
function intervalo_viagem(string $data, string $hora, ?string $duracao): array {
    $ini = strtotime("$data $hora");
    [$h, $m] = array_map('intval', explode(':', $duracao ?: '00:00'));
    return [$ini, max($ini + 60, $ini + ($h * 60 + $m) * 60)];
}

function descricao_viagem(array $v): string {
    return date('d/m', strtotime($v['data'])) . ' ' . substr($v['hora_partida'], 0, 5) . ' · ' . $v['origem'] . ' → ' . $v['destino'];
}

/**
 * Um cobrador, motorista ou autocarro não pode estar em duas viagens ao mesmo tempo.
 * $coluna: 'id_cobrador' | 'id_motorista' | 'id_autocarro'.
 * $novas: viagens que se pretende criar/atribuir (id_viagem, data, hora_partida, duracao_estimada, origem, destino);
 *         ids negativos servem para viagens que ainda não existem.
 * Devolve a lista de mensagens de conflito (vazia = tudo bem).
 */
function conflitos_recurso(PDO $pdo, string $coluna, int $id, array $novas): array {
    if (!$novas || !in_array($coluna, ['id_cobrador', 'id_motorista', 'id_autocarro'], true)) return [];
    $datas = array_column($novas, 'data');
    $de  = date('Y-m-d', strtotime(min($datas) . ' -3 days'));   // viagens longas começam dias antes
    $ate = date('Y-m-d', strtotime(max($datas) . ' +3 days'));
    $idsNovas = array_map('intval', array_column($novas, 'id_viagem'));

    $st = $pdo->prepare("SELECT v.id_viagem, v.data, v.hora_partida, ro.origem, ro.destino, ro.duracao_estimada
                         FROM viagem v JOIN rota ro ON ro.id_rota = v.id_rota
                         WHERE v.$coluna = ? AND v.estado IN " . VIAGEM_ESTADOS_ABERTOS . " AND v.data BETWEEN ? AND ?");
    $st->execute([$id, $de, $ate]);
    $existentes = array_filter($st->fetchAll(), fn($v) => !in_array((int)$v['id_viagem'], $idsNovas, true));

    $sobrepoe = function (array $a, array $b): bool {
        [$a1, $a2] = intervalo_viagem($a['data'], $a['hora_partida'], $a['duracao_estimada']);
        [$b1, $b2] = intervalo_viagem($b['data'], $b['hora_partida'], $b['duracao_estimada']);
        return $a1 < $b2 && $b1 < $a2;
    };

    $msgs = [];
    foreach ($novas as $i => $n) {
        foreach ($existentes as $e) {
            if ($sobrepoe($n, $e)) { $msgs[] = descricao_viagem($n) . ' coincide com ' . descricao_viagem($e) . ' (já atribuída).'; break; }
        }
        foreach ($novas as $j => $o) {
            if ($j > $i && $sobrepoe($n, $o)) { $msgs[] = descricao_viagem($n) . ' coincide com ' . descricao_viagem($o) . ' (ambas seleccionadas).'; break; }
        }
    }
    return $msgs;
}

function conflitos_do_cobrador(PDO $pdo, int $idCobrador, array $novas): array {
    return conflitos_recurso($pdo, 'id_cobrador', $idCobrador, $novas);
}
