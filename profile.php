<?php
  require_once("templates/header.php");

  // Verifica se usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("auth.php");

  $user = new User();
$userDao = new UserDAO($conn, $BASE_URL, $message);

$movieDao = new MovieDAO($conn, $BASE_URL, $message);

// Receber id do usuário
$id = filter_input(INPUT_GET, "id");

// Garantir que a variável exista
$userData = null;

if (empty($id)) {

    if (!empty($userData) && is_object($userData)) {

        $id = $userData->id;

    } else {

        $message->setMessage("Usuário não encontrado!", "error", "index.php");
        exit;

    }

} else {

    $userData = $userDao->findById($id);

    // Se não encontrar usuário
    if (!$userData || !is_object($userData)) {
        $message->setMessage("Usuário não encontrado!", "error", "index.php");
        exit;
    }

}

$fullName = null;

if ($userData && is_object($userData)) {

    $fullName = $user->getFullName($userData);

    if (empty($userData->image)) {
        $userData->image = "user.png";
    }

} else {

    $message->setMessage("Usuário não encontrado!", "error", "index.php");
    exit;

}


  // Filmes que o usuário adicionou
  $userMovies = $movieDao->getMoviesByUserId($id);

?>
  <div id="main-container" class="container-fluid">
    <div class="col-md-8 offset-md-2">
      <div class="row profile-container">
        <div class="col-md-12 about-container">
          <h1 class="page-title"><?= $fullName ?></h1>
          <div id="profile-image-container" class="profile-image" style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $userData[image] ?>')"></div>
          <h3 class="about-title">Sobre:</h3>
          <!--Verificar se a bio do objeto $userData esta vazia-->
          <?php if(!empty($userData->bio)): ?>
            <p class="profile-description"><?= $userData->bio ?></p>
          <?php else: ?>
            <p class="profile-description">O usuário ainda não escreveu nada aqui...</p>
            <?php endif; ?>
  
        </div>
        <div class="col-md-12 added-movies-container">
          <h3>Filmes que enviou:</h3>
          <div class="movies-container">
          <!--Verifica se o array $userMovies tem algum filme-->
             <?php if(!empty($userMovies)): ?>
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