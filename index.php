<?php
require __DIR__ . '/includes/init.php';

// Kategória kiválasztása: a Nyúltartás mindenkinek elérhető, a többi csak bejelentkezett felhasználónak
$kategoria = NYILVANOS_KATEGORIA;
$valasztott = $_GET['kategoria'] ?? null;

if (is_string($valasztott) && array_key_exists($valasztott, KATEGORIAK)) {
    if ($valasztott === NYILVANOS_KATEGORIA || isLoggedIn()) {
        $kategoria = $valasztott;
    } else {
        flash('warning', 'Ennek a kategóriának a megtekintéséhez jelentkezz be.');
        redirect('login/login.php');
    }
}

// Oldalszámozás: megszámoljuk a kategória cikkeit, és ebből kiszámoljuk az oldalak számát
$limit = 12;
$osszcikk = (int)dbFetchOne($connection, 'SELECT COUNT(*) AS total FROM cikkek WHERE kategoria = ?', [$kategoria])['total'];
$osszOldal = max(1, (int)ceil($osszcikk / $limit));

// Csak 1 és az utolsó oldal közötti szám fogadható el
$oldal = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
$oldal = min($oldal, $osszOldal);
$offset = ($oldal - 1) * $limit;

$cikkek = dbFetchAll(
    $connection,
    'SELECT id, cim, szerzo, bevezeto FROM cikkek WHERE kategoria = ? ORDER BY id LIMIT ? OFFSET ?',
    [$kategoria, $limit, $offset]
);

$pageTitle = 'NyusziCare Cikkgyűjtemény';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/carousel.php';
?>

<!-- Üdvözlés -->
<div class="container mt-3 text-center">
  <?php if (currentUser() !== null) { ?>
    <h3>Üdvözöllek, kedves <?php print e(currentUser()['name']) ?>!</h3>
  <?php } elseif (isAdmin()) { ?>
    <h3>Üdvözöllek, admin!</h3>
  <?php } else { ?>
    <h3>Üdvözöllek! Kérlek jelentkezz be, vagy regisztrálj az összes cikk megtekintéséhez!</h3>
  <?php } ?>
</div>

<!-- Kategória gombok -->
<div class="container mt-3">
  <form method="get" action="<?php print url('index.php') ?>">
    <div class="row g-2">
      <div class="col-md-4">
        <?php if (isLoggedIn()) { ?>
          <button name="kategoria" value="Etetes" class="btn btn-success w-100 <?php if ($kategoria === 'Etetes') print 'active border-3 border-dark'; ?>">Etetés</button>
        <?php } else { ?>
          <button type="button" class="btn btn-success w-100" disabled>Etetés (bejelentkezés szükséges)</button>
        <?php } ?>
      </div>

      <div class="col-md-4">
        <button name="kategoria" value="Nyultartas" class="btn btn-light w-100 border-bottom <?php if ($kategoria === 'Nyultartas') print 'active border-3 border-dark'; ?>">Nyúltartás</button>
      </div>

      <div class="col-md-4">
        <?php if (isLoggedIn()) { ?>
          <button name="kategoria" value="Betegsegek" class="btn btn-danger w-100 <?php if ($kategoria === 'Betegsegek') print 'active border-3 border-dark'; ?>">Betegségek</button>
        <?php } else { ?>
          <button type="button" class="btn btn-danger w-100" disabled>Betegségek (bejelentkezés szükséges)</button>
        <?php } ?>
      </div>
    </div>
  </form>
</div>

<!-- Cikkek kártyázva -->
<div class="container mt-4">
  <h2 class="text-center mb-3"><?php print e(KATEGORIAK[$kategoria]) ?></h2>
  <div class="row g-3">
    <?php if (!$cikkek) { ?>
      <p class="text-center text-muted">Ebben a kategóriában még nincs cikk.</p>
    <?php } ?>

    <?php foreach ($cikkek as $sor) { ?>
      <div class="col-sm-6 col-lg-3">
        <div class="card shadow-lg h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php print e($sor['cim']) ?></h5>
            <h6 class="card-subtitle mb-2 text-muted">Szerző: <?php print e($sor['szerzo']) ?></h6>
            <p class="card-text"><?php print e($sor['bevezeto']) ?></p>
            <a href="<?php print url('cikk.php?id=' . (int)$sor['id']) ?>" class="btn btn-outline-primary mt-auto stretched-link">Teljes cikk</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Lapozás -->
<?php if ($osszOldal > 1) { ?>
  <nav class="d-flex justify-content-center mt-4" aria-label="Oldalak">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $osszOldal; $i++) { ?>
        <li class="page-item <?php if ($i === $oldal) print 'active'; ?>">
          <a class="page-link" href="<?php print url('index.php?' . http_build_query(['kategoria' => $kategoria, 'page' => $i])) ?>"><?php print $i ?></a>
        </li>
      <?php } ?>
    </ul>
  </nav>
<?php } ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
