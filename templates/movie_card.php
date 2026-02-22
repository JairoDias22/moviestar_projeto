<?php
// Se não houver imagem definida para o filme, usa imagem padrão
if(empty($movie->image)) {
  $movie->image = "movie_cover.jpg";
}
?>
<div class="card movie-card">
<img class="card-img-top" 
     src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" 
     alt="<?= $movie->title ?>">

  <div class="card-body">

    <!-- Avaliação do filme (estrela) -->
    <p class="card-rating">
      <i class="fas fa-star"></i>
      <span class="rating">—</span>
    </p>

    <!-- Título do filme com link para página do filme -->
    <h5 class="card-title">
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>">
        <?= $movie->title ?>
      </a>
    </h5>

    <!-- Botão para avaliar o filme -->
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn rate-btn">
      Avaliar
    </a>

    <!-- Botão para ver detalhes do filme -->
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn card-btn">
      Conhecer
    </a>

  </div>
</div>