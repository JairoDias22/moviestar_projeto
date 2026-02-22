<?php
// Se não houver imagem definida para o filme, usa imagem padrão
if(empty($movie->image)) {
  $movie->image = "movie_cover.jpg";
}
?>
<div class="card movie-card">
<<<<<<< HEAD

  <!-- Imagem do filme como background -->
  <div class="card-img-top" 
       style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')">
  </div>
=======
<img class="card-img-top" 
     src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" 
     alt="<?= $movie->title ?>">
>>>>>>> 1b5e252fa405fc9405532c030444a916814c2cb7

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