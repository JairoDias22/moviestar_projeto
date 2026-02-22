<?php

// Classe que representa uma avaliação de filme
class Review {

    public $id;        // ID da avaliação
    public $rating;    // Nota do filme (ex: 1 a 5)
    public $review;    // Texto da avaliação
    public $users_id;  // ID do usuário que fez a avaliação
    public $movies_id; // ID do filme avaliado
}

// Interface que define os métodos obrigatórios do ReviewDAO
interface ReviewDAOInterface {

    public function buildReview($data);       // Monta um objeto Review a partir de dados do banco
    public function create(Review $review);   // Cria uma nova avaliação
    public function getMoviesReview($id);     // Retorna todas as avaliações de um filme
    public function hasAlreadyReviewed($id, $userId); // Verifica se um usuário já avaliou o filme
    public function getRatings($id);          // Calcula a média das avaliações de um filme

}