<?php
// Minden oldal ezzel kezdődik: session indítás, beállítások, adatbázis-kapcsolat és segédfüggvények betöltése

require_once __DIR__ . '/functions.php';

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

$config = require __DIR__ . '/db_config.php';

define('APP_ROOT', dirname(__DIR__));
define('BASE_URL', $config['base_url'] ?? detectBaseUrl(APP_ROOT));
define('DEMO_MODE', (bool)$config['demo_mode']);

// Kategóriák: adatbázisbeli érték => megjelenített név
const KATEGORIAK = [
    'Nyultartas' => 'Nyúltartás',
    'Etetes' => 'Etetés',
    'Betegsegek' => 'Betegségek',
];
// Ez az egy kategória bejelentkezés nélkül is olvasható
const NYILVANOS_KATEGORIA = 'Nyultartas';

// Hibás lekérdezésnél kivétel keletkezzen, ne csendes hiba
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $connection = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pw'], $config['db_name']);
    mysqli_set_charset($connection, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('NyusziCare adatbázis hiba: ' . $e->getMessage());
    http_response_code(500);
    exit('Az adatbázis jelenleg nem elérhető. Kérlek, próbáld újra később.');
}
