<?php

// Importa as classes necessárias
require_once("models/User.php");
require_once("models/Message.php");

// Classe responsável por acessar o banco e manipular usuários
class UserDAO implements UserDAOInterface {

  private $conn;     // Conexão com o banco (PDO)
  private $url;      // URL base do sistema
  private $message;  // Classe para mensagens e redirecionamentos

  // Construtor recebe a conexão e a URL base
  public function __construct(PDO $conn, $url) {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }

  // Monta e retorna um objeto User a partir dos dados do banco
  public function buildUser($data) {

    $user = new User();
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

    $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)");

    $stmt->bindParam(":name", $user->name);
    $stmt->bindParam(":lastname", $user->lastname);
    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":token", $user->token);

    // Executa o INSERT no banco
    $stmt->execute();

    // Autentica o usuário automaticamente se $authUser for true
    if($authUser) {
      $this->setTokenToSession($user->token);
    }

  }

  // Atualiza os dados do usuário
  public function update(User $user, $redirect = true) {

    $stmt = $this->conn->prepare("UPDATE users SET
      name = :name,
      lastname = :lastname,
      email = :email,
      image = :image,
      bio = :bio,
      token = :token
      WHERE id = :id
    ");

    $stmt->bindParam(":name", $user->name);
    $stmt->bindParam(":lastname", $user->lastname);
    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":image", $user->image);
    $stmt->bindParam(":bio", $user->bio);
    $stmt->bindParam(":token", $user->token);
    $stmt->bindParam(":id", $user->id);

    // Executa o UPDATE no banco
    $stmt->execute();

    // Redireciona se $redirect for true
    if($redirect) {

      // Mostra mensagem de sucesso e redireciona
      $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");

    }

  }

  // Verifica se existe um usuário autenticado via token
  public function verifyToken($protected = false) {

    if(!empty($_SESSION["token"])) {

      // Recupera o token da sessão
      $token = $_SESSION["token"];

      // Busca o usuário pelo token
      $user = $this->findByToken($token);

      if($user) {
        return $user;
      } else if($protected) {

        // Redireciona se a página for protegida
        $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");

      }

    } else if($protected) {

      // Caso não exista token na sessão
      $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");

    }

  }

  // Salva o token do usuário na sessão
  public function setTokenToSession($token, $redirect = true) {

    // Armazena o token na sessão
    $_SESSION["token"] = $token;

    if($redirect) {

      // Mensagem de boas-vindas
      $this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");

    }

  }

  // Autentica o usuário com email e senha
  public function authenticateUser($email, $password) {

    // Busca usuário pelo email
    $user = $this->findByEmail($email);

    if($user) {

      // Verifica se a senha digitada confere com a do banco
      if(password_verify($password, $user->password)) {

        // Gera um novo token
        $token = $user->generateToken();

        // Salva o token na sessão (sem redirecionar)
        $this->setTokenToSession($token, false);

        // Atualiza o token no banco
        $user->token = $token;
        $this->update($user, false);

        return true;

      } else {
        return false;
      }

    } else {

      return false;

    }

  }

  // Busca um usuário pelo email
  public function findByEmail($email) {

    if($email != "") {

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);

        return $user;

      } else {
        return false;
      }

    } else {
      return false;
    }

  }

  // Busca um usuário pelo ID
  public function findById($id) {

    if($id != "") {

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);

        return $user;

      } else {
        return false;
      }

    } else {
      return false;
    }
  }

  // Busca um usuário pelo token
  public function findByToken($token) {

    if($token != "") {

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
      $stmt->bindParam(":token", $token);
      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);

        return $user;

      } else {
        return false;
      }

    } else {
      return false;
    }

  }

  // Remove o token da sessão (logout)
  public function destroyToken() {

    // Limpa o token da sessão
    $_SESSION["token"] = "";

    // Redireciona com mensagem de sucesso
    $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");

  }

  // Altera a senha do usuário
  public function changePassword(User $user) {

    $stmt = $this->conn->prepare("UPDATE users SET
      password = :password
      WHERE id = :id
    ");

    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":id", $user->id);

    // Executa a atualização da senha
    $stmt->execute();

    // Mensagem de sucesso
    $this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");

  }

}