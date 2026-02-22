<?php

// Classe que representa um usuário
class User {

    public $id;       // ID do usuário
    public $name;     // Nome
    public $lastname; // Sobrenome
    public $email;    // E-mail
    public $password; // Senha (hash)
    public $image;    // Nome da imagem de perfil
    public $bio;      // Biografia do usuário
    public $token;    // Token de autenticação

    // Retorna o nome completo do usuário
    public function getFullName($user) {
        return $user->name . " " . $user->lastname;
    }

    // Gera um token aleatório para autenticação
    public function generateToken() {
        return bin2hex(random_bytes(50));
    }

    // Cria hash seguro para senha
    public function generatePassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Gera um nome aleatório para a imagem de perfil
    public function imageGenerateName() {
        return bin2hex(random_bytes(60)) . ".jpg";
    }
}

// Interface que define os métodos obrigatórios do UserDAO
interface UserDAOInterface {

    public function buildUser($data);                   // Monta objeto User a partir de dados do banco
    public function create(User $user, $authUser = false); // Cria usuário no banco e autentica se necessário
    public function update(User $user, $redirect = true); // Atualiza dados do usuário
    public function verifyToken($protected = false);     // Verifica se o token da sessão é válido
    public function setTokenToSession($token, $redirect = true); // Salva token na sessão
    public function authenticateUser($email, $password); // Autentica usuário pelo email e senha
    public function findByEmail($email);                 // Busca usuário pelo email
    public function findById($id);                       // Busca usuário pelo ID
    public function findByToken($token);                 // Busca usuário pelo token
    public function destroyToken();                      // Remove token da sessão (logout)
    public function changePassword(User $user);          // Altera senha do usuário

}