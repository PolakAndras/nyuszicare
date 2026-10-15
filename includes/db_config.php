<?php
// Alkalmazás és adatbázis beállítások.
// Az értékek környezeti változókból jönnek (pl. Docker), ha ezek nincsenek megadva, a helyi XAMPP alapértékek érvényesek.
// Tárhelyen a db_config.local.php fájlban lehet felülírni őket (mintának lásd: db_config.local.example.php).
// A db_config.local.php nem kerül fel a Git-re, így a jelszavak sem.

$config = [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pw' => getenv('DB_PASSWORD') ?: '',
    'db_name' => getenv('DB_NAME') ?: 'nyuszicare',
    // Demó módban az admin felület csak olvasható, így a nyilvános demót nem lehet "szétszedni"
    'demo_mode' => filter_var(getenv('DEMO_MODE') ?: false, FILTER_VALIDATE_BOOLEAN),
    // Az oldal gyökér URL-je (pl. "/nyuszicare"). Ha null, automatikusan kiszámoljuk.
    'base_url' => null,
];

$localConfig = __DIR__ . '/db_config.local.php';
if (is_file($localConfig)) {
    $config = array_merge($config, require $localConfig);
}

return $config;
