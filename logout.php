<?php

// Inclui o cabeçalho do sistema (HTML inicial, sessões e configurações globais)
require_once("templates/header.php");

// Se o DAO de usuário existe, desloga o usuário destruindo seu token
if($userDao) {
  $userDao->destroyToken();
}
