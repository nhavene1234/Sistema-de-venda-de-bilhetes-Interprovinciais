<?php
// includes/viagens.php - criação de viagens a partir de uma rota já existente (gestor).
require_once __DIR__ . '/cobradores.php';

const CLASSES_VIAGEM = ['Convencional', 'Executivo · AC', 'Semi-luxo', 'Executivo nocturno'];

/**
 * Cria uma viagem (ou uma por dia, durante $in['dias'] dias) numa rota da transportadora.
 * $in: data (Y-m-d), hora (HH:MM), dias, classe, preco, autocarro (0 = automático), motorista, cobrador (0 = nenhum).
 * Valida autocarro, motorista e cobrador (pertença, disponibilidade, validades e sobreposição de horários).
 * Devolve ['n' => criadas, 'ignoradas' => já existentes] ou ['erro' => mensagem].
 */
function criar_viagens(PDO $pdo, int $idT, array $rota, array $in): array {
    $hora = $in['hora'] . ':00';
    $dias = max(1, min(120, (int)$in['dias']));
    $min = array_map('intval', explode(':', $rota['duracao_estimada'] ?: '00:00'));
    $duracaoMin = $min[0] * 60 + $min[1];
    $chegada = date('H:i:s', strtotime("1970-01-01 $hora") + $duracaoMin * 60);

    // datas a criar (as que já têm viagem nesta rota e hora ficam de fora)
    $existe = $pdo->prepare('SELECT COUNT(*) FROM viagem WHERE id_rota = ? AND data = ? AND hora_partida = ? AND estado <> \'cancelada\'');
    $datas = []; $ignoradas = 0;
    for ($i = 0; $i < $dias; $i++) {
        $d = date('Y-m-d', strtotime($in['data'] . " +$i day"));
        $existe->execute([$rota['id_rota'], $d, $hora]);
        if ($existe->fetchColumn() > 0) { $ignoradas++; continue; }
        $datas[] = $d;
    }
    if (!$datas) return ['erro' => 'já existe uma viagem desta rota ' . ($dias > 1 ? 'em todos esses dias' : 'nessa data') . ' à mesma hora.'];
    if (strtotime($datas[0] . " $hora") <= time()) return ['erro' => 'a hora de partida tem de ser no futuro.'];
    $ultima = end($datas);

    // viagens "virtuais" para detectar sobreposições antes de gravar
    $novas = [];
    foreach ($datas as $k => $d) {
        $novas[] = ['id_viagem' => -($k + 1), 'data' => $d, 'hora_partida' => $hora, 'duracao_estimada' => $rota['duracao_estimada'],
                    'origem' => $rota['origem'], 'destino' => $rota['destino']];
    }
    $conflito = fn(string $col, int $id) => conflitos_recurso($pdo, $col, $id, $novas);

    // ---- autocarro ----
    $sqlBus = 'SELECT a.id_autocarro, a.codigo_frota, a.estado, a.validade_inspecao,
                      EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = a.id_autocarro) AS tem_lugares
               FROM autocarro a WHERE a.id_transportadora = ?';
    if ((int)$in['autocarro']) {
        $st = $pdo->prepare($sqlBus . ' AND a.id_autocarro = ?');
        $st->execute([$idT, (int)$in['autocarro']]);
        $bus = $st->fetch();
        if (!$bus) return ['erro' => 'o autocarro escolhido não pertence à sua transportadora.'];
        if ($bus['estado'] !== 'disponivel') return ['erro' => 'o autocarro ' . $bus['codigo_frota'] . ' não está disponível.'];
        if (!(int)$bus['tem_lugares']) return ['erro' => 'o autocarro ' . $bus['codigo_frota'] . ' não tem lugares registados.'];
        if ($bus['validade_inspecao'] !== null && $bus['validade_inspecao'] < $ultima)
            return ['erro' => 'a inspecção do autocarro ' . $bus['codigo_frota'] . ' expira em ' . date('d/m/Y', strtotime($bus['validade_inspecao'])) . ', antes da última viagem.'];
        if ($c = $conflito('id_autocarro', (int)$bus['id_autocarro']))
            return ['erro' => 'o autocarro ' . $bus['codigo_frota'] . ' já está ocupado: ' . $c[0]];
    } else {
        $st = $pdo->prepare($sqlBus . " AND a.estado = 'disponivel' AND (a.validade_inspecao IS NULL OR a.validade_inspecao >= ?)
                                        AND EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = a.id_autocarro) ORDER BY a.id_autocarro");
        $st->execute([$idT, $ultima]);
        $bus = null;
        foreach ($st->fetchAll() as $cand) { if (!$conflito('id_autocarro', (int)$cand['id_autocarro'])) { $bus = $cand; break; } }
        if (!$bus) return ['erro' => 'não há nenhum autocarro disponível, com lugares, inspecção válida e livre nesse horário.'];
    }

    // ---- motorista ----
    $idM = (int)$in['motorista'] ?: null;
    if ($idM) {
        $st = $pdo->prepare('SELECT nome, estado, carta_validade FROM motorista WHERE id_motorista = ? AND id_transportadora = ?');
        $st->execute([$idM, $idT]);
        $m = $st->fetch();
        if (!$m) return ['erro' => 'o motorista escolhido não pertence à sua transportadora.'];
        if ($m['estado'] !== 'disponivel') return ['erro' => 'o motorista ' . $m['nome'] . ' não está disponível.'];
        if ($m['carta_validade'] < $ultima) return ['erro' => 'a carta de condução de ' . $m['nome'] . ' expira em ' . date('d/m/Y', strtotime($m['carta_validade'])) . ', antes da última viagem.'];
        if ($c = $conflito('id_motorista', $idM)) return ['erro' => 'o motorista ' . $m['nome'] . ' já está ocupado: ' . $c[0]];
    }

    // ---- cobrador ----
    $idC = (int)$in['cobrador'] ?: null;
    if ($idC) {
        $cob = cobrador_da_transportadora($pdo, $idC, $idT);
        if (!$cob) return ['erro' => 'o cobrador escolhido não pertence à sua transportadora.'];
        if (!(int)$cob['activo']) return ['erro' => 'a conta de ' . $cob['nome'] . ' está desactivada.'];
        if ($c = $conflito('id_cobrador', $idC)) return ['erro' => 'o cobrador ' . $cob['nome'] . ' já está ocupado: ' . $c[0]];
    }

    $pdo->beginTransaction();
    try {
        $ins = $pdo->prepare("INSERT INTO viagem (data, hora_partida, hora_chegada, preco, classe, estado, id_rota, id_autocarro, id_motorista, id_cobrador)
                              VALUES (?, ?, ?, ?, ?, 'agendada', ?, ?, ?, ?)");
        foreach ($datas as $d) $ins->execute([$d, $hora, $chegada, $in['preco'], $in['classe'], $rota['id_rota'], (int)$bus['id_autocarro'], $idM, $idC]);
        $pdo->commit();
    } catch (Throwable $ex) {
        $pdo->rollBack();
        return ['erro' => 'não foi possível gravar as viagens. Tente novamente.'];
    }
    return ['n' => count($datas), 'ignoradas' => $ignoradas, 'autocarro' => $bus['codigo_frota']];
}
