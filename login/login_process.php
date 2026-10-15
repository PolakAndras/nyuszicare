<?php
require __DIR__ . '/../includes/init.php';

requirePost();
verifyCsrf();

$email = trim(post('email'));
$password = post('password');

// A beírt emailt visszatöltjük hiba esetén, a jelszót nem
rememberOld(['email' => $email]);

if ($email === '' || $password === '') {
    flash('danger', 'Az email cím és a jelszó megadása kötelező!');
    redirect('login/login.php');
}

// Először a felhasználók között keresünk
$user = dbFetchOne($connection, 'SELECT id, name, email, password FROM users WHERE email = ?', [$email]);

if ($user !== null && password_verify($password, $user['password'])) {
    // Új session azonosító belépéskor (session fixation ellen)
    session_regenerate_id(true);
    forgetOld();

    // A jelszó hash-t nem tároljuk a sessionben
    $_SESSION['loggedInUser'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
    ];
    redirect('index.php');
}

// Ha nem felhasználó, megnézzük az adminokat
$admin = dbFetchOne($connection, 'SELECT id, email, password FROM adminok WHERE email = ?', [$email]);

if ($admin !== null && password_verify($password, $admin['password'])) {
    session_regenerate_id(true);
    forgetOld();

    $_SESSION['admin'] = [
        'id' => (int)$admin['id'],
        'email' => $admin['email'],
    ];
    redirect('admin/admin.php');
}

// Közös hibaüzenet: nem áruljuk el, hogy az email vagy a jelszó volt-e hibás
flash('danger', 'Hibás email cím vagy jelszó!');
redirect('login/login.php');
