<?php
require __DIR__ . '/includes/init.php';

$cikkId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

$cikk = $cikkId
    ? dbFetchOne($connection, 'SELECT cim, szerzo, tartalom, kategoria FROM cikkek WHERE id = ?', [$cikkId])
    : null;

// Nem létező cikk
if ($cikk === null) {
    flash('warning', 'A keresett cikk nem található.');
    redirect('index.php');
}

// A zárt kategóriák cikkei csak bejelentkezve olvashatók, akkor is, ha valaki az URL-be írja az azonosítót
if ($cikk['kategoria'] !== NYILVANOS_KATEGORIA && !isLoggedIn()) {
    flash('warning', 'A cikk elolvasásához jelentkezz be.');
    redirect('login/login.php');
}

$pageTitle = $cikk['cim'] . ' – NyusziCare';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/carousel.php';
?>

<!-- A cikk -->
<article class="container my-4" style="max-width: 900px;">
  <p class="text-center text-muted mb-1"><?php print e(KATEGORIAK[$cikk['kategoria']]) ?></p>
  <h1 class="text-center"><?php print e($cikk['cim']) ?></h1>
  <p class="fs-5 text-center">Szerző: <?php print e($cikk['szerzo']) ?></p>
  <p class="fs-4"><?php print nl2br(e($cikk['tartalom'])) ?></p>

  <div class="text-center">
    <a class="btn btn-warning my-5 px-5" href="<?php print url('index.php?kategoria=' . urlencode($cikk['kategoria'])) ?>">Vissza</a>
  </div>
</article>

<?php include __DIR__ . '/includes/footer.php'; ?>
