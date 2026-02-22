<?php

// Classe que representa um filme
class Movie {

    public $id;
    public $title;
    public $description;
    public $image;
    public $trailer;
    public $category;
    public $length;
    public $users_id;
    public $rating;

    // Gera um nome aleatório para a imagem
    public function imageGenerateName() {
        return bin2hex(random_bytes(60)) . ".jpg";
    }
}

// Interface que define os métodos obrigatórios do MovieDAO
interface MovieDAOInterface {

    public function buildMovie($data);          // Monta um objeto Movie a partir de dados do banco
    public function findAll();                   // Retorna todos os filmes
    public function getLatestMovies();           // Retorna os 10 filmes mais recentes
    public function getMoviesByCategory($category); // Retorna filmes filtrados por categoria
    public function getMoviesByUserId($id);     // Retorna filmes cadastrados por um usuário específico
    public function findById($id);              // Retorna um filme pelo ID
    public function findByTitle($title);        // Busca filmes pelo título
    public function create(Movie $movie);       // Cria um novo filme
    public function update(Movie $movie);       // Atualiza um filme existente
    public function destroy($id);               // Remove um filme pelo ID

}