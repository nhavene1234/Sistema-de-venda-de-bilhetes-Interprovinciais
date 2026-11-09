<?php
require_once __DIR__ . '/../includes/sessao.php';
exigir_perfil(['administrador']);

$permissoes = $pdo->query('SELECT * FROM permissao ORDER BY id_permissao')->fetchAll();
$matriz = [];
foreach ($pdo->query('SELECT perfil, id_permissao, concedida FROM perfil_permissao')->fetchAll() as $r) {
    $matriz[(int)$r['id_permissao']][$r['perfil']] = (int)$r['concedida'];
}
$colunas = ['bilheteiro', 'cobrador', 'gestor', 'administrador'];

$tituloPagina = 'Permissões';
$migalhas = ['Painel Admin', 'Gerir permissões'];
$paginaActiva = 'administrador/permissoes.php';
require __DIR__ . '/../includes/painel_inicio.php';
?>
<h1 class="titulo-pagina">Matriz de Permissões</h1>
<p class="subtitulo-pagina">Defina os níveis de acesso globais para cada tipo de utilizador no sistema.</p>

<section class="cartao">
  <div class="tabela-rolar">
  <table class="tabela matriz" id="matriz" data-csrf="<?= e(csrf_token()) ?>" data-url="<?= e(url('api/permissao_alternar.php')) ?>">
    <thead><tr><th>Permissão do sistema</th>
      <?php foreach ($colunas as $c): ?><th class="centro"><?= e(nome_perfil($c)) ?></th><?php endforeach; ?></tr></thead>
    <tbody>
    <?php foreach ($permissoes as $pm): $idp = (int)$pm['id_permissao']; ?>
      <tr class="<?= $pm['alerta_seguranca'] ? 'alerta-linha' : '' ?>">
        <td><?= e($pm['descricao']) ?><?= $pm['alerta_seguranca'] ? '<span class="etq-alerta">ALERTA DE SEGURANÇA</span>' : '' ?></td>
        <?php foreach ($colunas as $c): $bloqueado = $c === 'administrador'; ?>
          <td class="centro">
            <label class="interruptor" title="<?= $bloqueado ? 'O administrador mantém sempre todas as permissões' : '' ?>">
              <input type="checkbox" data-perm="<?= $idp ?>" data-perfil="<?= $c ?>"
                     <?= !empty($matriz[$idp][$c]) || $bloqueado ? 'checked' : '' ?> <?= $bloqueado ? 'disabled' : '' ?>>
              <span></span>
            </label>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <p class="nota-aviso">⚠ As alterações realizadas nesta matriz afectam de imediato todos os utilizadores associados a cada perfil. Tenha atenção ao conceder permissões administrativas.</p>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
