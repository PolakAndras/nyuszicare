<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid justify-content-center my-3">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link fw-semibold text-dark" href="<?php print url('index.php') ?>">NyusziCare</a>
      </li>

      <?php if (!isLoggedIn()) { ?>
        <li class="nav-item">
          <a class="nav-link fw-semibold text-dark" href="<?php print url('registration/registration.php') ?>">Regisztráció</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold text-dark" href="<?php print url('login/login.php') ?>">Belépés</a>
        </li>
      <?php } ?>

      <?php if (currentUser() !== null) { ?>
        <li class="nav-item">
          <a class="nav-link fw-semibold text-dark" href="<?php print url('profile/profile.php') ?>">Adatmódosítás</a>
        </li>
      <?php } ?>

      <?php if (isAdmin()) { ?>
        <li class="nav-item">
          <a class="nav-link fw-semibold text-dark" href="<?php print url('admin/admin.php') ?>">Admin felület</a>
        </li>
      <?php } ?>

      <?php if (isLoggedIn()) { ?>
        <li class="nav-item">
          <!-- A kijelentkezés POST kéréssel történik, így más oldalról nem lehet kiléptetni a felhasználót -->
          <form action="<?php print url('logout.php') ?>" method="post" class="d-inline">
            <?php print csrfField() ?>
            <button type="submit" class="nav-link fw-semibold text-dark">Kijelentkezés</button>
          </form>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>
