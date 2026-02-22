<?php
  // Inclui o header da aplicação (menu, sessão, conexão com banco, variáveis globais)
  require_once("templates/header.php");

  // Importa as classes necessárias
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");

  // Cria os objetos de User e DAO
  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);

  // Verifica se o usuário está autenticado (true = redireciona se não estiver logado)
  $userData = $userDao->verifyToken(true);

  // Cria o DAO de filmes
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // Recupera o ID do filme pela URL (GET)
  $id = filter_input(INPUT_GET, "id");

  // Valida se o ID foi informado
  if(empty($id)) {
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
  } else {

    // Busca o filme no banco
    $movie = $movieDao->findById($id);

    // Verifica se o filme existe
    if(!$movie) {
      $message->setMessage("O filme não foi encontrado!", "error", "index.php");
    }

  }

  // Define imagem padrão caso o filme não tenha imagem cadastrada
  if(empty($movie->image)) {
    $movie->image = "movie_cover.jpg";
  }
?>

<div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row">

      <!-- COLUNA DO FORMULÁRIO -->
      <div class="col-md-6 offset-md-1">
        <h1><?= $movie->title; ?></h1>
        <p class="page-description">
          Altere os dados do filme no formulário abaixo:
        </p>

        <form id="edit-movie-form"
              action="<?= $BASE_URL ?>movie_process.php"
              method="POST"
              enctype="multipart/form-data">

          <!-- Define o tipo da ação -->
          <input type="hidden" name="type" value="update">

          <!-- Envia o ID do filme -->
          <input type="hidden" name="id" value="<?= $movie->id ?>">

          <!-- TÍTULO -->
          <div class="form-group">
            <label for="title">Título:</label>
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   placeholder="Digite o título do seu filme"
                   value="<?= $movie->title ?>">
          </div>

          <!-- IMAGEM -->
          <div class="form-group">
            <label for="image">Imagem:</label>
            <input type="file"
                   class="form-control-file"
                   name="image"
                   id="image">
          </div>

          <!-- DURAÇÃO -->
          <div class="form-group">
            <label for="length">Duração:</label>
            <input type="text"
                   class="form-control"
                   id="length"
                   name="length"
                   placeholder="Digite a duração do filme"
                   value="<?= $movie->length ?>">
          </div>

          <!-- CATEGORIA -->
          <div class="form-group">
            <label for="category">Categoria:</label>
            <select name="category" id="category" class="form-control">
              <option value="">Selecione</option>
              <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
              <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
              <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
              <option value="Fantasia / Ficção" <?= $movie->category === "Fantasia / Ficção" ? "selected" : "" ?>>Fantasia / Ficção</option>
              <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
            </select>
          </div>

          <!-- TRAILER -->
          <div class="form-group">
            <label for="trailer">Trailer:</label>
            <input type="text"
                   class="form-control"
                   id="trailer"
                   name="trailer"
                   placeholder="Insira o link do trailer"
                   value="<?= $movie->trailer ?>">
          </div>

          <!-- DESCRIÇÃO -->
          <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea name="description"
                      id="description"
                      rows="5"
                      class="form-control"
                      placeholder="Descreva o filme..."><?= $movie->description ?></textarea>
          </div>

          <!-- BOTÃO -->
          <input type="submit" class="btn card-btn" value="Editar filme">
        </form>
      </div>

      <!-- COLUNA DA IMAGEM (mantendo a classe do código 1) -->
      <div class="col-md-3">
        <div class="edit-image-container"
             style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')">
        </div>
      </div>

    </div>
  </div>
</div>

<?php
  // Inclui o footer da aplicação
  require_once("templates/footer.php");
?>