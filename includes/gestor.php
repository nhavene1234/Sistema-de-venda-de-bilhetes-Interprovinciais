<?php
// includes/gestor.php - funções da área do Gestor (dados sempre restritos à sua transportadora, RN13)
require_once __DIR__ . '/bilheteiro.php';

const MESES_LONGOS = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

// pagamentos confirmados de uma transportadora (parâmetro: id da transportadora)
const SQL_PAGAMENTOS = "FROM pagamento p JOIN reserva rs ON rs.id_reserva = p.id_reserva
                        JOIN viagem v ON v.id_viagem = rs.id_viagem JOIN rota ro ON ro.id_rota = v.id_rota
                        WHERE ro.id_transportadora = ? AND p.estado = 'confirmado'";

function data_extensa(string $ymd): string {
    $t = strtotime($ymd);
    return date('j', $t) . ' de ' . MESES_LONGOS[(int)date('n', $t) - 1] . ' de ' . date('Y', $t);
}

/** Ecrã "Acesso restrito" do protótipo. */
function acesso_restrito(string $mensagem): void {
    global $pdo;
    http_response_code(403);
    $tituloPagina = 'Acesso restrito';
    $migalhas = ['Segurança', 'Bloqueio de Permissão'];
    $paginaActiva = '';
    require __DIR__ . '/painel_inicio.php';
    echo '<div class="acesso-restrito"><div class="ar-cartao"><span class="ar-icone">' . icone_svg('escudo') . '</span>'
       . '<h1>Acesso restrito</h1><p>' . e($mensagem) . '</p>'
       . '<a class="btn btn-primario btn-bloco" href="' . e(url('gestor/index.php')) . '">Voltar ao dashboard</a></div></div>';
    require __DIR__ . '/painel_fim.php';
    exit;
}

/** Transportadora do gestor, lida da base de dados. Sem associação, não há acesso. */
function transportadora_do_gestor(PDO $pdo): array {
    $u = utilizador_logado();
    $st = $pdo->prepare('SELECT t.id_transportadora, t.nome FROM utilizador x
                         LEFT JOIN transportadora t ON t.id_transportadora = x.id_transportadora WHERE x.id_utilizador = ?');
    $st->execute([$u['id_utilizador']]);
    $t = $st->fetch();
    if (!$t || !$t['id_transportadora']) {
        acesso_restrito('A sua conta não está associada a nenhuma transportadora. Contacte o administrador.');
    }
    return ['id' => (int)$t['id_transportadora'], 'nome' => $t['nome']];
}

/** Valida perfil + permissão (matriz do Administrador) e devolve a transportadora do gestor. */
function exigir_gestor(PDO $pdo, string $permissao): array {
    exigir_perfil(['gestor']);
    if (!tem_permissao($pdo, $permissao)) {
        acesso_restrito('O seu perfil não tem permissão para aceder a esta área. Contacte o administrador.');
    }
    return transportadora_do_gestor($pdo);
}

/** Receita e n.º de bilhetes por dia (dias sem vendas ficam a zero). */
function serie_diaria(PDO $pdo, int $idT, string $ini, string $fim): array {
    $st = $pdo->prepare('SELECT DATE(p.data_pagamento) AS d, SUM(p.valor) AS receita, COUNT(*) AS n ' . SQL_PAGAMENTOS
                        . ' AND DATE(p.data_pagamento) BETWEEN ? AND ? GROUP BY DATE(p.data_pagamento)');
    $st->execute([$idT, $ini, $fim]);
    $mapa = [];
    foreach ($st->fetchAll() as $r) $mapa[$r['d']] = $r;
    $out = [];
    for ($t = strtotime($ini); $t <= strtotime($fim); $t += 86400) {
        $d = date('Y-m-d', $t);
        $out[$d] = ['receita' => (float)($mapa[$d]['receita'] ?? 0), 'n' => (int)($mapa[$d]['n'] ?? 0)];
    }
    return $out;
}

/** Períodos rápidos do painel de vendas do gestor. */
const PERIODOS = ['hoje' => 'Hoje', 'ontem' => 'Ontem', 'semana' => 'Esta semana', 'mes' => 'Este mês', 'ano' => 'Este ano'];

/** [início, fim] de um período rápido, ou null se não for um período conhecido. */
function intervalo_periodo(string $p): ?array {
    $hoje = date('Y-m-d');
    switch ($p) {
        case 'hoje':   return [$hoje, $hoje];
        case 'ontem':  $o = date('Y-m-d', strtotime('-1 day')); return [$o, $o];
        case 'semana': return [date('Y-m-d', strtotime('monday this week')), $hoje];
        case 'mes':    return [date('Y-m-01'), $hoje];
        case 'ano':    return [date('Y-01-01'), $hoje];
    }
    return null;
}

/** Chave do período activo: um dos PERIODOS, 'personalizado' (datas manuais) ou o padrão. */
function periodo_escolhido(string $padrao = 'mes'): string {
    $p = $_GET['periodo'] ?? '';
    if (intervalo_periodo($p)) return $p;
    if (isset($_GET['inicio']) || isset($_GET['fim'])) return 'personalizado';
    return $padrao;
}

/** Período pedido por GET (?periodo=hoje|ontem|semana|mes|ano, ou inicio/fim); por defeito, os últimos N dias. */
function periodo_pedido(int $diasPadrao = 30, ?string $padrao = null): array {
    $hoje = date('Y-m-d');
    if ($iv = intervalo_periodo($_GET['periodo'] ?? '')) return $iv;
    if ($padrao && !isset($_GET['inicio']) && !isset($_GET['fim']) && ($iv = intervalo_periodo($padrao))) return $iv;
    $valida = fn($d) => is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
    $fim = $_GET['fim'] ?? '';
    $ini = $_GET['inicio'] ?? '';
    if (!$valida($fim) || $fim > $hoje) $fim = $hoje;
    if (!$valida($ini)) $ini = date('Y-m-d', strtotime("$fim -" . ($diasPadrao - 1) . ' days'));
    if ($ini > $fim) $ini = $fim;
    if (strtotime($fim) - strtotime($ini) > 366 * 86400) $ini = date('Y-m-d', strtotime("$fim -365 days"));
    return [$ini, $fim];
}

function variacao_pct(float $actual, float $anterior): ?int {
    return $anterior > 0 ? (int)round(($actual - $anterior) / $anterior * 100) : null;
}

function texto_variacao(?int $v, string $comparado): string {
    if ($v === null) return '<span class="var-neutra">Sem dados ' . e($comparado) . '</span>';
    return '<span class="' . ($v >= 0 ? 'var-pos' : 'var-neg') . '">' . ($v >= 0 ? '↗ +' : '↘ ') . $v . '% ' . e($comparado) . '</span>';
}

/** Mini-gráfico de linha para os cartões do dashboard. */
function svg_sparkline(array $valores, string $cor = '#12a150'): string {
    $v = array_values($valores);
    $n = count($v);
    if ($n < 2) return '';
    $max = max($v); $min = min($v); $r = ($max - $min) ?: 1;
    $pts = [];
    foreach ($v as $i => $x) $pts[] = round($i / ($n - 1) * 88 + 1, 1) . ',' . round(30 - ($x - $min) / $r * 26, 1);
    return '<svg class="spark" viewBox="0 0 90 32" preserveAspectRatio="none" aria-hidden="true"><polyline fill="none" stroke="' . $cor
         . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="' . implode(' ', $pts) . '"/></svg>';
}

/** Gráfico de linha com área. $rotuloMax: texto a mostrar sobre o ponto mais alto (opcional). */
function svg_linha(array $valores, array $rotulos, string $cor = '#12a150', ?string $rotuloMax = null, string $formato = 'mt'): string {
    $valores = array_values($valores);
    $rotulos = array_values($rotulos);
    $n = count($valores);
    if ($n < 2) return '<p class="pequeno">Sem dados suficientes para o gráfico.</p>';
    $W = 620; $H = 230; $px = 16; $py = 22; $base = $H - 28;
    $max = max($valores) ?: 1;
    $x = fn($i) => $px + $i / ($n - 1) * ($W - 2 * $px);
    $y = fn($v) => $base - $v / $max * ($base - $py);
    $pts = [];
    foreach ($valores as $i => $v) $pts[] = round($x($i), 1) . ',' . round($y($v), 1);
    $area = round($x(0), 1) . ',' . $base . ' ' . implode(' ', $pts) . ' ' . round($x($n - 1), 1) . ',' . $base;
    $id = 'g' . mt_rand(1000, 9999);

    $s = '<svg class="grafico" viewBox="0 0 ' . $W . ' ' . $H . '" role="img">'
       . '<defs><linearGradient id="' . $id . '" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="' . $cor . '" stop-opacity=".28"/>'
       . '<stop offset="1" stop-color="' . $cor . '" stop-opacity="0"/></linearGradient></defs>';
    foreach ([0, .25, .5, .75, 1] as $f) {
        $gy = round($base - $f * ($base - $py), 1);
        $s .= '<line x1="' . $px . '" x2="' . ($W - $px) . '" y1="' . $gy . '" y2="' . $gy . '" stroke="#e6ebf3" stroke-width="1"/>';
    }
    $s .= '<text x="' . $px . '" y="12" font-size="10" fill="#8493ab">' . e($formato === 'mt' ? formatar_mt($max) : formatar_numero($max) . ' bilhetes') . '</text>';
    $s .= '<polygon points="' . $area . '" fill="url(#' . $id . ')"/>';
    $s .= '<polyline points="' . implode(' ', $pts) . '" fill="none" stroke="' . $cor . '" stroke-width="2.4" stroke-linejoin="round" stroke-linecap="round"/>';
    $passo = max(1, (int)floor(($n - 1) / 5));
    for ($i = 0; $i < $n; $i += $passo) {
        $anc = $i === 0 ? 'start' : 'middle';
        $s .= '<text x="' . round($x($i), 1) . '" y="' . ($H - 8) . '" font-size="10" fill="#8493ab" text-anchor="' . $anc . '">' . e($rotulos[$i] ?? '') . '</text>';
    }
    if ($rotuloMax !== null && max($valores) > 0) {
        $im = array_search(max($valores), $valores, true);
        $cx = round($x($im), 1); $cy = round($y($valores[$im]), 1);
        $s .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="5" fill="#fff" stroke="' . $cor . '" stroke-width="2.4"/>';
        $tx = min($W - 70, max(70, $cx));
        $s .= '<rect x="' . ($tx - 62) . '" y="' . max(2, $cy - 34) . '" width="124" height="22" rx="6" fill="#0a2150"/>'
            . '<text x="' . $tx . '" y="' . (max(2, $cy - 34) + 15) . '" font-size="10" font-weight="700" fill="#fff" text-anchor="middle">' . e($rotuloMax) . '</text>';
    }
    return $s . '</svg>';
}

/**
 * Cria viagens diárias numa rota (a partir de amanhã), com autocarro e motorista opcionais.
 * Devolve ['n' => viagens criadas] ou ['erro' => mensagem].
 */
function gerar_viagens(PDO $pdo, int $idTransportadora, int $idRota, string $hora, int $dias, float $preco, int $duracaoMin,
                       string $classe, ?int $idAutocarro = null, ?int $idMotorista = null): array {
    $ultima = date('Y-m-d', strtotime('tomorrow +' . max(0, $dias - 1) . ' day'));

    // autocarro: o escolhido, ou o primeiro disponível com lugares e inspecção válida
    $sqlBus = "SELECT a.id_autocarro, a.codigo_frota, a.estado, a.validade_inspecao,
                      EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = a.id_autocarro) AS tem_lugares
               FROM autocarro a WHERE a.id_transportadora = ?";
    if ($idAutocarro) {
        $st = $pdo->prepare($sqlBus . ' AND a.id_autocarro = ?');
        $st->execute([$idTransportadora, $idAutocarro]);
    } else {
        $st = $pdo->prepare($sqlBus . " AND a.estado = 'disponivel' AND (a.validade_inspecao IS NULL OR a.validade_inspecao >= ?)
                                        AND EXISTS (SELECT 1 FROM assento s WHERE s.id_autocarro = a.id_autocarro) ORDER BY a.id_autocarro LIMIT 1");
        $st->execute([$idTransportadora, $ultima]);
    }
    $bus = $st->fetch();
    if (!$bus) {
        return ['erro' => $idAutocarro ? 'o autocarro escolhido não pertence à sua transportadora.'
                                       : 'não há autocarros disponíveis, com lugares e inspecção válida, na sua transportadora.'];
    }
    if ($bus['estado'] !== 'disponivel') return ['erro' => 'o autocarro ' . $bus['codigo_frota'] . ' não está disponível.'];
    if (!(int)$bus['tem_lugares']) return ['erro' => 'o autocarro ' . $bus['codigo_frota'] . ' não tem lugares registados.'];
    if ($bus['validade_inspecao'] !== null && $bus['validade_inspecao'] < $ultima) {
        return ['erro' => 'a inspecção do autocarro ' . $bus['codigo_frota'] . ' expira em ' . date('d/m/Y', strtotime($bus['validade_inspecao']))
                        . ', antes da última viagem (' . date('d/m/Y', strtotime($ultima)) . ').'];
    }

    // motorista: carta de condução válida na data de todas as viagens
    if ($idMotorista) {
        $st = $pdo->prepare('SELECT nome, estado, carta_validade FROM motorista WHERE id_motorista = ? AND id_transportadora = ?');
        $st->execute([$idMotorista, $idTransportadora]);
        $m = $st->fetch();
        if (!$m) return ['erro' => 'o motorista escolhido não pertence à sua transportadora.'];
        if ($m['estado'] !== 'disponivel') return ['erro' => 'o motorista ' . $m['nome'] . ' não está disponível.'];
        if ($m['carta_validade'] < $ultima) {
            return ['erro' => 'a carta de condução de ' . $m['nome'] . ' expira em ' . date('d/m/Y', strtotime($m['carta_validade']))
                            . ', antes da última viagem (' . date('d/m/Y', strtotime($ultima)) . ').'];
        }
    }

    $chegada = date('H:i:s', strtotime("1970-01-01 $hora") + $duracaoMin * 60);
    $existe = $pdo->prepare('SELECT COUNT(*) FROM viagem WHERE id_rota = ? AND data = ? AND hora_partida = ?');
    $ins = $pdo->prepare("INSERT INTO viagem (data, hora_partida, hora_chegada, preco, classe, estado, id_rota, id_autocarro, id_motorista)
                          VALUES (?, ?, ?, ?, ?, 'agendada', ?, ?, ?)");
    $n = 0;
    for ($i = 0; $i < $dias; $i++) {
        $d = date('Y-m-d', strtotime("tomorrow +$i day"));
        $existe->execute([$idRota, $d, $hora]);
        if ($existe->fetchColumn() > 0) continue;
        $ins->execute([$d, $hora, $chegada, $preco, $classe, $idRota, (int)$bus['id_autocarro'], $idMotorista]);
        $n++;
    }
    return ['n' => $n];
}

/** "26h 30m", "26:30" ou "26" → minutos (ou null se inválido). */
function duracao_para_minutos(string $txt): ?int {
    if (!preg_match('/^\s*(\d{1,2})\s*(?:h|:)?\s*(\d{0,2})\s*m?\s*$/i', $txt, $m)) return null;
    $min = ($m[2] === '' ? 0 : (int)$m[2]);
    if ($min > 59) return null;
    return (int)$m[1] * 60 + $min;
}

function formatar_mzn($v): string {
    return number_format((float)$v, 2, ',', '.');
}

/** Guarda um documento (PDF, JPG ou PNG, até 5 MB) fora do acesso público. Devolve [sucesso, caminho|erro|null]. */
function guardar_documento(?array $f, string $subpasta): array {
    if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return [true, null];
    if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Não foi possível carregar o ficheiro.'];
    if ($f['size'] > 5 * 1024 * 1024) return [false, 'O ficheiro excede o limite de 5 MB.'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;
    if (!$ext) return [false, 'Formato inválido. Use PDF, JPG ou PNG.'];
    $base = __DIR__ . '/../armazenamento/';
    $pasta = $base . $subpasta . '/';
    if (!is_dir($pasta)) mkdir($pasta, 0775, true);
    if (!is_file($base . '.htaccess')) file_put_contents($base . '.htaccess', "Require all denied\n");   // sem acesso directo pelo navegador
    $nome = bin2hex(random_bytes(12)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $pasta . $nome)) return [false, 'Não foi possível guardar o ficheiro.'];
    return [true, $subpasta . '/' . $nome];
}

/** Próximo código sequencial livre (ex.: MT-025) dentro de uma transportadora. */
function proximo_codigo(PDO $pdo, string $tabela, string $coluna, string $prefixo, int $idT): string {
    $st = $pdo->prepare("SELECT $coluna FROM $tabela WHERE id_transportadora = ?");
    $st->execute([$idT]);
    $max = 0;
    foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $c) {
        if (preg_match('/(\d+)\s*$/', (string)$c, $m)) $max = max($max, (int)$m[1]);
    }
    return $prefixo . str_pad((string)($max + 1), 3, '0', STR_PAD_LEFT);
}

/** [texto, classe] da validade de um documento (inspecção ou carta). */
function estado_validade(?string $data): array {
    if (!$data) return ['Sem data', 'etq-pendente'];
    $dias = (int)floor((strtotime($data) - strtotime(date('Y-m-d'))) / 86400);
    if ($dias < 0) return ['Expirada', 'etq-cancelado'];
    if ($dias <= 30) return ['Expira em ' . $dias . ' dia' . ($dias === 1 ? '' : 's'), 'etq-expirado'];
    return ['Válida', 'etq-pago'];
}

function paginas_lista(int $actual, int $total): array {
    if ($total <= 6) return range(1, $total);
    $p = [1];
    if ($actual > 3) $p[] = '…';
    for ($i = max(2, $actual - 1); $i <= min($total - 1, $actual + 1); $i++) $p[] = $i;
    if ($actual < $total - 2) $p[] = '…';
    $p[] = $total;
    return $p;
}