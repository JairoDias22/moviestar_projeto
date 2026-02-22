<?php
  require_once("templates/header.php");

  // Models e DAOs necessários
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("auth.php");

  $user = new User();
$userDao = new UserDAO($conn, $BASE_URL, $message);

  // Recebe o ID do usuário pela URL
  $id = filter_input(INPUT_GET, "id");

  // Se não vier ID na URL
  if(empty($id)) {

    if(!empty($userData)) {
      $id = $userData->id;
    } else {
      $message->setMessage("Usuário não encontrado!", "error", "index.php");
    }

} else {

    // Busca usuário pelo ID
    $userData = $userDao->findById($id);

    if(!$userData) {
      $message->setMessage("Usuário não encontrado!", "error", "index.php");
    }

  }
  // Verifica se o usuário existe antes de usar
  if(!$userData) {
    $message->setMessage("Usuário não encontrado!", "error", "index.php");
    exit;
  }

  // Nome completo
  $fullName = $user->getFullName($userData);

  // Imagem padrão caso não tenha foto
  if(empty($userData->image)) {
    $userData->image = "user.png";
  }

  // Filmes adicionados pelo usuário
  $userMovies = $movieDao->getMoviesByUserId($id);
?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">

      <!-- INFORMAÇÕES DO USUÁRIO -->
      <div class="col-md-12 about-container">
        <h1 class="page-title"><?= $fullName ?></h1>

        <div 
          id="profile-image-container" 
          class="profile-image"
          style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')">
        </div>

        <h3 class="about-title">Sobre:</h3>

        <?php if(!empty($userData->bio)): ?>
          <p class="profile-description"><?= $userData->bio ?></p>
        <?php else: ?>
          <p class="profile-description">
            O usuário ainda não escreveu nada aqui...
          </p>
        <?php endif; ?>

      </div>

      <!-- FILMES DO USUÁRIO -->
      <div class="col-md-12 added-movies-container">
        <h3>Filmes que enviou:</h3>

        <div class="movies-container">

          <?php if(count($userMovies) > 0): ?>

            <?php foreach($userMovies as $movie): ?>
              <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>

          <?php else: ?>
            <p class="empty-list">O usuário ainda não enviou filmes.</p>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</div>

<?php
  require_once("templates/footer.php");
?>