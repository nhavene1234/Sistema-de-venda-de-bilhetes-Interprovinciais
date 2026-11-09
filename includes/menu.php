<?php
// includes/menu.php - menu lateral por perfil e ícones

function icone_svg(string $nome): string {
    static $i = [
        'grelha'   => '<rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/>',
        'bilhete'  => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'calendario' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/>',
        'rota'     => '<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>',
        'pessoas'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'carteira' => '<path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"/><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>',
        'utilizador'=> '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>',
        'definicoes'=> '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1 7 17M17 7l2.1-2.1"/>',
        'escudo'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>',
        'documento'=> '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h8"/>',
        'edificio' => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M12 6h.01M16 6h.01M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01"/>',
        'leitor'   => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/>',
        'grafico'  => '<path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 5-6"/>',
        'sino'     => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
        'pesquisa' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'onibus'   => '<rect x="4" y="3" width="16" height="14" rx="2"/><path d="M4 11h16M8 21v-4M16 21v-4"/>',
        'motorista'=> '<rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2"/><path d="M13 10h5M13 14h4"/>',
        'suporte'  => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.5"/><path d="m5.6 5.6 3.9 3.9M14.5 14.5l3.9 3.9M18.4 5.6l-3.9 3.9M9.5 14.5l-3.9 3.9"/>',
        'sair'     => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
    ];
    return '<svg class="icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
         . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($i[$nome] ?? '') . '</svg>';
}

// cada item: [rótulo, caminho, ícone]
function menu_do_perfil(string $perfil): array {
    $dados = ['Dados Pessoais', 'conta/dados_pessoais.php', 'utilizador'];
    switch ($perfil) {
        case 'administrador':
            return [
                ['Visão geral',     'administrador/index.php',          'grelha'],
                ['Utilizadores',    'administrador/utilizadores.php',   'pessoas'],
                ['Passageiros',     'administrador/passageiro.php',     'utilizador'],
                ['Suporte',         'administrador/suporte.php',        'suporte'],
                ['Permissões',      'administrador/permissoes.php',     'escudo'],
                ['Transportadoras', 'administrador/transportadoras.php','edificio'],
                ['Auditoria',       'administrador/auditoria.php',      'documento'],
                ['Segurança',       'administrador/seguranca.php',      'definicoes'],
            ];
        case 'bilheteiro':
            return [
                ['Vender Bilhete',     'bilheteiro/index.php',   'bilhete'],
                ['Lugares Disponíveis','bilheteiro/lugares.php', 'grelha'],
                ['Vendas do Dia',      'bilheteiro/vendas.php',  'carteira'],
                ['Perfil',             'conta/dados_pessoais.php','utilizador'],
            ];
        case 'gestor':
            return [
                ['Dashboard',    'gestor/index.php',         'grelha'],
                ['Vendas',       'gestor/vendas.php',        'documento'],
                ['Reservas',     'gestor/reservas.php',      'bilhete'],
                ['Relatórios',   'gestor/relatorios.php',    'carteira'],
                ['Rotas',        'gestor/rotas.php',         'rota'],
                ['Viagens',      'gestor/viagens.php',       'calendario'],
                ['Autocarros',   'gestor/autocarros.php',    'onibus'],
                ['Motoristas',   'gestor/motoristas.php',    'motorista'],
                ['Cobradores',   'gestor/cobradores.php',    'leitor'],
                ['Estatísticas', 'gestor/estatisticas.php',  'grafico'],
                ['Conta',        'conta/dados_pessoais.php', 'utilizador'],
            ];
        case 'cobrador':
            return [
                ['Viagens de Hoje',  'cobrador/index.php',    'leitor'],
                $dados,
            ];
        default: // passageiro
            return [
                ['Pesquisar Viagem',  'passageiro/index.php',     'rota'],
                ['As Minhas Viagens', 'passageiro/historico.php', 'bilhete'],
                $dados,
            ];
    }
}
