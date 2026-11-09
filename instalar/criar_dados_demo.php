<?php
// instalar/criar_dados_demo.php
// Cria autocarros, assentos, rotas e viagens de demonstração (e alguns lugares já ocupados).
// Pode ser executado de novo para acrescentar dias: criar_dados_demo.php?ate=2027-03-31
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/conexao.php';
if (!AMBIENTE_DEMO) { die('Desactivado fora do ambiente de demonstração.'); }
set_time_limit(0);

$fim = $_GET['ate'] ?? '2027-01-15';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fim)) { die('Data inválida.'); }

// destino, km, duração (min), preço base
$pares = [
    ['Beira', 1200, 570, 2500], ['Nampula', 2000, 1260, 3300], ['Xai-Xai', 220, 180, 650],
    ['Inhambane', 470, 420, 1250], ['Quelimane', 1600, 840, 2850], ['Tete', 1500, 780, 3100],
];
// transportadora, hora de partida, classe, factor de preço, matrícula
$slots = [
    ['Nagi',     '05:30:00', 'Executivo · AC',      1.00, 'NAG-101-MP'],
    ['Lalgy',    '07:00:00', 'Semi-luxo',           0.94, 'LAL-201-MP'],
    ['CityLink', '20:00:00', 'Executivo nocturno',  1.08, 'CTL-301-MP'],
];

// outras transportadoras activas que ainda não têm rotas (ex.: criadas no ecrã do Administrador)
$horas = ['06:00:00', '09:00:00', '15:00:00', '21:00:00'];
$extras = $pdo->query("SELECT t.id_transportadora, t.nome FROM transportadora t WHERE t.activa = 1
                       AND NOT EXISTS (SELECT 1 FROM rota r WHERE r.id_transportadora = t.id_transportadora)")->fetchAll();
$i = 0;
foreach ($extras as $ex) {
    $conhecida = false;
    foreach ($slots as $s) { if (stripos($ex['nome'], $s[0]) === 0) $conhecida = true; }
    if ($conhecida) continue;   // Nagi, Lalgy e CityLink já estão nas definições acima
    $slots[] = [$ex['nome'], $horas[$i % 4], 'Convencional', 1.00, 'DEM-' . $ex['id_transportadora'] . '-MP'];
    $i++;
}

echo '<meta charset="UTF-8"><body style="font-family:sans-serif;max-width:680px;margin:40px auto"><h2>Dados de demonstração</h2>';

$pdo->beginTransaction();
try {
    $empresas = []; $autocarros = [];
    foreach ($slots as [$nome, , , , $matricula]) {
        $st = $pdo->prepare('SELECT id_transportadora FROM transportadora WHERE nome LIKE ? LIMIT 1');
        $st->execute([$nome . '%']);
        $idT = (int)$st->fetchColumn();
        if (!$idT) { throw new Exception("Transportadora '$nome' não encontrada. Importe primeiro o ficheiro bilhetes_interprovinciais.sql."); }
        $empresas[$nome] = $idT;

        // autocarro
        $st = $pdo->prepare('SELECT id_autocarro FROM autocarro WHERE matricula = ?');
        $st->execute([$matricula]);
        $idA = (int)$st->fetchColumn();
        if (!$idA) {
            $pdo->prepare('INSERT INTO autocarro (matricula, modelo, capacidade_total, id_transportadora) VALUES (?,?,?,?)')
                ->execute([$matricula, 'Marcopolo Paradiso', 44, $idT]);
            $idA = (int)$pdo->lastInsertId();
        }
        $autocarros[$nome] = $idA;

        // 44 assentos: 11 filas x A,B,C,D
        $st = $pdo->prepare('SELECT COUNT(*) FROM assento WHERE id_autocarro = ?');
        $st->execute([$idA]);
        if ((int)$st->fetchColumn() === 0) {
            $ins = $pdo->prepare('INSERT INTO assento (numero, tipo, id_autocarro) VALUES (?,?,?)');
            for ($f = 1; $f <= 11; $f++) foreach (['A', 'B', 'C', 'D'] as $c) $ins->execute([$f . $c, 'normal', $idA]);
        }
    }

    // rotas (ida e volta, por transportadora)
    $rotas = []; // [nome][origem|destino] => [id, minutos, preco]
    $selRota = $pdo->prepare('SELECT id_rota FROM rota WHERE origem = ? AND destino = ? AND id_transportadora = ?');
    $temPreco = (bool)$pdo->query("SHOW COLUMNS FROM rota LIKE 'preco_base'")->fetch();
    $insRota = $pdo->prepare($temPreco
        ? 'INSERT INTO rota (origem, destino, distancia_km, duracao_estimada, id_transportadora, preco_base) VALUES (?,?,?,?,?,?)'
        : 'INSERT INTO rota (origem, destino, distancia_km, duracao_estimada, id_transportadora) VALUES (?,?,?,?,?)');
    foreach ($slots as [$nome, , , $fator]) {
        foreach ($pares as [$cidade, $km, $min, $preco]) {
            foreach ([['Maputo', $cidade], [$cidade, 'Maputo']] as [$o, $d]) {
                $selRota->execute([$o, $d, $empresas[$nome]]);
                $idR = (int)$selRota->fetchColumn();
                if (!$idR) {
                    $dadosRota = [$o, $d, $km, sprintf('%02d:%02d:00', intdiv($min, 60), $min % 60), $empresas[$nome]];
                    if ($temPreco) $dadosRota[] = round($preco * $fator / 10) * 10;
                    $insRota->execute($dadosRota);
                    $idR = (int)$pdo->lastInsertId();
                }
                $rotas[$nome][] = [$idR, $min, $preco];
            }
        }
    }

    // viagens existentes (para não duplicar)
    $existentes = [];
    foreach ($pdo->query('SELECT id_rota, data, hora_partida FROM viagem')->fetchAll() as $v) {
        $existentes[$v['id_rota'] . '|' . $v['data'] . '|' . $v['hora_partida']] = true;
    }
    $insV = $pdo->prepare('INSERT INTO viagem (data, hora_partida, hora_chegada, preco, classe, estado, id_rota, id_autocarro)
                           VALUES (?,?,?,?,?,\'agendada\',?,?)');
    $novas = 0;
    for ($t = strtotime('tomorrow'); $t <= strtotime($fim); $t += 86400) {
        $dia = date('Y-m-d', $t);
        foreach ($slots as [$nome, $hora, $classe, $factor]) {
            foreach ($rotas[$nome] as [$idR, $min, $preco]) {
                if (isset($existentes["$idR|$dia|$hora"])) continue;
                $chegada = date('H:i:s', strtotime("1970-01-01 $hora") + $min * 60);
                $insV->execute([$dia, $hora, $chegada, round($preco * $factor / 10) * 10, $classe, $idR, $autocarros[$nome]]);
                $novas++;
            }
        }
    }

    // lugares já ocupados nas viagens dos próximos 21 dias (só viagens sem reservas)
    $sem = $pdo->prepare('SELECT v.id_viagem, v.id_autocarro FROM viagem v
                          WHERE v.data BETWEEN ? AND ? AND NOT EXISTS (SELECT 1 FROM reserva r WHERE r.id_viagem = v.id_viagem)');
    $sem->execute([date('Y-m-d', strtotime('tomorrow')), date('Y-m-d', strtotime('+21 days'))]);
    $viagens = $sem->fetchAll();
    $assentosBus = [];
    foreach ($autocarros as $idA) {
        $st = $pdo->prepare('SELECT id_assento FROM assento WHERE id_autocarro = ?');
        $st->execute([$idA]);
        $assentosBus[$idA] = $st->fetchAll(PDO::FETCH_COLUMN);
    }
    $ocupados = 0;
    foreach ($viagens as $i => $v) {
        $r = random_int(1, 14);
        $n = $r === 1 ? 44 : ($r <= 3 ? random_int(38, 41) : random_int(4, 30));
        $ids = $assentosBus[$v['id_autocarro']];
        shuffle($ids);
        $ids = array_slice($ids, 0, $n);
        $marcas = implode(',', array_fill(0, count($ids), "('confirmada','Reservado (demonstração)',?,?)"));
        $params = [];
        foreach ($ids as $idAs) { $params[] = $v['id_viagem']; $params[] = $idAs; }
        $pdo->prepare("INSERT INTO reserva (estado, nome_passageiro, id_viagem, id_assento) VALUES $marcas")->execute($params);
        $ocupados += count($ids);
    }

    $pdo->commit();
    echo "<p>Viagens novas criadas: <b>$novas</b> (até $fim).</p>";
    echo "<p>Lugares ocupados simulados: <b>$ocupados</b>.</p>";
    echo '<p style="color:#b00"><b>Importante:</b> apague este ficheiro depois de o executar.</p>';
    echo '<p><a href="../passageiro/">Abrir a área do passageiro</a></p>';
} catch (Throwable $e) {
    $pdo->rollBack();
    echo '<p style="color:#b00">Erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
