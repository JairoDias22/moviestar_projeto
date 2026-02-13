<div class="card movie-card text-light" style="width: 18rem; background-color: black;">

  <img src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" class="card-img-top" alt="<?= $movie->title ?>">

  <div class="card-body">

    <p class="card-rating mb-1">
      <i class="fas fa-star text-warning"></i>
      <span class="rating">5.0<?= $movie->rating ?></span>
    </p>
    <h5 class="card-title">
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="text-decoration-none text-light">
        <?= $movie->title ?>
      </a>
    </h5>

     <div class="d-grid gap-2 mt-3">
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn btn-outline-primary w-100">
        Avaliar
      </a>
    </div>

    <div class="d-grid gap-2 mt-3">
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn btn-outline-warning w-100">
        Conhecer
      </a>
    </div>

    
  </div>

  

