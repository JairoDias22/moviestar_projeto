<?php
  // ================================
  // TRATAMENTO DOS DADOS DO USUÁRIO
  // ================================

  // Nome completo do autor do review (dados vindos do JOIN no banco)
  $fullName = $review->user_name . " " . $review->user_lastname;

  // Define imagem padrão caso o usuário não tenha foto
  if(empty($review->user_image)) {
    $userImage = "user.png";
  } else {
    $userImage = $review->user_image;
  }

  // ID do usuário (usado para linkar para o perfil)
  $userId = $review->users_id;

  
?>

<!-- ================================
     CARD DO REVIEW DO USUÁRIO
================================== -->
<div class="col-md-12 user-review">
  <div class="row">

    <!-- Imagem do usuário -->
    <div class="col-md-1">
      <div 
        class="profile-image-container review-image"
        style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userImage ?>')">
      </div>
    </div>

    <!-- Informações do autor e nota -->
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <!-- Link para o perfil do usuário -->
        <a href="<?= $BASE_URL ?>profile.php?id=<?= $userId ?>">
          <?= $fullName ?>
        </a>
      </h4>

      <!-- Nota do review -->
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