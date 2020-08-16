<?php

require __DIR__ . './../../../vendor/autoload.php';

use \Classes\Usuario;

$u = new Usuario();

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $url = explode('/', $_GET['url']);

    if(isset($url[3]))
    {
        $id = $url[3];
    }else{
        $id = false;
    }

    echo $u->list($id);

}else if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    echo "POST";

}else if($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    echo "PUT";

}else if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    echo "DELETE";
}