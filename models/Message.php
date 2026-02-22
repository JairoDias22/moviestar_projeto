<?php

// Classe responsável por exibir mensagens para o usuário
class Message {

    private $url; // URL base para redirecionamentos

    public function __construct($url) {
        $this->url = $url;
    }

    // Define uma mensagem de feedback e faz redirecionamento
    // $msg = texto da mensagem
    // $type = tipo da mensagem (success, error, info...)
    // $redirect = página para redirecionar ou "back" para voltar à página anterior
    public function setMessage($msg, $type, $redirect = "index.php") {

        $_SESSION["msg"] = $msg;   // Armazena mensagem na sessão
        $_SESSION["type"] = $type; // Armazena tipo da mensagem

        // Redireciona para página desejada
        if ($redirect != "back") {
            header("Location: $this->url" . $redirect);
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]); // Volta à página anterior
        }
    }

    // Retorna a mensagem atual armazenada na sessão
    public function getMessage() {
        if (!empty($_SESSION["msg"])) {
            return [
                "msg" => $_SESSION["msg"],
                "type" => $_SESSION["type"]
            ];
        } else {
            return false; // Nenhuma mensagem disponível
        }
    }

    // Limpa a mensagem da sessão
    public function clearMessage() {
        $_SESSION["msg"] = "";
        $_SESSION["type"] = "";
    }

}