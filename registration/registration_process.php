<?php
require __DIR__ . '/../includes/init.php';

requirePost();
verifyCsrf();

$name = cleanData(post('name'));
$email = trim(post('email'));
$password = post('password');
$passwordConfirm = post('password_confirm');
$newsletter = post('newsletter') === '1' ? 1 : 0;

$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    $errors[] = 'Az email cím nem valid!';
} elseif (dbFetchOne($connection, 'SELECT id FROM users WHERE email = ?', [$email]) !== null) {
    $errors[] = 'Az email cím már használatban van!';
}

if (mb_strlen($name) < 3 || mb_strlen($name) > 20) {
    $errors[] = 'A név nem megfelelő hosszúságú! Minimum 3, maximum 20 karakter lehetséges!';
}

if (mb_strlen($password) < 6 || mb_strlen($password) > 60) {
    $errors[] = 'A jelszó nem megfelelő hosszúságú! Minimum 6, maximum 60 karakter lehetséges!';
}

// Szigorú összehasonlítás, hogy pl. a "100000" és "1e5" ne számítson egyezőnek
if ($password !== $passwordConfirm) {
    $errors[] = 'A jelszavak nem egyeznek!';
}

if ($errors) {
    // A beírt adatok visszatöltése, hogy ne kelljen elölről kezdeni (jelszó nélkül)
    rememberOld(['name' => $name, 'email' => $email, 'newsletter' => (string)$newsletter]);
    foreach ($errors as $error) {
        flash('danger', $error);
    }
    redirect('registration/registration.php');
}

dbExecute(
    $connection,
    'INSERT INTO users (name, email, password, newsletter) VALUES (?, ?, ?, ?)',
    [$name, $email, password_hash($password, PASSWORD_DEFAULT), $newsletter]
);

flash('success', 'A regisztráció sikeres volt! Most már bejelentkezhetsz.');
if ($newsletter === 1) {
    flash('success', 'Köszönjük a hírlevélre való feliratkozást!');
}

rememberOld(['email' => $email]);
redirect('login/login.php');
