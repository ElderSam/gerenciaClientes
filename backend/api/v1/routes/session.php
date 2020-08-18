<?php

//print_r($_REQUEST);

use \Classes\Usuario;

$c = new Usuario();

$url = explode('/', $requisicao['url']);

$method = $url[3];

if($method == "login"){
    return Usuario::login($_POST["login"], $_POST["senha"]); //autentifica usuário


}else if($method == "logout"){
    
    $_SESSION[Usuario::SESSION] = NULL;
    
    header("Location: http://localhost/login");
    exit;
}
