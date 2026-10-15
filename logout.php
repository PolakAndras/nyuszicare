<?php
require __DIR__ . '/includes/init.php';

requirePost();
verifyCsrf();

// Felhasználó és admin kijelentkeztetése, új session azonosítóval
unset($_SESSION['loggedInUser'], $_SESSION['admin']);
session_regenerate_id(true);

flash('success', 'Sikeresen kijelentkeztél.');
redirect('index.php');
