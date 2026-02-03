<?php

require_once(__DIR__ . "/../models/Movie.php");

class MovieDAO implements MovieDAOInterface {

    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function buildMovie($data) {
        $movie = new Movie();
        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->users_id = $data["users_id"];
        return $movie;
    }

    public function findAll() {
        // Retorna todos os filmes
    }

    public function getLatestMovies() {
        // Retorna os filmes mais recentes
    }

    public function getMoviesByCategory($category) {
        // Retorna filmes filtrados por categoria
    }

    public function getMoviesByUserId($id) {
        // Retorna filmes cadastrados por um usuário específico
    }

    public function findById($id) {
        // Retorna um filme pelo ID
    }

    public function findByTitle($title) {
        // Retorna filmes cujo título contenha a string informada
    }

    public function create(Movie $movie) {
        // Adiciona um novo filme
    }

    public function update(Movie $movie) {
        // Atualiza os dados de um filme existente
    }

    public function destroy($id) {
        // Remove um filme do sistema
    }
}
