<?php

require_once("models/User.php");
require_once("models/Message.php");

// DAO responsável por gerenciar os usuários no banco
class UserDAO implements UserDAOInterface {

    private $conn;    // Conexão PDO com o banco de dados
    private $url;     // URL base para redirecionamentos
    private $message; // Objeto para enviar mensagens para o usuário

    // Construtor recebe a conexão e a URL base
    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    // Constrói um objeto User a partir de um array do banco
    public function buildUser($data) {
        $user = new User();

        // Preenche propriedades do objeto
        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;
    }

    // Cria um novo usuário no banco
    public function create(User $user, $authUser = false) {
        $stmt = $this->conn->prepare("
            INSERT INTO users(name, lastname, email, password, token) 
            VALUES (:name, :lastname, :email, :password, :token)
        ");

        // Associa valores do objeto User aos parâmetros da query
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":password", $user->password);
        $stmt->bindParam(":token", $user->token);

        $stmt->execute();

        // Autentica o usuário imediatamente se authUser for true
        if ($authUser) {
            $this->setTokenToSession($user->token);
        }
    }

    // Atualiza dados do usuário
    public function update(User $user, $redirect = true) {
        $stmt = $this->conn->prepare("
            UPDATE users SET
                name = :name,
                lastname = :lastname,
                email = :email,
                image = :image,
                bio = :bio,
                token = :token
            WHERE id = :id
        ");

        // Associa os valores do objeto User
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":image", $user->image);
        $stmt->bindParam(":bio", $user->bio);
        $stmt->bindParam(":token", $user->token);
        $stmt->bindParam(":id", $user->id);

        $stmt->execute();

        // Redireciona e mostra mensagem caso $redirect seja true
        if ($redirect) {
            $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
        }
    }

    // Verifica se o token de sessão é válido
    public function verifyToken($protected = false) {
        if (!empty($_SESSION["token"])) {
            $token = $_SESSION["token"]; // Pega token da sessão
            $user = $this->findByToken($token);

            if ($user) {
                return $user; // Token válido
            } else if ($protected) {
                // Redireciona se o usuário não estiver autenticado
                $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
            }
        } else if ($protected) {
            // Redireciona se não houver token e página protegida
            $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
        }
    }

    // Salva token na sessão e opcionalmente redireciona
    public function setTokenToSession($token, $redirect = true) {
        $_SESSION["token"] = $token;

        if ($redirect) {
            $this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
        }
    }

    // Autentica usuário pelo email e senha
    public function authenticateUser($email, $password) {
        $user = $this->findByEmail($email); // Busca usuário pelo email

        if ($user) {
            // Verifica senha
            if (password_verify($password, $user->password)) {
                $token = $user->generateToken(); // Gera token
                $this->setTokenToSession($token, false); // Salva token na sessão

                $user->token = $token; 
                $this->update($user, false); // Atualiza token no banco

                return true; // Autenticado com sucesso
            } else {
                return false; // Senha incorreta
            }
        } else {
            return false; // Usuário não encontrado
        }
    }

    // Busca usuário pelo email
    public function findByEmail($email) {
        if ($email != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false; // Não encontrou usuário
    }

    // Busca usuário pelo ID
    public function findById($id) {
        if ($id != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false; // Não encontrou usuário
    }

    // Busca usuário pelo token
    public function findByToken($token) {
        if ($token != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
            $stmt->bindParam(":token", $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false; // Token inválido
    }

    // Remove token da sessão (logout)
    public function destroyToken() {
        $_SESSION["token"] = "";
        $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
    }

    // Altera senha do usuário
    public function changePassword(User $user) {
        $stmt = $this->conn->prepare("
            UPDATE users SET password = :password WHERE id = :id
        ");

        $stmt->bindParam(":password", $user->password);
        $stmt->bindParam(":id", $user->id);

        $stmt->execute();

        $this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
    }

}