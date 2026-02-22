<?php
  require_once("templates/header.php");

  // ===== IMPORTA AS CLASSES NECESSÁRIAS =====
  require_once("models/Movie.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDao.php"); 

  // ===== OBTÉM PARÂMETROS DA URL =====
  // Obtém o ID do filme via GET e valida como inteiro
  $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

  // Obtém a ação (ex: ?action=review) ou define "view" como padrão
  $action = filter_input(INPUT_GET, "action") ?? "view";

  // ===== VALIDAÇÃO DO ID =====
  // Se o ID for inválido ou não existir, exibe mensagem e redireciona
  if(empty($id)) {
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
    exit;
  }

  // ===== INSTÂNCIAS DOS DAOs =====
  // Responsável por buscar os dados do filme
  $movieDao  = new MovieDAO($conn, $BASE_URL);

  // Responsável por buscar avaliações e calcular médias
  $reviewDao = new ReviewDAO($conn, $BASE_URL);

  // ===== BUSCA DO FILME =====
  // Busca o filme pelo ID informado na URL
  $movie = $movieDao->findById($id);

  // Se o filme não existir no banco, exibe mensagem e redireciona
  if(!$movie){
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
    exit;
  }

  // ===== TRATAMENTO DE DADOS DO FILME =====
  // Define imagem padrão caso o filme não possua imagem cadastrada
  if(empty($movie->image)){
    $movie->image = "movie_cover.jpg";
  }

  // ===== VARIÁVEIS DE CONTROLE =====
  // Indica se o usuário logado é o dono do filme
  $userOwnsMovie = false;
  $alreadyReviewed = false;

  // Indica se o usuário já avaliou este filme
  $alreadyReviewed = false;

  // ===== VERIFICAÇÕES DE USUÁRIO =====
  // Verifica se o usuário está logado e se é dono do filme
  if(!empty($userData)){
    $userOwnsMovie = ($userData->id === $movie->users_id);

    // Verifica se o usuário já avaliou esse filme
    $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->id);
  }

  // ===== BUSCA DAS AVALIAÇÕES =====
  // Busca todas as avaliações do filme
  $movieReviews = $reviewDao->getMoviesReview($id);

  // ===== CÁLCULO DA MÉDIA =====
  // Obtém a média das avaliações do filme
  $rating = $reviewDao->getRatings($id);
?>

<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="offset-md-1 col-md-6 movie-container">

      <!-- Título do filme -->
      <h1 class="page-title"><?= $movie->title ?></h1>

      <!-- Informações do filme -->
      <p class="movie-details">
        <span>Duração: <?= $movie->length ?></span>
        <span class="pipe">|</span>
        <span><?= $movie->category ?></span>
        <span class="pipe">|</span>

        <!-- Exibe média ou mensagem padrão -->
        <span>
          <i class="fas fa-star"></i>
          <?php if($rating > 0): ?>
            <?= number_format($rating, 1) ?>
          <?php else: ?>
            Não avaliado
          <?php endif; ?>
        </span>
      </p>

      <!-- Trailer do filme -->
      <iframe 
        src="<?= $movie->trailer ?>" 
        width="560" 
        height="315" 
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>

      <!-- Descrição -->
      <p class="description"><?= $movie->description ?></p>

    </div>

    <!-- Imagem do filme -->
    <div class="movie-image-container">
      <img src="<?= $BASE_URL ?>img/movies/<?= $movie->image ?>" alt="<?= $movie->title ?>">
    </div>

  </div>

  <h3 id="reviews-title">Avaliações:</h3>

  <div id="review-form-container" class="review-card">

    <!-- Mostra formulário apenas se usuário estiver logado e não tiver avaliado -->
    <?php if($action === "review" && !empty($userData) && !$userOwnsMovie && !$alreadyReviewed ): ?>
      <div class="col-md-12">

        <h4 class="Submit_review">Envie sua avaliação:</h4>
        <p class="page-description1">
          Preencha o formulário com a nota e comentário sobre o filme
        </p>

        <form action="<?= $BASE_URL ?>review_process.php" id="review-form" method="POST">
          <input type="hidden" name="type" value="create">
          <input type="hidden" name="movies_id" value="<?= $movie->id ?>">

          <!-- Seleção de nota -->
          <div class="form-group">
            <label for="rating" class="Film_note">Nota do filme:</label>
            <select name="rating" id="rating" class="notice">
              <option value="">Selecione</option>
              <?php for($i = 10; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <!-- Comentário -->
          <div class="form-group">
            <label class="comment_movie" for="review">Seu comentário:</label>
            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
          </div>

          <input type="submit" class="review-btn" value="Enviar comentário">
        </form>
      </div>
    <?php endif; ?>

    <!-- Lista de avaliações -->
    <?php foreach($movieReviews as $review): ?>
        <?php
          $reviewUser = $userDao->findById($review->users_id);
        ?>
      <?php require("templates/user_review.php"); ?>
    <?php endforeach; ?>

    <!-- Caso não haja avaliações -->
    <?php if(count($movieReviews) == 0): ?>
      <p class="empty-list">Não há comentários para este filme ainda...</p>
    <?php endif; ?>

  </div>
</div>

<?php
  require_once("templates/footer.php");
?>
