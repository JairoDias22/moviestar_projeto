<?php

// TODO: Incluir arquivos necessários (globals, db, models, DAOs)
require_once("globals.php");
require_once("db.php");

require_once("models/Review.php");
require_once("models/Message.php");

require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/ReviewDAO.php");


// Instâncias das classes necessárias
$message = new Message($BASE_URL); // Responsável por mostrar mensagens para o usuário
$userDao = new UserDAO($conn, $BASE_URL, $message); // Valida login e manipula dados de usuário
$movieDao = new MovieDAO($conn, $BASE_URL); // Busca informações de filmes
$reviewDao = new ReviewDAO($conn, $BASE_URL); // Salva e manipula reviews

// Recebe o tipo do formulário enviado via POST
$type = filter_input(INPUT_POST, "type"); // Pode ser "create" ou outros tipos

// Resgata os dados do usuário logado
$userData = $userDao->verifyToken();

// Verifica se o formulário é de criação de review
if($type === "create") {

    // Recebe os dados enviados pelo formulário
    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $moviesId = filter_input(INPUT_POST, "movies_id");

    // Verifica se todos os campos obrigatórios foram preenchidos
    if($rating && $review && $moviesId){

        // Busca o filme no banco pelo ID
        $movieData = $movieDao->findById($moviesId);

        // Se o filme existir no sistema
        if($movieData){

            // Cria um novo objeto Review
            $newReview = new Review();

            // Preenche os dados da review
            $newReview->rating = $rating;
            $newReview->review = $review;
            $newReview->moviesId = $moviesId;
            $newReview->usersId = $userData->id;

            // Salva a review no banco de dados
            $reviewDao->create($newReview);

            // Mostra mensagem de sucesso e redireciona para a página do filme
            $message->setMessage("Review criada com sucesso", "success", "movie.php?id=" . $moviesId);

        } else {
            // Se o filme não for encontrado no banco
            $message->setMessage("Filme não encontrado.", "error", "index.php");
        }
    }
    else{
        // Se algum campo obrigatório estiver vazio
        $message->setMessage("Preencha todos os campos!", "error", "back");
    }

} else {

    // Se o tipo de formulário não for "create"
    $message->setMessage("Tipo de formulário inválido.", "error", "index.php");

}

