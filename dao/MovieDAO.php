<?php

// Importa o Model Movie
require_once(__DIR__ . "/../models/Movie.php");

// Classe responsável por acessar a tabela "movies"
class MovieDAO {

    // Conexão com o banco de dados
    private $conn;

    // Recebe a conexão PDO no momento da criação
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // Converte um array do banco em um objeto Movie
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

    // Retorna todos os filmes
    public function findAll() {

        $stmt = $this->conn->query("SELECT * FROM movies ");

        $movie = [];

        foreach ($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    // Retorna os 10 filmes mais recentes
    public function getLatestMovies() {

        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC LIMIT 10");

        $movie = [];

        foreach ($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    // Busca filmes por categoria (usa prepared statement)
    public function getMoviesByCategory($category) {

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category");

        $stmt->bindParam(":category", $category);
        $stmt->execute();

        $movie = [];

        foreach ($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    // Busca filmes pelo ID do usuário
    public function getMoviesByUserId($id) {

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE users_id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $movie = [];

        foreach ($stmt->fetchAll() as $data) {
            $movie[] = $this->buildMovie($data);
        }

        return $movie;
    }

    // Busca um filme específico pelo ID
    public function findById($id) {

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $this->buildMovie($stmt->fetch());
        }

        return false;
    }

    // Busca filmes pelo título (busca parcial com LIKE)
    public function findByTitle($title) {

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title LIKE :title");

        $title = "%" . $title . "%";

        $stmt->bindParam(":title", $title);
        $stmt->execute();

        $movies = [];

        foreach ($stmt->fetchAll() as $item) {
            $movies[] = $this->buildMovie($item);
        }

        return $movies;
    }

    // Insere um novo filme no banco
    public function create(Movie $movie) {

        $stmt = $this->conn->prepare("
            INSERT INTO movies (title, description, image, trailer, category, length, users_id)
            VALUES (:title, :description, :image, :trailer, :category, :length, :users_id)
        ");

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":users_id", $movie->users_id);

        $stmt->execute();
    }

    // Atualiza um filme existente
    public function update(Movie $movie) {

        $stmt = $this->conn->prepare("
            UPDATE movies SET 
                title = :title,
                description = :description,
                image = :image,
                trailer = :trailer,
                category = :category,
                length = :length
            WHERE id = :id
        ");

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":id", $movie->id);

        $stmt->execute();
    }

public function destroy($id) {

<<<<<<< HEAD
        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
        
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
    }
=======
    // Apaga as reviews do filme
    $stmt = $this->conn->prepare("DELETE FROM reviews WHERE movies_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    // Apaga o filme
    $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}
>>>>>>> 1b5e252fa405fc9405532c030444a916814c2cb7
}