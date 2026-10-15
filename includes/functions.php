<?php
// Segédfüggvények: URL-ek, kiíratás, űrlapkezelés, jogosultság, adatbázis

// ---------- URL-ek és átirányítás ----------

// Kiszámolja, milyen URL alatt fut az oldal (pl. XAMPP almappában vagy tárhely gyökerében)
function detectBaseUrl(string $appRoot): string
{
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $appRoot = realpath($appRoot);

    if ($docRoot === false || $appRoot === false || strncasecmp($appRoot, $docRoot, strlen($docRoot)) !== 0) {
        return '';
    }

    $relative = str_replace('\\', '/', substr($appRoot, strlen($docRoot)));
    $segments = array_filter(explode('/', $relative), 'strlen');

    return $segments ? '/' . implode('/', array_map('rawurlencode', $segments)) : '';
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

// ---------- Kiíratás ----------

// Minden felhasználótól vagy adatbázisból származó szöveget ezzel írunk ki (XSS védelem)
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Egyszeri üzenetek (siker / hiba), amelyek a következő oldalbetöltéskor jelennek meg
function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type][] = $message;
}

function takeFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

// ---------- Űrlapok ----------

// Csak érvényes UTF-8 szöveget fogadunk el, minden más (tömb, hibás kódolás) üres értéknek számít
function post(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) && mb_check_encoding($value, 'UTF-8') ? $value : '';
}

// Hibás beküldés után visszatöltjük a beírt adatokat (jelszót soha)
function rememberOld(array $values): void
{
    $_SESSION['old'] = $values;
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function forgetOld(): void
{
    unset($_SESSION['old']);
}

// Szóközök levágása és a többszörös szóközök összevonása
function cleanData(string $data): string
{
    return preg_replace('/\s+/u', ' ', trim($data));
}

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('index.php');
    }
}

// ---------- CSRF védelem ----------

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    if (!hash_equals(csrfToken(), post('csrf_token'))) {
        flash('danger', 'Lejárt az űrlap, kérlek próbáld újra.');
        redirect('index.php');
    }
}

// ---------- Bejelentkezés és jogosultság ----------

function currentUser(): ?array
{
    return $_SESSION['loggedInUser'] ?? null;
}

function isAdmin(): bool
{
    return isset($_SESSION['admin']);
}

// A zárt kategóriák olvasásához felhasználó vagy admin bejelentkezés kell
function isLoggedIn(): bool
{
    return currentUser() !== null || isAdmin();
}

function requireLogin(): void
{
    if (currentUser() === null) {
        flash('warning', 'Az oldal megtekintéséhez jelentkezz be.');
        redirect('login/login.php');
    }
}

// Ha a bejelentkezett felhasználó közben törlődött az adatbázisból, kiléptetjük
function logoutMissingUser(?array $user): void
{
    if ($user === null) {
        unset($_SESSION['loggedInUser']);
        flash('warning', 'A felhasználói fiók nem található, kérlek jelentkezz be újra.');
        redirect('login/login.php');
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        flash('danger', 'Ehhez az oldalhoz admin jogosultság szükséges.');
        redirect('login/login.php');
    }
}

// ---------- Adatbázis (prepared statement) ----------

function dbExecute(mysqli $db, string $sql, array $params = []): mysqli_stmt
{
    $stmt = mysqli_prepare($db, $sql);

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            $types .= is_int($param) ? 'i' : 's';
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    return $stmt;
}

function dbFetchAll(mysqli $db, string $sql, array $params = []): array
{
    $result = mysqli_stmt_get_result(dbExecute($db, $sql, $params));
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function dbFetchOne(mysqli $db, string $sql, array $params = []): ?array
{
    $result = mysqli_stmt_get_result(dbExecute($db, $sql, $params));
    return mysqli_fetch_assoc($result) ?: null;
}
