<?php

  // Inclui arquivos globais (configurações gerais do sistema)
  require_once("globals.php");

  // Inclui a conexão com o banco de dados
  require_once("db.php");

  // Inclui o model da entidade User (usuário)
  require_once("models/User.php");

  // Inclui o model responsável por mensagens do sistema
  require_once("models/Message.php");

  // Inclui o DAO responsável pelas operações do usuário no banco
  require_once("dao/UserDAO.php");

  // Instancia o objeto de mensagens, passando a URL base
  $message = new Message($BASE_URL);

  // Instancia o DAO do usuário com a conexão e a URL base
  $userDao = new UserDAO($conn, $BASE_URL);

  // Resgata o tipo do formulário enviado via POST (login ou register)
  $type = filter_input(INPUT_POST, "type");

  // Verifica qual tipo de formulário foi enviado
  if($type === "register") {

    // Resgata os dados do formulário de cadastro
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Verifica se todos os campos obrigatórios foram preenchidos
    if($name && $lastname && $email && $password && $confirmpassword) {

      // Verifica se a senha e a confirmação de senha são iguais
      if($password === $confirmpassword) {

        // Verifica se o e-mail ainda não está cadastrado no sistema
        if($userDao->findByEmail($email) === false) {

          // Cria um novo objeto do tipo User
          $user = new User();

          // Gera um token de autenticação para o usuário
          $userToken = $user->generateToken();

          // Criptografa a senha informada
          $finalPassword = $user->generatePassword($password);

          // Atribui os valores ao objeto usuário
          $user->name = $name;
          $user->lastname = $lastname;
          $user->email = $email;
          $user->password = $finalPassword;
          $user->token = $userToken;

          // Define que o usuário será autenticado automaticamente após o cadastro
          $auth = true;

          // Cria o usuário no banco de dados
          $userDao->create($user, $auth);

        } else {
          
          // Caso o e-mail já exista, envia mensagem de erro
          $message->setMessage(
            "Usuário já cadastrado, tente outro e-mail.",
            "error",
            "back"
          );

        }

      } else {

        // Caso as senhas não coincidam, envia mensagem de erro
        $message->setMessage(
          "As senhas não são iguais.",
          "error",
          "back"
        );

      }

    } else {

      // Caso algum campo não tenha sido preenchido, envia mensagem de erro
      $message->setMessage(
        "Por favor, preencha todos os campos.",
        "error",
        "back"
      );

    }

  // Caso o formulário seja de login
  } else if($type === "login") {

    // Resgata os dados do formulário de login
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    // Tenta autenticar o usuário com e-mail e senha
    if($userDao->authenticateUser($email, $password)) {

      // Se autenticar com sucesso, envia mensagem e redireciona
      $message->setMessage(
        "Seja bem-vindo!",
        "success",
        "editprofile.php"
      );

    } else {

      // Caso falhe a autenticação, envia mensagem de erro
      $message->setMessage(
        "Usuário e/ou senha incorretos.",
        "error",
        "back"
      );

    }

  } else {

    // Caso o tipo de formulário seja inválido
    $message->setMessage(
      "Informações inválidas!",
      "error",
      "index.php"
    );

  }
