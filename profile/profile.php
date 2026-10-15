<?php
require __DIR__ . '/../includes/init.php';

requireLogin();

// A jelenlegi adatokkal töltjük ki az űrlapot (hiba után a beírtakkal)
$user = dbFetchOne($connection, 'SELECT name, email FROM users WHERE id = ?', [currentUser()['id']]);
logoutMissingUser($user);

$pageTitle = 'NyusziCare Adatmódosítás';
include __DIR__ . '/../includes/header.php';
?>

<div class="container bg-light rounded-3 mt-5 p-4 form-container">
  <h1 class="text-center">Adatmódosítás</h1>
  <form action="profile_process.php" method="post">
    <?php print csrfField() ?>

    <div class="mb-3">
      <label for="email1" class="form-label">Email cím</label>
      <input type="email" class="form-control" id="email1" name="email" value="<?php print old('email', $user['email']) ?>" required maxlength="100" autocomplete="email">
    </div>

    <div class="mb-3">
      <label for="name1" class="form-label">Név</label>
      <input type="text" class="form-control" id="name1" name="name" value="<?php print old('name', $user['name']) ?>" required minlength="3" maxlength="20" autocomplete="name">
    </div>

    <div class="mb-3">
      <label for="password1" class="form-label">Új jelszó</label>
      <input type="password" class="form-control" placeholder="Hagyd üresen, ha nem változtatod" id="password1" name="password" minlength="6" maxlength="60" autocomplete="new-password">
    </div>

    <div class="mb-3">
      <label for="password2" class="form-label">Új jelszó megerősítés</label>
      <input type="password" class="form-control" placeholder="Add meg az új jelszavad ismét" id="password2" name="password_confirm" autocomplete="new-password">
    </div>

    <hr>

    <div class="mb-3">
      <label for="current_password" class="form-label">Jelenlegi jelszó</label>
      <input type="password" class="form-control" placeholder="A módosítás jóváhagyásához" id="current_password" name="current_password" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-primary my-3">Módosítás</button>
  </form>
</div>

<?php
forgetOld();
include __DIR__ . '/../includes/footer.php';
?>
