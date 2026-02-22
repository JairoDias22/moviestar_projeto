<?php
  // Inclui o header da aplicação (menu, sessão, conexão com banco, variáveis globais)
  require_once("templates/header.php");

  // Importa as classes relacionadas ao usuário
  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  // Cria instâncias do Model e do DAO
  $user = new User();
  $userDao = new UserDAO($conn, $BASE_URL);

  // Verifica se o usuário está autenticado (true = redireciona se não estiver logado)
  $userData = $userDao->verifyToken(true);

  // Obtém o nome completo do usuário
  $fullName = $user->getFullName($userData);

  // Define imagem padrão caso o usuário não tenha foto
  if(empty($userData->image)) {
    $userData->image = "user.png";
  }
?>

<!-- ================================
     CONTAINER PRINCIPAL DA PÁGINA
================================ -->
<div id="main-container" class="container-fluid edit-profile-page">
  <div class="col-md-12">

    <!-- ================================
         FORMULÁRIO DE ATUALIZAÇÃO DE PERFIL
    ================================= -->
    <form action="<?= $BASE_URL ?>user_process.php"
          method="POST"
          enctype="multipart/form-data">

      <!-- Define o tipo da ação -->
      <input type="hidden" name="type" value="update">

      <div class="row">

        <!-- COLUNA 1 - DADOS BÁSICOS -->
        <div class="col-md-4">

          <!-- Nome do usuário -->
          <h1><?= $fullName ?></h1>

          <!-- Texto explicativo -->
          <p class="page-description">
            Altere seus dados no formulário abaixo:
          </p>

          <!-- CAMPO NOME -->
          <div class="form-group m-1">
            <label for="name">Nome:</label>
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?= $userData->name ?>">
          </div>

          <!-- CAMPO SOBRENOME -->
          <div class="form-group m-1">
            <label for="lastname">Sobrenome:</label>
            <input type="text"
                   class="form-control"
                   id="lastname"
                   name="lastname"
                   value="<?= $userData->lastname ?>">
          </div>

          <!-- CAMPO EMAIL (SOMENTE LEITURA) -->
          <div class="form-group m-1">
            <label for="email">E-mail:</label>
            <input type="text"
                   readonly
                   class="form-control disabled"
                   id="email"
                   name="email"
                   value="<?= $userData->email ?>">
          </div>

          <!-- BOTÃO PARA SALVAR ALTERAÇÕES -->
          <input type="submit"
                 class="btn btn-outline-warning"
                 value="Alterar">
        </div>

        <!-- COLUNA 2 - FOTO E BIO -->
        <div class="col-md-4">

          <!-- Container da imagem de perfil -->
          <div id="profile-image-container"
               style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')">
          </div>

          <!-- CAMPO PARA UPLOAD DE FOTO -->
          <div class="form-group">
            <label for="image">Foto:</label>
            <input type="file"
                   class="form-control-file"
                   name="image">
          </div>

          <!-- CAMPO BIO -->
          <div class="form-group">
            <label for="bio">Sobre você:</label>
            <textarea class="form-control"
                      name="bio"
                      id="bio"
                      rows="5"><?= $userData->bio ?></textarea>
          </div>

        </div>

        <div class="col-md-4">
          <div id="profile-image-container" 
               style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')"></div>

          <div class="form-group">
            <label for="image">Foto:</label>
            <input type="file" class="form-control-file" name="image">
          </div>

          <div class="form-group">
            <label for="bio">Sobre você:</label>
            <textarea class="form-control" name="bio" id="bio" rows="5"><?= $userData->bio ?></textarea>
          </div>
        </div>
      </div>
    </form>

    <div class="row" id="change-password-container">
      <div class="col-md-4">
        <h2>Alterar a senha:</h2>
        <p class="page-description">Digite a nova senha e confirme:</p>

        <form action="<?= $BASE_URL ?>user_process.php" method="POST">
          <input type="hidden" name="type" value="changepassword">

          <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>

          <div class="form-group">
            <label for="confirmpassword">Confirmação de senha:</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword">
          </div>

          <input type="submit" class="btn card-btn" value="Alterar Senha">
        </form>
      </div>
    </form>

    <!-- ================================
         FORMULÁRIO DE ALTERAÇÃO DE SENHA
    ================================= -->
    <div class="row" id="change-password-container">
      <div class="col-md-4">

        <h2>Alterar a senha:</h2>

        <p class="page-description">
          Digite a nova senha e confirme:
        </p>

        <form action="<?= $BASE_URL ?>user_process.php"
              method="POST">

          <!-- Define o tipo da ação -->
          <input type="hidden" name="type" value="changepassword">

          <!-- CAMPO NOVA SENHA -->
          <div class="form-group m-1">
            <label for="password">Senha:</label>
            <input type="password"
                   class="form-control"
                   id="password"
                   name="password">
          </div>

          <!-- CAMPO CONFIRMAÇÃO DE SENHA -->
          <div class="form-group m-1">
            <label for="confirmpassword">Confirmação de senha:</label>
            <input type="password"
                   class="form-control"
                   id="confirmpassword"
                   name="confirmpassword">
          </div>

          <!-- BOTÃO PARA ALTERAR SENHA -->
          <input type="submit"
                 class="btn btn-outline-warning"
                 value="Alterar Senha">
        </form>
      </div>
    </div>

  </div>
</div>

<?php
  // Inclui o footer da aplicação
  require_once("templates/footer.php");
?>
