<?php

// Importa o Model Review
require_once(__DIR__ . "/../models/Review.php");

class ReviewDao {

    // Conexão com o banco (PDO)
    private $conn;

    // Construtor recebe a conexão PDO ao criar o DAO
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // Monta um objeto Review a partir de um array do banco
    public function buildReview($data) {
        $reviewObject = new Review();

        // Preenche os atributos do objeto com os dados do banco
        $reviewObject->id = $data["id"];
        $reviewObject->rating = $data["rating"]; // Nota do filme
        $reviewObject->review = $data["review"]; // Texto da avaliação
        $reviewObject->users_id = $data["users_id"]; // ID do usuário que avaliou
        $reviewObject->movies_id = $data["movies_id"]; // ID do filme avaliado

        return $reviewObject;
    }

    // Cria uma nova avaliação
    public function create(Review $review) {

        // Evita que o mesmo usuário avalie o mesmo filme duas vezes
        if ($this->hasAlreadyReviewed($review->movies_id, $review->users_id)) {
            return false; // Já avaliou, não insere
        }

        // Prepara a query para inserir a avaliação
        $stmt = $this->conn->prepare("
            INSERT INTO reviews (rating, review, users_id, movies_id)
            VALUES (:rating, :review, :users_id, :movies_id)
        ");

        // Associa os valores do objeto Review aos parâmetros da query
        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":users_id", $review->users_id);
        $stmt->bindParam(":movies_id", $review->movies_id);

        // Executa a inserção
        $stmt->execute();

        return true;
    }

    // Retorna todas as avaliações de um filme
    public function getMoviesReview($id) {

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

        // Associa o ID do filme
        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();

        $reviews = [];

        // Converte cada registro em objeto Review
        foreach ($stmt->fetchAll() as $data) {
            $reviews[] = $this->buildReview($data);
        }

        return $reviews;
    }

    // Verifica se o usuário já avaliou determinado filme
    public function hasAlreadyReviewed($movieId, $userId) {

        $stmt = $this->conn->prepare("
            SELECT id FROM reviews 
            WHERE movies_id = :movies_id AND users_id = :user_id
        ");

        // Associa os parâmetros
        $stmt->bindParam(":movies_id", $movieId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        // Retorna true se já houver avaliação
        return $stmt->rowCount() > 0;
    }

    // Calcula a média das avaliações de um filme
    public function getRatings($id) {

        $stmt = $this->conn->prepare("
            SELECT rating  
            FROM reviews 
            WHERE movies_id = :id
        ");

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // Se houver avaliações
        if ($stmt->rowCount() > 0) {

            $ratings = $stmt->fetchAll();
            $total = 0;

            // Soma todas as notas
            foreach ($ratings as $item) {
                $total += $item["rating"];
            }

            // Calcula a média
            $media = $total / count($ratings);

            // Retorna a média arredondada
            return round($media, 1);
        }

        // Caso não haja avaliações, retorna 0
        return 0;
    }
}