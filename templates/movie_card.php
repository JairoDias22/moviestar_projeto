  <?php
    
require_once("dao/ReviewDAO.php");

/* cria o DAO passando conexão e BASE_URL (igual no movie.php) */
$reviewDao = new ReviewDAO($conn, $BASE_URL);

/* calcula a média do filme atual */
$rating = $reviewDao->getRatings($movie->id);


/* imagem padrão */
    if(empty($movie->image)) {
      $movie->image = "movie_cover.jpg";
    }
  ?>

  <!-- Card principal que representa um filme -->
  <div class="card movie-card">

    <!-- Imagem de capa do filme -->
    <img class="card-img-top" 
        src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" 
        alt="<?= $movie->title ?>">

    <div class="card-body">

      <!-- Área de avaliação (estrela + nota do filme) -->
      <p class="card-rating">
        <!-- Ícone de estrela (Font Awesome) -->
        <i class="fas fa-star"></i>

        <!-- Nota do filme (no momento está fixa como traço) -->
        <span class="rating">—</span>
      </p>

      <!-- Título do filme -->
      <h5 class="card-title">
        <!-- Link para a página do filme passando o ID pela URL -->
        <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>">
          <?= $movie->title ?>
        </a>
      </h5>

      <!-- Botão que leva para a página do filme já na seção de avaliações -->
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>&action=review"class="btn btn-outline-primary w-100">
        Avaliar
      </a>

      <!-- Botão que leva para a página do filme para ver mais detalhes -->
      <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>&action=view" class="btn btn-outline-warning w-100">
        Conhecer
      </a>

    </div>
  </div>