<?php
// includes/bilheteiro.php - funções da venda presencial
require_once __DIR__ . '/passageiro.php';

/** Transportadora a que o bilheteiro está associado (lida da base de dados). Sem associação, não pode vender. */
function transportadora_do_bilheteiro(PDO $pdo): array {
    $u = utilizador_logado();
    $st = $pdo->prepare('SELECT t.id_transportadora, t.nome, t.activa FROM utilizador u
                         LEFT JOIN transportadora t ON t.id_transportadora = u.id_transportadora WHERE u.id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    $t = $st->fetch();
    if (!$t || !$t['id_transportadora']) {
        http_response_code(403);
        die('A sua conta não está associada a nenhuma transportadora. Contacte o administrador.');
    }
    if (!(int)$t['activa']) {
        http_response_code(403);
        die('A transportadora ' . htmlspecialchars($t['nome']) . ' está desactivada. Contacte o administrador.');
    }
    return ['id' => (int)$t['id_transportadora'], 'nome' => $t['nome']];
}

function nome_metodo(?string $m): string {
    return ['numerario' => 'Numerário', 'mpesa' => 'M-Pesa', 'emola' => 'e-Mola', 'cartao' => 'Cartão'][$m] ?? '—';
}

function classe_metodo(?string $m): string {
    return 'mt-' . ($m ?: 'numerario');
}

/** Indicador dos 3 passos da venda. */
function passos_venda(int $activo): string {
    $p = [['Escolher viagem', 'Rotas, horários e preço'], ['Escolher lugar', 'Mapa interactivo do autocarro'],
          ['Dados e pagamento', 'Confirmação e emissão']];
    $h = '<div class="passos-venda nao-imprimir">';
    foreach ($p as $i => [$t, $s]) {
        $n = $i + 1;
        $cls = $n < $activo ? 'feito' : ($n === $activo ? 'actual' : '');
        if ($i > 0) $h .= '<span class="pv-linha ' . ($n <= $activo ? 'feita' : '') . '"></span>';
        $h .= '<div class="pv ' . $cls . '"><span class="bola">' . ($n < $activo ? '✓' : $n) . '</span>'
            . '<span><b>' . e($t) . '</b><small>' . e($s) . '</small></span></div>';
    }
    return $h . '</div>';
}

/** Lugares do autocarro por fila e conjunto de lugares ocupados (pendentes ou confirmados). */
function carregar_mapa(PDO $pdo, array $viagem, ?int $liberarDoBilheteiro = null): array {
    $st = $pdo->prepare('SELECT id_assento, numero FROM assento WHERE id_autocarro = ?');
    $st->execute([$viagem['id_autocarro']]);
    $assentos = $st->fetchAll();
    $cfg = $pdo->prepare('SELECT configuracao_assentos FROM autocarro WHERE id_autocarro = ?');
    $cfg->execute([$viagem['id_autocarro']]);
    $filas = organizar_filas($assentos, (string)($cfg->fetchColumn() ?: '2+2'));

    $sql = "SELECT id_assento FROM reserva WHERE id_viagem = ? AND estado IN ('pendente','confirmada')";
    $p = [$viagem['id_viagem']];
    if ($liberarDoBilheteiro) { $sql .= " AND NOT (estado = 'pendente' AND id_bilheteiro = ?)"; $p[] = $liberarDoBilheteiro; }
    $st = $pdo->prepare($sql);
    $st->execute($p);
    return [$filas, array_flip($st->fetchAll(PDO::FETCH_COLUMN))];
}

/** Desenha o autocarro. $clicavel: lugares livres são botões; senão apenas visualização. */
function mapa_autocarro_html(array $filas, array $ocupados, bool $clicavel, string $id = 'mapa-bus'): string {
    $lugar = function (?array $a) use ($ocupados, $clicavel): string {
        if (!$a) return '<span></span>';
        $oc = isset($ocupados[$a['id_assento']]);
        $dados = 'data-id="' . (int)$a['id_assento'] . '" data-num="' . e($a['numero']) . '"';
        if ($clicavel && !$oc) return '<button type="button" class="bus-lugar" ' . $dados . '>' . e($a['numero']) . '</button>';
        return '<span class="bus-lugar' . ($oc ? ' ocupado' : '') . ' fixo" ' . $dados . '>' . e($a['numero']) . '</span>';
    };
    $h = '<div class="bus" id="' . e($id) . '"><div class="bus-topo"><span>' . ic('volante') . '</span>'
       . '<span class="cabine">CABINE</span><span>' . ic('entrar') . '</span></div>';
    foreach ($filas as $n => $f) {
        $h .= '<div class="bus-fila">' . $lugar($f['A'] ?? null) . $lugar($f['B'] ?? null) . '<span class="n">' . $n . '</span>'
            . $lugar($f['C'] ?? null) . $lugar($f['D'] ?? null) . '</div>';
    }
    $h .= '<div class="bus-legenda"><span><i class="l-livre"></i>Livre</span><span><i class="l-ocupado"></i>Ocupado</span>'
        . ($clicavel ? '<span><i class="l-sel"></i>Seleccionado</span>' : '') . '</div></div>';
    return $h;
}

function inicial_empresa(string $nome): string {
    $cores = [['#fff3d1', '#b27700'], ['#e3f4fd', '#0b6fb0'], ['#fdeaec', '#c4303c'], ['#e6f7ee', '#0d8a45'], ['#efe9ff', '#6c47e8']];
    [$bg, $fg] = $cores[crc32($nome) % count($cores)];
    return '<span class="inicial" style="background:' . $bg . ';color:' . $fg . '">' . e(mb_strtoupper(mb_substr($nome, 0, 1))) . '</span>';
}

/** Reservas (lugares) de uma venda do bilheteiro. */
function carregar_venda(PDO $pdo, string $grupo, int $idBilheteiro, string $estado): array {
    $st = $pdo->prepare(
        'SELECT rs.*, v.data, v.hora_partida, v.preco, v.classe, v.id_autocarro, ro.origem, ro.destino, t.nome AS transportadora,
                a.numero AS lugar, b.codigo_qr, p.metodo, p.valor, p.estado AS pagamento_estado
         FROM reserva rs
         JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
         JOIN transportadora t ON t.id_transportadora = ro.id_transportadora JOIN assento a ON a.id_assento = rs.id_assento
         LEFT JOIN bilhete b ON b.id_reserva = rs.id_reserva LEFT JOIN pagamento p ON p.id_reserva = rs.id_reserva
         WHERE rs.grupo_venda = ? AND rs.id_bilheteiro = ? AND rs.estado = ? ORDER BY a.numero'
    );
    $st->execute([$grupo, $idBilheteiro, $estado]);
    return $st->fetchAll();
}

function texto_bilhetes(int $n): string {
    return $n === 1 ? 'Bilhete simples' : ($n === 2 ? 'Bilhete duplo' : "$n bilhetes");
}
