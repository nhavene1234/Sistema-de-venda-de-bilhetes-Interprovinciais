<?php
// includes/funcoes.php - funções auxiliares usadas em todo o sistema

function e($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function url(string $caminho = ''): string {
    return BASE_URL . '/' . ltrim($caminho, '/');
}

function redirecionar(string $caminho): void {
    header('Location: ' . url($caminho));
    exit;
}

function ip_cliente(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function iniciais(string $nome): string {
    $partes = preg_split('/\s+/', trim($nome));
    $i = mb_strtoupper(mb_substr($partes[0], 0, 1));
    if (count($partes) > 1) {
        $i .= mb_strtoupper(mb_substr(end($partes), 0, 1));
    }
    return $i;
}

// ---------- mensagens (flash) ----------
function flash(string $tipo, string $mensagem): void {
    $_SESSION['flash'] = ['tipo' => $tipo, 'msg' => $mensagem];
}

function flash_obter(): ?array {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// ---------- protecção CSRF ----------
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valido(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// ---------- auditoria ----------
function registar_log(PDO $pdo, ?int $idUtilizador, string $accao, ?string $entidade = null,
                      ?int $idEntidade = null, ?string $detalhes = null): void {
    try {
        $st = $pdo->prepare(
            'INSERT INTO log_transaccao (id_utilizador, accao, entidade, id_entidade, detalhes, ip)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $st->execute([$idUtilizador, $accao, $entidade, $idEntidade, $detalhes, ip_cliente()]);
    } catch (PDOException $e) {
        // a auditoria nunca deve impedir a operação principal
    }
}

// ---------- configurações ----------
function config_valor(PDO $pdo, string $chave, $padrao = null) {
    try {
        $st = $pdo->prepare('SELECT valor FROM configuracao WHERE chave = ?');
        $st->execute([$chave]);
        $v = $st->fetchColumn();
        return $v === false ? $padrao : $v;
    } catch (PDOException $e) {
        return $padrao;
    }
}

// ---------- palavra-passe ----------
function validar_nova_senha(string $senha): ?string {
    if (strlen($senha) < 8) return 'A palavra-passe deve ter pelo menos 8 caracteres.';
    if (!preg_match('/[A-Za-z]/', $senha) || !preg_match('/\d/', $senha)) {
        return 'A palavra-passe deve conter letras e números.';
    }
    return null;
}

// ---------- perfis ----------
function nome_perfil(string $perfil): string {
    return [
        'passageiro' => 'Passageiro', 'bilheteiro' => 'Bilheteiro', 'cobrador' => 'Cobrador',
        'gestor' => 'Gestor', 'administrador' => 'Administrador',
    ][$perfil] ?? ucfirst($perfil);
}

function nome_secao(string $perfil): string {
    return [
        'passageiro' => 'Passageiro', 'bilheteiro' => 'Bilheteira', 'cobrador' => 'Cobrança',
        'gestor' => 'Gestão', 'administrador' => 'Administração',
    ][$perfil] ?? 'Painel';
}

function pagina_inicial_perfil(string $perfil): string {
    return [
        'passageiro' => 'passageiro/index.php', 'bilheteiro' => 'bilheteiro/index.php',
        'cobrador' => 'cobrador/index.php', 'gestor' => 'gestor/index.php',
        'administrador' => 'administrador/index.php',
    ][$perfil] ?? 'auth/login.php';
}

// ---------- apresentação ----------
function logo_html(string $classe = ''): string {
    return '<span class="logo ' . e($classe) . '"><svg class="logo-icone" viewBox="0 0 32 32" aria-hidden="true">'
         . '<defs><linearGradient id="lg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1fb0f0"/>'
         . '<stop offset="1" stop-color="#0a5bd6"/></linearGradient></defs>'
         . '<rect width="32" height="32" rx="8" fill="url(#lg)"/><rect x="9" y="7" width="14" height="14" rx="3" fill="#fff"/>'
         . '<rect x="11" y="10" width="10" height="4" rx="1" fill="#0a5bd6"/>'
         . '<circle cx="12.5" cy="24" r="1.6" fill="#fff"/><circle cx="19.5" cy="24" r="1.6" fill="#fff"/></svg>'
         . '<span class="logo-texto">Moz<span>Transport</span></span></span>';
}

function avatar_html(array $u, string $classe = ''): string {
    if (!empty($u['foto'])) {
        return '<span class="avatar ' . e($classe) . '"><img src="' . e(url($u['foto'])) . '" alt=""></span>';
    }
    $cores = ['#12a150', '#f08a24', '#6c47e8', '#0ea5e9', '#d62854', '#e6b800'];
    $cor = $cores[crc32((string)($u['nome'] ?? '')) % count($cores)];
    return '<span class="avatar ' . e($classe) . '" style="background:' . $cor . '">' . e(iniciais($u['nome'] ?? '?')) . '</span>';
}

// ---------- filtro global de transportadora (administrador) ----------
function filtro_transportadora(): ?int {
    if (isset($_GET['transportadora'])) {
        $v = (int)$_GET['transportadora'];
        $_SESSION['filtro_transp'] = $v > 0 ? $v : null;
    }
    return $_SESSION['filtro_transp'] ?? null;
}

function gerar_senha_temporaria(): string {
    $letras = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $s = '';
    for ($i = 0; $i < 7; $i++) {
        $s .= $letras[random_int(0, strlen($letras) - 1)];
    }
    return $s . '@' . random_int(10, 99);
}

function formatar_numero($n): string {
    return number_format((float)$n, 0, ',', ' ');
}

function classe_perfil(string $perfil): string {
    return 'etq-' . $perfil;
}

// ---------------------------------------------------------------------------
// Telemóveis moçambicanos: 9 dígitos, com prefixo válido.
// Para aceitar outro prefixo (ex.: 93), acrescente-o em PREFIXOS_TELEFONE.
// ---------------------------------------------------------------------------
const PREFIXOS_TELEFONE = ['82', '83', '84', '85', '86', '87'];   // Tmcel 82/83, Vodacom 84/85, Movitel 86/87
const PREFIXOS_MPESA    = ['84', '85'];                          // M-Pesa (Vodacom)
const PREFIXOS_EMOLA    = ['86', '87'];                          // e-Mola (Movitel)

/**
 * Valida um telemóvel e devolve os 9 dígitos, ou null se for inválido.
 * Aceita espaços/hífens e o indicativo opcional (+258, 258, 00258). Não corta números a mais:
 * "84123456789" (11 dígitos) é inválido.
 */
function telefone_normalizar(?string $t, array $prefixos = PREFIXOS_TELEFONE): ?string {
    $t = trim((string)$t);
    if ($t === '' || preg_match('/[^0-9+\s.\-]/', $t) || strpos($t, '+', 1) !== false) return null;
    $d = preg_replace('/\D/', '', $t);
    if (preg_match('/^(?:00)?258(\d{9})$/', $d, $m)) $d = $m[1];
    if (!preg_match('/^\d{9}$/', $d)) return null;
    return in_array(substr($d, 0, 2), $prefixos, true) ? $d : null;
}

/** Texto gravado na base de dados: +258 84 123 4567 */
function telefone_guardar(string $nove): string {
    return '+258 ' . substr($nove, 0, 2) . ' ' . substr($nove, 2, 3) . ' ' . substr($nove, 5);
}

// true = o contacto telefónico é obrigatório nos formulários de utilizadores; false = opcional (mas, se preenchido, tem de ser válido)
const CONTACTO_OBRIGATORIO = true;

const MSG_TELEFONE = 'O contacto deve ter 9 dígitos e começar por 82, 83, 84, 85, 86 ou 87 (ex.: 84 123 4567).';
