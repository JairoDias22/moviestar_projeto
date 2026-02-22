<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar usuário
if($type === "update") {

  // Resgata dados do usuário
  $userData = $userDao->verifyToken();
  // salvar dados antigos para comparação
  $oldName = $userData->name;
  $oldLastname = $userData->lastname;
  $oldEmail = $userData->email;
  $oldBio = $userData->bio;
  $oldImage = $userData->image;

  // Receber dados do post
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  // VERIFICAR SE EMAIL JÁ EXISTE
  $userByEmail = $userDao->findByEmail($email);

  if($userByEmail && $userByEmail->id != $userData->id){
    $message->setMessage("E-mail já está em uso!", "error", "back");
    exit;
  }

  // verificar se imagem foi enviada
  $imageChanged = (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"]));

  // verificar se nada mudou
  if(
    $name === $oldName &&
    $lastname === $oldLastname &&
    $email === $oldEmail &&
    $bio === $oldBio &&
    !$imageChanged
  ){
    $message->setMessage("Nenhum dado foi alterado!", "error", "back");
    exit;
  }

  // Atualizar dados
  if(empty($name) || empty($lastname) || empty($email)){

    $message->setMessage("Preencha nome, sobrenome e email!", "error", "back");

  } else {

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

  }
  // Upload da imagem
  if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    // Checagem de tipo de imagem
    if(in_array($image["type"], $imageTypes)) {

      // JPG
      if(in_array($image["type"], $jpgArray)) {
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
      } 
      // PNG
      else {
        $imageFile = imagecreatefrompng($image["tmp_name"]);
      }

      // Gerar nome único
      $user = new User();
      $imageName = $user->imageGenerateName();

      // Salvar imagem
      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      // Salvar no usuário
      $userData->image = $imageName;

    } else {
      $message->setMessage("Tipo inválido de imagem, use JPG ou PNG!", "error", "back");
       exit;
    }
  }

  $userDao->update($userData);
  $message->setMessage("Perfil atualizado com sucesso!", "success", "editprofile.php");


// Atualizar senha
} else if($type === "changepassword") {

  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  $userData = $userDao->verifyToken();
  $id = $userData->id;

  if(!empty($password) && $password === $confirmpassword) {

    $user = new User();
    $finalPassword = $user->generatePassword($password);

    $user->password = $finalPassword;
    $user->id = $id;

    $userDao->changePassword($user);

    $message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");

  } else {
    $message->setMessage("As senhas não são iguais!", "error", "back");
  }

} else {
  $message->setMessage("Informações inválidas!", "error", "index.php");
}
