<?php

// Classe que representa um filme
class Movie {

    public $id;          // ID do filme no banco
    public $title;       // Título do filme
    public $description; // Descrição do filme
    public $image;       // Nome da imagem do filme
    public $trailer;     // Link do trailer
    public $category;    // Categoria do filme
    public $length;      // Duração do filme
    public $users_id;    // ID do usuário que cadastrou o filme

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