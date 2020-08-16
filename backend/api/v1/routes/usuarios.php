<?php

require __DIR__ . '/../../vendor/autoload.php';

use Classes\Usuario;

$u = new Usuario();

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    echo $u->list();

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