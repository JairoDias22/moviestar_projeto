<?php

// ================= IMPORTAÇÕES =================
// Arquivos globais e conexão com banco
require_once("globals.php");
require_once("db.php");

// Models
require_once("models/Movie.php");
require_once("models/Message.php");

// DAOs
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

// ================= INSTÂNCIAS =================
// Classe de mensagens (feedback ao usuário)
$message = new Message($BASE_URL);

// DAO de usuário (autenticação)
$userDao = new UserDAO($conn, $BASE_URL);

// DAO de filmes (CRUD de filmes)
$movieDao = new MovieDAO($conn, $BASE_URL);

// ================= AUTENTICAÇÃO =================
// Resgata dados do usuário logado (se não estiver logado, verifyToken retorna false)
$userData = $userDao->verifyToken();

// Se não estiver autenticado, interrompe o script
if(!$userData) {
  $message->setMessage("Usuário não autenticado!", "error", "index.php");
  exit;
}

// ================= TIPO DO FORMULÁRIO =================
// Resgata o tipo do formulário enviado (create, update ou delete)
$type = filter_input(INPUT_POST, "type");

// ================= CREATE =================
if($type === "create") {

  // Recebe os dados do formulário
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");

  // Cria um novo objeto Movie
  $movie = new Movie();

  // Validação mínima de dados obrigatórios
  if(!empty($title) && !empty($description) && !empty($category)) {

    // Preenche os dados do filme
    $movie->title = $title;
    $movie->description = $description;
    $movie->trailer = $trailer;
    $movie->category = $category;
    $movie->length = $length;
    $movie->users_id = $userData->id;

    // ================= UPLOAD DA IMAGEM =================
    // Verifica se foi enviada uma imagem
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

      $image = $_FILES["image"];

      // Tipos de imagem permitidos
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];

      // Verifica o tipo real do arquivo (mais seguro que $_FILES["type"])
      $mimeType = mime_content_type($image["tmp_name"]);

      // Verifica se o tipo da imagem é permitido
      if(in_array($mimeType, $imageTypes)) {

        // Cria a imagem conforme o tipo
        if(in_array($mimeType, $jpgArray)) {
          $imageFile = imagecreatefromjpeg($image["tmp_name"]);
        } else {
          $imageFile = imagecreatefrompng($image["tmp_name"]);
        }

        // Gera um nome único para a imagem
        $imageName = $movie->imageGenerateName();

        // Salva a imagem no diretório correto
        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

        // Libera memória
        imagedestroy($imageFile);

        // Salva o nome da imagem no objeto Movie
        $movie->image = $imageName;

      } else {
        $message->setMessage("Tipo inválido de imagem, insira PNG ou JPG!", "error", "back");
        exit;
      }
    }

    // Salva o filme no banco de dados
    $movieDao->create($movie);

    // Mensagem de sucesso
    $message->setMessage("Filme adicionado com sucesso!", "success", "index.php");

  } else {
    // Se os campos obrigatórios não forem preenchidos
    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
    exit;
  }


// ================= DELETE =================
} else if($type === "delete") {

  // Recebe o ID do filme
  $id = filter_input(INPUT_POST, "id");

  // Busca o filme no banco
  $movie = $movieDao->findById($id);

  // Verifica se o filme existe
  if($movie) {

    // Verifica se o filme pertence ao usuário logado
    if($movie->users_id === $userData->id) {

      // Remove o filme
      $movieDao->destroy($movie->id);

      // Mensagem de sucesso
      $message->setMessage("Filme removido com sucesso!", "success", "index.php");

    } else {
      $message->setMessage("Informações inválidas!", "error", "index.php");
      exit;
    }

  } else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
    exit;
  }


// ================= UPDATE =================
} else if($type === "update") {

  // Recebe os dados do formulário
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");
  $id = filter_input(INPUT_POST, "id");

  // Busca o filme no banco
  $movieData = $movieDao->findById($id);

  // ===== salvar dados antigos para comparação =====
  $oldTitle = $movieData->title;
  $oldDescription = $movieData->description;
  $oldTrailer = $movieData->trailer;
  $oldCategory = $movieData->category;
  $oldLength = $movieData->length;
  $oldImage = $movieData->image;

  // Verifica se o filme existe
  if($movieData) {

    // Verifica se o filme pertence ao usuário logado
    if($movieData->users_id === $userData->id) {

      // Validação mínima de dados obrigatórios
      if(!empty($title) && !empty($description) && !empty($category)) {

      // ===== verificar se imagem foi enviada =====
      $imageChanged = (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"]));

      // ===== verificar se nada mudou =====
      if(
        $title === $oldTitle &&
        $description === $oldDescription &&
        $trailer === $oldTrailer &&
        $category === $oldCategory &&
        $length === $oldLength &&
        !$imageChanged
      ){
        $message->setMessage("Nenhum dado foi alterado!", "error", "back");
        exit;
      }

        // Atualiza os dados do filme
        $movieData->title = $title;
        $movieData->description = $description;
        $movieData->trailer = $trailer;
        $movieData->category = $category;
        $movieData->length = $length;

        // ================= UPLOAD DA IMAGEM (UPDATE) =================
        // Verifica se foi enviada uma nova imagem
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

          $image = $_FILES["image"];

          // Tipos de imagem permitidos
          $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
          $jpgArray = ["image/jpeg", "image/jpg"];

          // Verifica o tipo real do arquivo
          $mimeType = mime_content_type($image["tmp_name"]);

          if(in_array($mimeType, $imageTypes)) {

            // Cria a imagem conforme o tipo
            if(in_array($mimeType, $jpgArray)) {
              $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {
              $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            // Gera um nome único para a imagem
            $movie = new Movie();
            $imageName = $movie->imageGenerateName();

            // Salva a imagem no diretório correto
            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

            // Libera memória
            imagedestroy($imageFile);

            // Atualiza a imagem do filme
            $movieData->image = $imageName;

          } else {
            $message->setMessage("Tipo inválido de imagem, insira PNG ou JPG!", "error", "back");
            exit;
          }
        }

        // Atualiza o filme no banco de dados
        $movieDao->update($movieData);

        // Mensagem de sucesso
        $message->setMessage("Filme atualizado com sucesso!", "success", "index.php");

      } else {
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
        exit;
      }

    } else {
      $message->setMessage("Informações inválidas!", "error", "index.php");
      exit;
    }

  } else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
    exit;
  }

} else {
  // Caso o tipo do formulário seja inválido
  $message->setMessage("Informações inválidas!", "error", "index.php");
  exit;
}
