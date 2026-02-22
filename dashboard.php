<?php
  // Inclui o header padrão do sistema (menu, conexão, variáveis globais etc.)
  require_once("templates/header.php");

  // ===== IMPORTAÇÃO DOS MODELS E DAOS =====
  // Model de usuário
  require_once("models/User.php");
  // DAO responsável pelas operações do usuário
  require_once("dao/UserDAO.php");
  // DAO responsável pelas operações dos filmes
  require_once("dao/MovieDAO.php");
  // DAO responsável pelas avaliações (reviews)
  require_once("dao/ReviewDao.php"); 

  // ===== INSTÂNCIAS DAS CLASSES =====
  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);
  $reviewDao = new ReviewDao($conn); 

  // ===== VERIFICAÇÃO DE LOGIN =====
  // Verifica se o token do usuário é válido (usuário autenticado)
  $userData = $userDao->verifyToken(true);
  
  // Se o usuário estiver logado e tiver ID válido
  if($userData && isset($userData->id)){
    // Busca todos os filmes cadastrados por ele
    $userMovies = $movieDao->getMoviesByUserId($userData->id);
  } else {
    // Caso não esteja logado, retorna array vazio
    $userMovies = [];
  }
?>

<div id="main-container" class="container-fluid">

  <!-- Título da página -->
  <h2 class="section-title">Dashboard</h2>
  <p class="section-description">
    Adicione ou atualize as informações dos filmes que você enviou
  </p>

  <!-- Botão para adicionar novo filme -->
  <div class="col-md-12 add" id="add-movie-container">
    <a href="<?= $BASE_URL ?>newmovie.php" class="btn btn-outline-warning">
      <i class="fas fa-plus add"></i> Adicionar Filme
    </a>
  </div>

  <!-- Tabela que lista os filmes do usuário -->
  <div class="col-md-12" id="movies-dashboard">
    <table class="table ">
      <thead>
        <!-- Cabeçalho da tabela -->
        <th scope="col">N°</th>
        <th scope="col">Título</th>
        <th scope="col">Nota</th>
        <th scope="col" class="actions-column">Ações</th>
      </thead>

      <tbody>

        <!-- Loop que percorre todos os filmes do usuário -->
        <?php foreach($userMovies as $movie): ?>
        <tr>

          <!-- ID do filme -->
          <td scope="row"><?= $movie->id ?></td>

          <!-- Título com link para página individual do filme -->
          <td>
            <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="table-movie-title">
              <?= $movie->title ?>
            </a>
          </td>

          <!-- Média das avaliações do filme -->
          <td>
            <i class="fas fa-star"></i>
            <?= $reviewDao->getRatings($movie->id) ?>
          </td>

          <!-- Coluna de ações -->
          <td class="actions-column">
            <div class="action-buttons">

           <!-- Botão Editar -->
          <a href="<?= $BASE_URL ?>editmovie.php?id=<?= $movie->id ?>" class=" btn btn-outline-warning ">
            <i class="far fa-edit "></i> Editar
           </a>

           <!-- Botão Deletar -->
           <form action="<?= $BASE_URL ?>movie_process.php" method="POST">
             <input type="hidden" name="type" value="delete">
             <input type="hidden" name="id" value="<?= $movie->id ?>">

              <button type="submit" class="btn btn-outline-primary "
                onclick="return confirm('Tem certeza que deseja excluir este filme?')">
               <i class="fas fa-times "></i> Deletar
              </button>
             </form>
              </div>
          </td>
        </tr>
        <?php endforeach; ?>

        <!-- Caso o usuário não tenha nenhum filme cadastrado -->
        <?php if(count($userMovies) === 0): ?>
          <tr>
            <td colspan="4" class="empty-list text-center">
              Você ainda não cadastrou nenhum filme 😅
            </td>
          </tr>
        <?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

<?php
  // Inclui o footer padrão do sistema
  require_once("templates/footer.php");
?>