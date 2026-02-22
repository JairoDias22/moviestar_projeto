<?php
<<<<<<< HEAD
require_once("models/User.php"); // Classe User
require_once("dao/UserDAO.php"); // DAO de usuários
require_once("db.php"); // Conexão com banco
require_once("globals.php"); // Variáveis globais

$userDao = new UserDAO($conn, $BASE_URL); // Instancia DAO

// Pega os dados do usuário que fez a review
$reviewUser = $userDao->findById($review->users_id);

// Se não encontrar usuário, não exibe
if(!$reviewUser) {
  return;
}

// Se o usuário não tiver imagem, usa padrão
if(empty($reviewUser->image)) {
  $reviewUser->image = "user.png";
}
=======
  require_once("models/User.php");

  $userModel = new User();

  // Nome completo do usuário da review
  $fullName = $userModel->getFullName($reviewUser);

  // Imagem do usuário (com fallback)
  $userImage = "user.png";
  if($reviewUser && $reviewUser->image) {
    $userImage = $reviewUser->image;
  }
>>>>>>> 1b5e252fa405fc9405532c030444a916814c2cb7
?>

<!-- User Review Card -->
<div class="col-md-12 review">

  <div class="row">
    
    <!-- Imagem do perfil -->
    <div class="col-md-1">
<<<<<<< HEAD
      <div class="profile-image-container review-image"
           style="background-image: url('<?= $BASE_URL ?>img/users/<?= $reviewUser->image ?>')">
=======
      <div 
        class="profile-image-container review-image"
        style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userImage ?>')">
>>>>>>> 1b5e252fa405fc9405532c030444a916814c2cb7
      </div>
    </div>

    <!-- Nome do autor e avaliação -->
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <a href="<?= $BASE_URL ?>profile.php?id=<?= $reviewUser->id ?>">
          <?= $fullName ?>
        </a>
      </h4>

      <p>
        <i class="fas fa-star"></i> <?= $review->rating ?>
      </p>
    </div>

    <!-- Comentário do usuário -->
    <div class="col-md-12">
      <p class="comment-title">Comentário:</p>
      <p><?= $review->review ?></p>
    </div>

  </div>
</div>