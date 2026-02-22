<?php
  require_once("models/User.php");

  $userModel = new User();

  // Nome completo do usuário da review
  $fullName = $userModel->getFullName($reviewUser);

  // Imagem do usuário (com fallback)
  $userImage = "user.png";
  if($reviewUser && $reviewUser->image) {
    $userImage = $reviewUser->image;
  }
?>

<!-- User Review Card -->
<div class="col-md-12 review">

  <div class="row">
    
    <!-- Imagem do perfil -->
    <div class="col-md-1">
      <div 
        class="profile-image-container review-image"
        style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userImage ?>')">
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