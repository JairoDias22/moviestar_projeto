<?php
  require_once("templates/header.php");

  // Verifica se usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true);

?>
  <div id="main-container" class="container-fluid">
    <div class="offset-md-4 col-md-4 new-movie-container">
      <div class="text-center mb-4">
      <h1 class="page-title d-inline-block border-bottom pb-2 border-warning">Adicionar Filme</h1>
      </div>
      <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
      <form action="<?= $BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="type" value="create">
        <div class="form-group">
          <label for="title">Título:</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do seu filme">
        </div>
        <div class="form-group">
          <label for="image">Imagem:</label>
          <input type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group">
          <label for="length">Duração:</label>
          <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
        </div>
        <div class="form-group">

          <label for="category">Category:</label>
          <select name="category" id="category" class="form-control">
            <option value="">Selecione uma categoria</option>
            <option value="Ação">Ação</option>
            <option value="Comédia">Comédia</option>
            <option value="Drama">Drama</option>
            <option value="Terror">Terror</option>
            <option value="Romance">Romance</option>
            <option value="Ficção Científica">Ficção Científica</option>
          </select>
          
        </div>
        <div class="form-group">
          <label for="trailer">Trailer:</label>
          <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
        </div>
        <div class="form-group">
          <label for="description">Descrição:</label>
          <textarea name="description" id="description" rows="5" class="form-control" placeholder="Descreva o filme..."></textarea>
        </div>
        <input type="submit" class="button btn btn-outline-warning btn-block" value="Adicionar Filme">
      </form>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>