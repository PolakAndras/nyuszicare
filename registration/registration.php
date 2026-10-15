<?php
require __DIR__ . '/../includes/init.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$pageTitle = 'NyusziCare Regisztráció';
include __DIR__ . '/../includes/header.php';
?>

<div class="container bg-light rounded-3 mt-5 p-4 form-container">
  <h1 class="text-center">Regisztráció</h1>
  <form action="registration_process.php" method="post">
    <?php print csrfField() ?>

    <div class="mb-3">
      <label for="email1" class="form-label">Email cím</label>
      <input type="email" class="form-control" placeholder="Add meg az email címed" id="email1" name="email" value="<?php print old('email') ?>" required maxlength="100" autocomplete="email">
    </div>

    <div class="mb-3">
      <label for="name1" class="form-label">Név</label>
      <input type="text" class="form-control" placeholder="Add meg a neved" id="name1" name="name" value="<?php print old('name') ?>" required minlength="3" maxlength="20" autocomplete="name">
    </div>

    <div class="mb-3">
      <label for="password1" class="form-label">Jelszó</label>
      <input type="password" class="form-control" placeholder="Minimum 6 karakter" id="password1" name="password" required minlength="6" maxlength="60" autocomplete="new-password">
    </div>

    <div class="mb-3">
      <label for="password2" class="form-label">Jelszó megerősítés</label>
      <input type="password" class="form-control" placeholder="Add meg a jelszavad ismét" id="password2" name="password_confirm" required autocomplete="new-password">
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="newsletter1" name="newsletter" value="1" <?php if (old('newsletter') === '1') print 'checked'; ?>>
      <label class="form-check-label" for="newsletter1">Hírlevélre feliratkozás</label>
    </div>

    <button type="submit" class="btn btn-primary my-3">Regisztráció</button>
    <p class="mb-0">Van már fiókod? <a href="<?php print url('login/login.php') ?>">Jelentkezz be!</a></p>
  </form>
</div>

<?php
forgetOld();
include __DIR__ . '/../includes/footer.php';
?>
