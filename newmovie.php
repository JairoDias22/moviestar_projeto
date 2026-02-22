
<?php
  // Inclui o header da aplicação
  // (normalmente contém conexão com banco, sessão, menu e variáveis globais)
  require_once("templates/header.php");

  // Importa o Model e o DAO do usuário
  // Isso é necessário para verificar se o usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  // Cria uma instância do model User
  $user = new User();

  // Cria uma instância do DAO do usuário
  // Passa a conexão com o banco e a BASE_URL
  $userDao = new UserDao($conn, $BASE_URL);

  // Verifica se o usuário está autenticado
  // O "true" força redirecionamento caso não esteja logado
  $userData = $userDao->verifyToken(true);
?>

<!-- Container principal da página -->
<div id="main-container" class="container-fluid ">

  <!-- Centraliza o formulário na tela -->
  <div class="offset-md-4 col-md-4 new-movie-container">

    <!-- Título da página -->
    <div class="text-center mb-4">
      <h1 class="page-title d-inline-block border-bottom pb-2 border-warning">
        Adicionar Filme
      </h1>
    </div>

    <!-- Texto explicativo -->
    <p class="page-description">
      Adicione sua crítica e compartilhe com o mundo!
    </p>

    <!-- Formulário para criação do filme -->
    <!-- enctype="multipart/form-data" é obrigatório para upload de imagem -->
    <form action="<?= $BASE_URL ?>movie_process.php"
          method="POST"
          enctype="multipart/form-data">

      <!-- Campo oculto que informa ao movie_process que é uma criação -->
      <input type="hidden" name="type" value="create">

      <!-- Campo Título -->
      <div class="form-group">
        <label for="title">Título:</label>
        <input type="text" class="form-control w-100" id="title" name="title" placeholder="Digite o título do seu filme">
      </div>

      <!-- Campo de upload da imagem do filme -->
      <div class="form-group">
        <label for="image">Imagem:</label>
        <input type="file" class="form-control-file w-100" name="image" id="image">
      </div>

      <!-- Campo Duração -->
      <div class="form-group">
        <label for="length">Duração:</label>
        <input type="text"
               class="form-control w-100" id="length" name="length" placeholder="Digite a duração do filme">
      </div>

      <!-- Campo Categoria -->
      <div class="form-group">
        <label for="category">Category:</label>

        <!-- Select para escolher a categoria do filme -->
        <select name="category" id="category" class="form-control w-100">

          <option value="">Selecione uma categoria</option>
          <option value="Ação">Ação</option>
          <option value="Comédia">Comédia</option>
          <option value="Terror">Terror</option>
          <option value="Romance">Romance</option>
          <option value="Drama">Drama</option>
          <option value="Animação">Animação</option>
          <option value="Fantasia">Fantasia</option>


        </select>
      </div>

      <!-- Campo Trailer (link do YouTube, por exemplo) -->
      <div class="form-group">
        <label for="trailer">Trailer:</label>
        <input type="text" class="form-control w-100" id="trailer" name="trailer" placeholder="Insira o link do trailer">
      </div>

      <!-- Campo Descrição -->
      <div class="form-group">
        <label for="description">Descrição:</label>
        <textarea name="description" id="description" rows="5"  class="form-control w-100" placeholder="Descreva o filme..."></textarea>
      </div>

      <!-- Botão de envio -->
      <input type="submit" class="button btn btn-outline-warning btn-block" value="Adicionar Filme">
    </form>
  </div>
</div>

<?php
  // Inclui o footer da aplicação
  require_once("templates/footer.php");
?>