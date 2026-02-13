<?php
require_once("templates/header.php");
require_once("dao/MovieDAO.php");

$movieDao = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDao->getLatestMovies() ?? [];
$actionMovies = $movieDao->getMoviesByCategory("Ação") ?? [];
$comedyMovies = $movieDao->getMoviesByCategory("Comédia") ?? [];
?>

<div id="main-container" class="container-fluid">
  
  <!-- Filmes novos -->
   <div class="new-movie text-justify mb-4">
  <h2 class="section-title d-inline-block border-start pb-1 border-warning">Filmes novos</h2>
  <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
  </div>

  <div class="movies-container">
    
    <?php if (count($latestMovies) > 0): ?>
      <?php foreach ($latestMovies as $movie): ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty-list">Ainda não há filmes cadastrados!</p>
    <?php endif; ?>
  </div>

  <!-- Ação -->
  <div class="new-movie text-justify mb-4">
  <h2 class="section-title">Ação</h2>
  <p class="section-description">Veja os melhores filmes de ação</p>
  </div>
  <div class="movies-container">
    <?php if (count($actionMovies) > 0): ?>
      <?php foreach ($actionMovies as $movie): ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty-list">Ainda não há filmes de ação cadastrados!</p>
    <?php endif; ?>
  </div>

  <!-- Comédia -->
  <div class="new-movie text-justify mb-4">
  <h2 class="section-title">Comédia</h2>
  <p class="section-description">Veja os melhores filmes de comédia</p>
  </div>
  <div class="movies-container">
    <?php if (count($comedyMovies) > 0): ?>
      <?php foreach ($comedyMovies as $movie): ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty-list">Ainda não há filmes de comédia cadastrados!</p>
    <?php endif; ?>
  </div>

</div>

<?php require_once("templates/footer.php"); ?>
