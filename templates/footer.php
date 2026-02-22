<footer id="footer"> 
  <div id="social-container">
    <ul>
      <li class="social-item">
        <a class="social-icons" href="#"><i class="fab fa-facebook"></i></a>
      </li>
      <li class="social-item">
        <a class="social-icons" href="#"><i class="fab fa-instagram"></i></a>
      </li>
      <li class="social-item">
        <a class="social-icons" href="#"><i class="fab fa-youtube"></i></a>
      </li>
    </ul>
  </div>

  <div id="footer-links-container">
    <ul>

      <!-- Adicionar Filme -->
      <li class="footer-link">
        <?php if($userData): ?>
          <a class="footer-links" href="<?= $BASE_URL ?>newmovie.php?action=movie">Adicionar filme</a>
       <?php else: ?>
        <a class="footer-links" href="<?= $BASE_URL ?>auth.php?action=movie">Adicionar filme</a>
      <?php endif; ?>
      </li>

      <!-- Adicionar Crítica -->
      <li class="footer-link">
        <?php if($userData): ?>
          <a class="footer-links" href="<?= $BASE_URL ?>index.php?action=review">
            Adicionar crítica
          </a>
        <?php else: ?>
          <a class="footer-links" href="<?= $BASE_URL ?>auth.php?action=review">Adicionar crítica</a>
        <?php endif; ?>
      </li>

      <!-- Entrar / Registrar -->
      <li class="footer-link">
        <a class="footer-links" href="<?= $BASE_URL ?>auth.php">Entrar / Registrar</a>
      </li>

    </ul>
  </div>

  <p class="footer-txt"> &copy; 2026 MovieStar</p>
</footer>