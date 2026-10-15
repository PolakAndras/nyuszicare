<?php
require __DIR__ . '/../includes/init.php';

// Ha már be van jelentkezve, nincs itt dolga
if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/admin.php' : 'index.php');
}

$pageTitle = 'NyusziCare Belépés';
include __DIR__ . '/../includes/header.php';
?>

<div class="container bg-light rounded-3 mt-5 p-4 form-container">
  <h1 class="text-center">Belépés</h1>
  <form action="login_process.php" method="post">
    <?php print csrfField() ?>

    <div class="mb-3">
      <label for="email1" class="form-label">Email</label>
      <input type="email" class="form-control" placeholder="Add meg az email címed" id="email1" name="email" value="<?php print old('email') ?>" required autocomplete="email">
    </div>

    <div class="mb-3">
      <label for="password1" class="form-label">Jelszó</label>
      <input type="password" class="form-control" placeholder="Add meg a jelszavad" id="password1" name="password" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-primary my-3">Belépés</button>
    <p class="mb-0">Még nincs fiókod? <a href="<?php print url('registration/registration.php') ?>">Regisztrálj!</a></p>
  </form>
</div>

<?php
forgetOld();
include __DIR__ . '/../includes/footer.php';
?>
