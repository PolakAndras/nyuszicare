<?php
require __DIR__ . '/../includes/init.php';

requireAdmin();

$cikkek = dbFetchAll($connection, 'SELECT id, cim, szerzo, bevezeto, tartalom, kategoria FROM cikkek ORDER BY kategoria, id');

$pageTitle = 'NyusziCare Admin';
include __DIR__ . '/../includes/header.php';

// Kategória választó, a megadott kategória van kiválasztva
function kategoriaSelect(string $kivalasztott): string
{
    $html = '<select class="form-select" name="kategoria" required>';
    foreach (KATEGORIAK as $ertek => $nev) {
        $selected = $ertek === $kivalasztott ? ' selected' : '';
        $html .= '<option value="' . e($ertek) . '"' . $selected . '>' . e($nev) . '</option>';
    }
    return $html . '</select>';
}
?>

<div class="container">
  <h1 class="text-center mt-3">Admin felület</h1>

  <?php if (DEMO_MODE) { ?>
    <div class="alert alert-info text-center">
      Demó módban az admin felület csak megtekinthető, a módosítások nem kerülnek mentésre.
    </div>
  <?php } ?>

  <!-- Új cikk hozzáadása -->
  <h3 class="text-center my-3">Új cikk hozzáadása</h3>
  <div class="bg-body-secondary rounded-3 p-3 mb-5">
    <form action="admin_process.php" method="post">
      <?php print csrfField() ?>
      <input type="hidden" name="action" value="uj">
      <div class="d-grid gap-3">
        <input class="form-control" type="text" name="cim" value="<?php print old('cim') ?>" placeholder="Cím" required>
        <input class="form-control" type="text" name="szerzo" value="<?php print old('szerzo') ?>" placeholder="Szerző" required>
        <textarea class="form-control" name="bevezeto" placeholder="Bevezető" rows="3" required><?php print old('bevezeto') ?></textarea>
        <textarea class="form-control" name="tartalom" placeholder="Tartalom" rows="6" required><?php print old('tartalom') ?></textarea>
        <?php print kategoriaSelect($_SESSION['old']['kategoria'] ?? NYILVANOS_KATEGORIA) ?>
        <div>
          <button type="submit" class="btn btn-primary">Beküld</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Cikkek listája -->
  <h3 class="text-center my-3">Cikkek (<?php print count($cikkek) ?> db)</h3>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Cím</th>
          <th scope="col">Szerző</th>
          <th scope="col">Bevezető</th>
          <th scope="col">Kategória</th>
          <th scope="col" class="text-end">Műveletek</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cikkek as $row) { $cikkId = (int)$row['id']; ?>
          <tr>
            <td><?php print $cikkId ?></td>
            <td><?php print e($row['cim']) ?></td>
            <td><?php print e($row['szerzo']) ?></td>
            <td class="small"><?php print e($row['bevezeto']) ?></td>
            <td><?php print e(KATEGORIAK[$row['kategoria']] ?? $row['kategoria']) ?></td>
            <td class="text-end text-nowrap">
              <button type="button" class="btn btn-warning btn-sm my-1" data-bs-toggle="collapse" data-bs-target="#szerkeszt_<?php print $cikkId ?>">Szerkesztés</button>
              <form action="admin_process.php" method="post" class="d-inline" onsubmit="return confirm('Biztosan törlöd ezt a cikket?');">
                <?php print csrfField() ?>
                <input type="hidden" name="action" value="torles">
                <input type="hidden" name="id" value="<?php print $cikkId ?>">
                <button type="submit" class="btn btn-danger btn-sm my-1">Törlés</button>
              </form>
            </td>
          </tr>

          <!-- Szerkesztés lenyíló form -->
          <tr class="collapse" id="szerkeszt_<?php print $cikkId ?>">
            <td colspan="6">
              <form action="admin_process.php" method="post" class="card card-body d-grid gap-2">
                <?php print csrfField() ?>
                <input type="hidden" name="action" value="szerkesztes">
                <input type="hidden" name="id" value="<?php print $cikkId ?>">
                <input class="form-control" type="text" name="cim" placeholder="Cím" value="<?php print e($row['cim']) ?>" required>
                <input class="form-control" type="text" name="szerzo" placeholder="Szerző" value="<?php print e($row['szerzo']) ?>" required>
                <textarea class="form-control" name="bevezeto" placeholder="Bevezető" rows="3" required><?php print e($row['bevezeto']) ?></textarea>
                <textarea class="form-control" name="tartalom" placeholder="Tartalom" rows="6" required><?php print e($row['tartalom']) ?></textarea>
                <?php print kategoriaSelect($row['kategoria']) ?>
                <div>
                  <button type="submit" class="btn btn-primary mt-2">Mentés</button>
                </div>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<?php
forgetOld();
include __DIR__ . '/../includes/footer.php';
?>
