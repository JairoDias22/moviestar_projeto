<?php

  // Importa as configurações globais do sistema (como $BASE_URL)
  require_once("globals.php");

  // Importa a conexão com o banco de dados
  require_once("db.php");

  // Importa a classe responsável por mensagens de feedback (flash messages)
  require_once("models/Message.php");

  // Importa o DAO de usuários (responsável por buscar e validar usuário)
  require_once("dao/UserDAO.php");

  // Cria o objeto de mensagens do sistema
  $message = new Message($BASE_URL);

  // Recupera a mensagem flash (se existir)
  $flashMessage = $message->getMessage();

  // Cria o objeto DAO para manipular usuários no banco
  $userDao = new UserDAO($conn, $BASE_URL);

  // Verifica se existe um token válido na sessão e retorna os dados do usuário logado (se houver)
  $userData = $userDao->verifyToken(false); 
  // O parâmetro false indica que a página não é protegida (não obriga login)

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Define o conjunto de caracteres como UTF-8 -->
  <meta charset="UTF-8">

  <!-- Configuração de responsividade para dispositivos móveis -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Título da página -->
  <title>MovieStar</title>

  <!-- Ícone da aba do navegador -->
  <link rel="short icon" href="<?= $BASE_URL ?>img/moviestar.ico" />

  <!-- Importação do Bootstrap via CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css" integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw==" crossorigin="anonymous" />

  <!-- Importação dos ícones do Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <!-- Importação do Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

  <!-- Importação do CSS do projeto -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>/css/styles.css">
</head>
<body>

  <header>

  <!-- BLOCO DE MENSAGENS DO SISTEMA (flash messages) -->
  <?php if(!empty($flashMessage["msg"])): ?>
    <div class="msg-container  text-center p-3">
      <!-- Exibe a mensagem com a classe de tipo (success, error, etc.) -->
      <p class="msg <?= $flashMessage["type"] ?> m-0">
        <?= $flashMessage["msg"] ?>
      </p>
    </div>

    <?php 
      // Limpa a mensagem da sessão após exibir
      $message->clearMessage(); 
    ?>
  <?php endif; ?>

  <!-- Barra de navegação principal -->
  <nav id="main-navbar">

    <!-- Logo e nome do site -->
    <a href="index.php" class="navbar-brand">
      <img src="img/logo.svg" alt="MovieStar" id="logo">
      <span id="moviestar-title">MovieStar</span>
    </a>

    <!-- FORMULÁRIO DE BUSCA DE FILMES -->
    <form action="<?= $BASE_URL ?>search.php" method="GET" id="search-form">
      <input type="text" name="q" id="search" placeholder="Buscar Filmes" aria-label="Search">
      <button type="submit"><i class="bi bi-search"></i></button>
    </form>

    <!-- Verifica se o usuário está logado -->
    <?php if($userData): ?> 
      <!-- Menu exibido para usuário logado -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="<?= $BASE_URL ?>newmovie.php" class="nav-link">
            <i class="bi bi-plus-square"></i>Incluir Filme
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
        </li>
        <li class="nav-item">
          <a href="<?= $BASE_URL ?>editprofile.php" class="nav-link bold">
            <?= $userData->name ?>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a>
        </li>

    <?php else: ?>
      <!-- Menu exibido para visitante (não logado) -->
        <li class="nav-item">
          <a href="<?= $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
        </li>
    <?php endif; ?>
      </ul>
  </nav>

</header>