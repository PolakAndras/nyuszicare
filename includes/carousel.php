<div class="container-fluid my-2">
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php for ($kep = 1; $kep <= 4; $kep++) { ?>
        <div class="carousel-item <?php if ($kep === 1) print 'active'; ?>">
          <img src="<?php print url("assets/img/carousel$kep.jpg") ?>" class="d-block w-100" alt="Nyuszi kép <?php print $kep ?>">
        </div>
      <?php } ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Előző</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Következő</span>
    </button>
  </div>
</div>
