<?php
// includes/passageiro.php - funções da área do passageiro
require_once __DIR__ . '/app_layout.php';

const MESES_CURTOS  = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
const DIAS_CURTOS   = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

/** Máximo de lugares (bilhetes) que um passageiro pode comprar de uma só vez. Altere aqui para mudar o limite em todo o sistema. */
const MAX_LUGARES = 8;

/** "12,13,14", "12" ou um array → [12, 13, 14] (inteiros, sem repetidos, sem zeros), no máximo $max. */
function lista_ids($bruto, int $max = MAX_LUGARES): array {
    $partes = is_array($bruto) ? $bruto : explode(',', (string)$bruto);
    $ids = array_values(array_unique(array_filter(array_map('intval', $partes))));
    return array_slice($ids, 0, $max);
}

function data_curta(string $ymd): string {
    $t = strtotime($ymd);
    return date('j', $t) . ' ' . MESES_CURTOS[(int)date('n', $t) - 1];
}

function data_com_ano(string $ymd): string {
    return data_curta($ymd) . ' ' . date('Y', strtotime($ymd));
}

function hora_curta(?string $t): string {
    return $t ? substr($t, 0, 5) : '--:--';
}

function formatar_mt($v): string {
    return number_format((float)$v, 0, ',', '.') . ' MT';
}

function duracao_texto(string $partida, string $chegada): string {
    $a = strtotime("1970-01-01 $partida");
    $b = strtotime("1970-01-01 $chegada");
    if ($b <= $a) $b += 86400;
    $m = intdiv($b - $a, 60);
    return intdiv($m, 60) . 'h ' . str_pad((string)($m % 60), 2, '0', STR_PAD_LEFT) . 'm';
}

function primeiro_nome(string $nome): string {
    return explode(' ', trim($nome))[0];
}

function partida_ts(array $r): int {
    return strtotime($r['data'] . ' ' . $r['hora_partida']);
}

/** Explica porque é que uma viagem não pode ser vendida/reservada agora (a venda fecha à hora de partida; no próprio dia continua aberta). */
function motivo_indisponivel($v): string {
    if (!$v) return 'Esta viagem não existe.';
    if ($v['estado'] !== 'agendada') return 'Esta viagem foi cancelada ou já terminou.';
    if (partida_ts($v) <= time()) {
        return 'Esta viagem já partiu: a partida era às ' . substr($v['hora_partida'], 0, 5) . ' de ' . date('d/m', strtotime($v['data']))
             . ' e agora são ' . date('H:i') . ' (hora do servidor).';
    }
    return 'Esta viagem não está disponível para a sua conta.';
}

/** Últimos 9 dígitos de um número de telefone. */
function telefone_digitos(?string $t): string {
    return substr(preg_replace('/\D/', '', (string)$t), -9);
}

function telefone_formatado(string $nove): string {
    return '+258 ' . substr($nove, 0, 2) . ' ' . substr($nove, 2, 3) . ' ' . substr($nove, 5);
}

/** Liberta os lugares cujo bloqueio temporário já expirou (RN04). */
function libertar_reservas_expiradas(PDO $pdo): void {
    $pdo->prepare("UPDATE reserva SET estado = 'expirada'
                   WHERE estado = 'pendente' AND data_expiracao IS NOT NULL AND data_expiracao < ?")
        ->execute([date('Y-m-d H:i:s')]);
}

/** Carrega uma reserva do passageiro com todos os dados necessários. */
function carregar_reserva(PDO $pdo, int $id, int $idPassageiro): ?array {
    $st = $pdo->prepare(
        'SELECT rs.*, v.data, v.hora_partida, v.hora_chegada, v.preco, v.classe, v.estado AS viagem_estado,
                ro.origem, ro.destino, ro.id_transportadora, t.nome AS transportadora, a.numero AS lugar,
                b.codigo_qr, b.estado AS bilhete_estado,
                p.estado AS pagamento_estado, p.metodo, p.motivo_falha, p.valor
         FROM reserva rs
         JOIN viagem v ON v.id_viagem = rs.id_viagem
         JOIN rota ro ON ro.id_rota = v.id_rota
         JOIN transportadora t ON t.id_transportadora = ro.id_transportadora
         JOIN assento a ON a.id_assento = rs.id_assento
         LEFT JOIN bilhete b ON b.id_reserva = rs.id_reserva
         LEFT JOIN pagamento p ON p.id_reserva = rs.id_reserva
         WHERE rs.id_reserva = ? AND rs.id_passageiro = ?'
    );
    $st->execute([$id, $idPassageiro]);
    return $st->fetch() ?: null;
}

/** [texto, classe] do estado de uma reserva. */
function estado_reserva(array $r): array {
    $passada = partida_ts($r) < time();
    switch ($r['estado']) {
        case 'cancelada': return ['Cancelada', 'etq-vermelha'];
        case 'expirada':  return ['Expirada', 'etq-amarela'];
        case 'pendente':  return ['Pendente', 'etq-amarela'];
        default:          return $passada ? ['Concluída', 'etq-verde'] : ['Confirmado', 'etq-verde'];
    }
}

/** Ainda é possível cancelar/remarcar? (RN06) */
function dentro_do_prazo(PDO $pdo, array $r): bool {
    $horas = (int)config_valor($pdo, 'cancelamento_horas', 24);
    return partida_ts($r) - time() >= $horas * 3600;
}

function lugares_livres_sql(): string {
    return "(SELECT COUNT(*) FROM reserva rs WHERE rs.id_viagem = v.id_viagem AND rs.estado IN ('pendente','confirmada'))";
}

/**
 * Organiza os assentos de um autocarro em filas para desenhar o mapa.
 * Aceita os dois formatos de numeração existentes na BD:
 *  - "1A", "2C"... (dados demo): a letra define a coluna;
 *  - "01", "02"... (autocarros criados em gestor/autocarro_novo.php): as filas são
 *    calculadas pela configuração (2+2 = 4 lugares por fila, 2+1 = 3 lugares por fila: A, B e C).
 * Devolve [numeroFila => ['A' => assento, 'B' => assento, 'C' => assento, 'D' => assento]].
 */
function organizar_filas(array $assentos, string $config = '2+2'): array {
    $filas = [];
    $comLetra = true;
    foreach ($assentos as $a) {
        if (!preg_match('/^(\d+)([A-D])$/i', trim($a['numero']), $m)) { $comLetra = false; break; }
    }
    if ($comLetra) {
        foreach ($assentos as $a) {
            preg_match('/^(\d+)([A-D])$/i', trim($a['numero']), $m);
            $filas[(int)$m[1]][strtoupper($m[2])] = $a;
        }
        ksort($filas);
        return $filas;
    }
    usort($assentos, fn($x, $y) => strnatcmp($x['numero'], $y['numero']));
    $colunas = $config === '2+1' ? ['A', 'B', 'C'] : ['A', 'B', 'C', 'D'];
    foreach (array_chunk($assentos, count($colunas)) as $i => $grupo) {
        foreach ($grupo as $k => $a) $filas[$i + 1][$colunas[$k]] = $a;
    }
    return $filas;
}