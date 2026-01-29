<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  $message = new Message($BASE_URL);

  $flassMessage = $message->getMessage();

  if(!empty($flassMessage["msg"])) {
    // Limpar a mensagem
    $message->clearMessage();
  }

  $userDao = new UserDAO($conn, $BASE_URL);

  $userData = $userDao->verifyToken(false);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieStar</title>
  <link rel="short icon" href="<?= $BASE_URL ?>img/moviestar.ico" />
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css" integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw==" crossorigin="anonymous" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">
</head>
<body>
  <header>
  <nav id="main-navbar">
    <a href="index.php" class="navbar-brand">
      <img src="img/logo.svg" alt="MovieStar" id="logo">
      <span id="moviestar-title">MovieStar</span>
    </a>
    <form action="#" method="GET" id="search-form">
      <input type="text" name="q" id="search" placeholder="Buscar Filmes" aria-label="Search">
      <button type="submit">Buscar</button>
    </form>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="newmovie.php" class="nav-link">Incluir Filme</a>
      </li>
      <li class="nav-item">
        <a href="html" class="nav-link">Meus Filmes</a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link bold">Usuário</a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">Sair</a>
      </li>
      <li class="nav-item">
        <a href="auth.php" class="nav-link">Entrar / Cadastrar</a>
      </li>
    </ul>
  </nav>
</header>

