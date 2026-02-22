<?php

  // Inclui o header padrão do site (menu, head, etc.)
  require_once("templates/header.php");

   // Mensagem de alerta Caso o usuario nao esteja logado e tentar adicionar filme ou critica
   if(isset($_GET["action"])){

  if($_GET["action"] === "movie"){
    $message->setMessage(
      "É necessário estar logado para adicionar um filme",
      "error",
      "auth.php"
    );
  }

  if($_GET["action"] === "review"){
    $message->setMessage(
      "É necessário estar logado para adicionar uma avaliação",
      "error",
      "auth.php"
    );
  }

}

?>

  <!-- Container principal da página -->
  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
      <!-- Linha principal da área de autenticação -->
      <div class="row" id="auth-row">

        <!-- Container do Login -->
        <div class="col-md-4" id="login-container">
          <!-- Título do formulário de login (mantido do código 1) -->
          <h2 id="title_auth">Entrar</h2>

          <!-- Formulário de Login -->
          <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
            <!-- Define o tipo da ação no backend -->
            <input type="hidden" name="type" value="login">

            <!-- Campo de e-mail -->
            <div class="form-group">
              <label for="email">E-mail:</label>
              <input 
                type="email" 
                class="form-control w-75" 
                id="email" 
                name="email" 
                placeholder="Digite seu e-mail"
                required
              >
            </div>

            <!-- Campo de senha -->
            <div class="form-group">
              <label for="password">Senha:</label>
              <input 
                type="password" 
                class="form-control w-75" 
                id="password" 
                name="password" 
                placeholder="Digite sua senha"
                required
              >
            </div>

            <!-- Botão de envio do login (ID corrigido para não duplicar) -->
            <input 
              type="submit" 
              class="btn btn-outline-warning w-75 cd" 
              id="btn_login" 
              value="Entrar"
            >
          </form>
        </div>

        <!-- Container do Cadastro -->
        <div class="col-md-4" id="register-container">
          <!-- Título do formulário de cadastro (mantido do código 1) -->
          <h2 id="h2_contact">Criar Conta</h2>

          <!-- Formulário de Cadastro -->
          <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
            <!-- Define o tipo da ação no backend -->
            <input type="hidden" name="type" value="register">

            <!-- Campo de e-mail -->
            <div class="form-group">
              <label for="email">E-mail:</label>
              <input 
                type="email" 
                class="form-control" 
                id="email" 
                name="email" 
                placeholder="Digite seu e-mail"
                required
              >
            </div>

            <!-- Campo de nome -->
            <div class="form-group">
              <label for="name">Nome:</label>
              <input 
                type="text" 
                class="form-control" 
                id="name" 
                name="name" 
                placeholder="Digite seu nome"
                required
              >
            </div>

            <!-- Campo de sobrenome -->
            <div class="form-group">
              <label for="lastname">Sobrenome:</label>
              <input 
                type="text" 
                class="form-control" 
                id="lastname" 
                name="lastname" 
                placeholder="Digite seu sobrenome"
                required
              >
            </div>

            <!-- Campo de senha -->
            <div class="form-group">
              <label for="password">Senha:</label>
              <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                placeholder="Digite sua senha"
                required
              >
            </div>

            <!-- Campo de confirmação de senha -->
            <div class="form-group">
              <label for="confirmpassword">Confirmação de senha:</label>
              <input 
                type="password" 
                class="form-control" 
                id="confirmpassword" 
                name="confirmpassword" 
                placeholder="Confirme sua senha"
                required
              >
            </div>

            <!-- Botão de envio do cadastro (mantido do código 1) -->
            <input 
              type="submit" 
              class="btn card-btn cd" 
              id="btn_registrar" 
              value="Registrar"
            >
          </form>
        </div>

      </div>

      <!-- Formulário de registro -->
      <div class="col-md-4" id="register-container">
        <h2>Criar Conta</h2>
        <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
          <input type="hidden" name="type" value="register"> <!-- Tipo de ação -->

          <!-- E-mail -->
          <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
          </div>

          <!-- Nome -->
          <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome">
          </div>

          <!-- Sobrenome -->
          <div class="form-group">
            <label for="lastname">Sobrenome:</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome">
          </div>

          <!-- Senha -->
          <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
          </div>

          <!-- Confirmação de senha -->
          <div class="form-group">
            <label for="confirmpassword">Confirmação de senha:</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua senha">
          </div>

          <!-- Botão de registrar -->
          <input type="submit" class="btn card-btn" value="Registrar">
        </form>
      </div>

    </div>
  </div>
</div>

<?php
  // Inclui o footer padrão do site
  require_once("templates/footer.php");
?>