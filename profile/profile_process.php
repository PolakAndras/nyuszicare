<?php
require __DIR__ . '/../includes/init.php';

requireLogin();
requirePost();
verifyCsrf();

$userId = currentUser()['id'];
$user = dbFetchOne($connection, 'SELECT name, email, password FROM users WHERE id = ?', [$userId]);
logoutMissingUser($user);

$name = cleanData(post('name'));
$email = trim(post('email'));
$password = post('password');
$passwordConfirm = post('password_confirm');

$errors = [];

// Bármilyen módosításhoz a jelenlegi jelszó szükséges
if (!password_verify(post('current_password'), $user['password'])) {
    $errors[] = 'A jelenlegi jelszó hibás!';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    $errors[] = 'Az email cím nem valid!';
} elseif (dbFetchOne($connection, 'SELECT id FROM users WHERE email = ? AND id != ?', [$email, $userId]) !== null) {
    // Más felhasználó már használja ezt az emailt (a saját emailje nem számít)
    $errors[] = 'Az email cím már használatban van!';
}

if (mb_strlen($name) < 3 || mb_strlen($name) > 20) {
    $errors[] = 'A név nem megfelelő hosszúságú! Minimum 3, maximum 20 karakter lehetséges!';
}

// Az új jelszót csak akkor ellenőrizzük, ha meg akarja változtatni
$changePassword = $password !== '' || $passwordConfirm !== '';
if ($changePassword) {
    if (mb_strlen($password) < 6 || mb_strlen($password) > 60) {
        $errors[] = 'Az új jelszó nem megfelelő hosszúságú! Minimum 6, maximum 60 karakter lehetséges!';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Az új jelszavak nem egyeznek!';
    }
}

if ($errors) {
    rememberOld(['name' => $name, 'email' => $email]);
    foreach ($errors as $error) {
        flash('danger', $error);
    }
    redirect('profile/profile.php');
}

if ($name === $user['name'] && $email === $user['email'] && !$changePassword) {
    flash('info', 'Nem módosítottál egyetlen adatot sem.');
    redirect('profile/profile.php');
}

dbExecute($connection, 'UPDATE users SET name = ?, email = ? WHERE id = ?', [$name, $email, $userId]);

if ($changePassword) {
    dbExecute($connection, 'UPDATE users SET password = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $userId]);
}

// A sessionben tárolt adatok frissítése, mert ezek jelennek meg az oldalon
$_SESSION['loggedInUser']['name'] = $name;
$_SESSION['loggedInUser']['email'] = $email;

flash('success', 'A módosítás sikeres volt!');
redirect('index.php');
