<?php

// Inclui o Model Review
require_once(__DIR__ . "/../models/Review.php");

// Classe responsável por manipular as reviews dos filmes
class ReviewDao {

    // Conexão com o banco de dados
    private $conn;

    // Construtor recebe a conexão PDO
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // =====================================
    // Monta o objeto Review com dados do banco
    // =====================================
    public function buildReview($data) {

        $reviewObject = new Review();

        // Dados da review
        $reviewObject->id        = $data["id"];
        $reviewObject->rating    = $data["rating"];
        $reviewObject->review    = $data["review"];
        $reviewObject->users_id  = $data["users_id"];
        $reviewObject->movies_id = $data["movies_id"];

        // Dados do usuário (vindos do JOIN)
        $reviewObject->user_name     = $data["name"] ?? null;
        $reviewObject->user_lastname = $data["lastname"] ?? null;
        $reviewObject->user_image    = $data["image"] ?? null;

        return $reviewObject;
    }

    // =====================================
    // Cria uma nova review
    // =====================================
    public function create(Review $review) {

        // Impede que o mesmo usuário avalie o mesmo filme mais de uma vez
        if($this->hasAlreadyReviewed($review->movies_id, $review->users_id)) {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO reviews (rating, review, users_id, movies_id)
            VALUES (:rating, :review, :users_id, :movies_id)
        ");

        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":users_id", $review->users_id);
        $stmt->bindParam(":movies_id", $review->movies_id);

        $stmt->execute();

        return true;
    }

    // =====================================
    // Busca todas as reviews de um filme
    // =====================================
    public function getMoviesReview($id) {

        $reviews = [];

        // Busca as reviews junto com o nome do usuário
        $stmt = $this->conn->prepare("
           SELECT reviews.*, users.name, users.lastname, users.image
            FROM reviews
            JOIN users ON users.id = reviews.users_id
            WHERE reviews.movies_id = :movies_id
            ORDER BY reviews.id DESC
        ");

        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $review) {
            $reviews[] = $this->buildReview($review);
        }

        return $reviews;
    }

    // =====================================
    // Verifica se o usuário já avaliou o filme
    // =====================================
    public function hasAlreadyReviewed($id, $userId) {

        $stmt = $this->conn->prepare("
            SELECT id 
            FROM reviews 
            WHERE movies_id = :movies_id 
            AND users_id = :user_id
        ");

        $stmt->bindParam(":movies_id", $id);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // =====================================
    // Calcula a média das avaliações de um filme
    // =====================================
    public function getRatings($id) {

        $stmt = $this->conn->prepare("
            SELECT rating
            FROM reviews
            WHERE movies_id = :movies_id
        ");

        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {

            $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = 0;

            foreach($ratings as $item) {
                $total += $item["rating"];
            }

            return round($total / count($ratings), 1);
        }

        return 0;
    }
}