<?php
// Közös fejléc: <head>, navigáció és az egyszeri üzenetek. Használat előtt állítsd be a $pageTitle változót.
$pageTitle = $pageTitle ?? 'NyusziCare';
?>
<!doctype html>
<html lang="hu">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print e($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php print url('assets/css/styles.css') ?>">
  </head>
  <body class="d-flex flex-column min-vh-100">

<?php include __DIR__ . '/navbar.php'; ?>

<?php $flashes = takeFlashes(); ?>
<?php if ($flashes) { ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <?php foreach ($flashes as $type => $messages) { ?>
          <?php foreach ($messages as $message) { ?>
            <div class="alert alert-<?php print e($type) ?> text-center"><?php print e($message) ?></div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>

<main class="flex-grow-1">
