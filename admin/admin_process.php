<?php
require __DIR__ . '/../includes/init.php';

requireAdmin();
requirePost();
verifyCsrf();

if (DEMO_MODE) {
    flash('warning', 'Demó módban a cikkek nem módosíthatók.');
    redirect('admin/admin.php');
}

// Cikk adatainak beolvasása és ellenőrzése (új cikknél és szerkesztésnél is ugyanaz)
function cikkAdatok(): array
{
    return [
        'cim' => cleanData(post('cim')),
        'szerzo' => cleanData(post('szerzo')),
        'bevezeto' => trim(post('bevezeto')),
        'tartalom' => trim(post('tartalom')),
        'kategoria' => post('kategoria'),
    ];
}

function cikkHibak(array $cikk): array
{
    $errors = [];
    $hosszak = [
        'cim' => ['Cím', 3, 255],
        'szerzo' => ['Szerző', 3, 40],
        'bevezeto' => ['Bevezető', 3, 400],
        'tartalom' => ['Tartalom', 3, 6400],
    ];

    foreach ($hosszak as $mezo => [$nev, $min, $max]) {
        $hossz = mb_strlen($cikk[$mezo]);
        if ($hossz < $min || $hossz > $max) {
            $errors[] = "$nev: minimum $min, maximum $max karakter lehetséges!";
        }
    }

    if (!array_key_exists($cikk['kategoria'], KATEGORIAK)) {
        $errors[] = 'Érvénytelen kategória!';
    }

    return $errors;
}

$action = post('action');
$id = filter_var(post('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

// Törlés
if ($action === 'torles' && $id) {
    dbExecute($connection, 'DELETE FROM cikkek WHERE id = ?', [$id]);
    flash('success', 'Sikeres törlés!');
    redirect('admin/admin.php');
}

// Új cikk vagy szerkesztés
if ($action === 'uj' || ($action === 'szerkesztes' && $id)) {
    $cikk = cikkAdatok();
    $errors = cikkHibak($cikk);

    if ($errors) {
        foreach ($errors as $error) {
            flash('danger', $error);
        }
        // Új cikknél visszatöltjük a beírt adatokat
        if ($action === 'uj') {
            rememberOld($cikk);
        }
        redirect('admin/admin.php');
    }

    if ($action === 'uj') {
        dbExecute(
            $connection,
            'INSERT INTO cikkek (cim, szerzo, bevezeto, tartalom, kategoria) VALUES (?, ?, ?, ?, ?)',
            [$cikk['cim'], $cikk['szerzo'], $cikk['bevezeto'], $cikk['tartalom'], $cikk['kategoria']]
        );
        flash('success', 'Sikeres új feltöltés!');
    } else {
        dbExecute(
            $connection,
            'UPDATE cikkek SET cim = ?, szerzo = ?, bevezeto = ?, tartalom = ?, kategoria = ? WHERE id = ?',
            [$cikk['cim'], $cikk['szerzo'], $cikk['bevezeto'], $cikk['tartalom'], $cikk['kategoria'], $id]
        );
        flash('success', 'Sikeres módosítás!');
    }

    redirect('admin/admin.php');
}

flash('danger', 'Ismeretlen művelet.');
redirect('admin/admin.php');
