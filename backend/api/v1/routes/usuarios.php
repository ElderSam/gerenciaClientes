<?php

use \Classes\Usuario;

$u = new Usuario();

$url = explode('/', $_GET['url']);

if($_SERVER['REQUEST_METHOD'] == 'GET') /* Listar usuários --------------- */
{

    if(isset($url[3]))
    {
        $id = $url[3];
    }else{
        $id = false;
    }

    echo $u->list($id);

}else if($_SERVER['REQUEST_METHOD'] == 'POST') /* Cadastrar usuário --------------- */
{
    echo $u->create($_POST);

}else if($_SERVER['REQUEST_METHOD'] == 'PUT') /* Atualizar usuário --------------- */
{
    if(!isset($url[3]))
        exit(json_encode(array('status'=>'error', 'message'=>"Id don't passed by URL (GET)")));
    
    $id = $url[3];
    $data = urldecode(file_get_contents('php://input'));
    
    //echo $data;

    if(strpos($data, '=') !== false)
    {
        $allPairs = array();
        $data = explode('&', $data); //separa tudo que tiver entre & na string e inserre em um array
        
        foreach($data as $pair) {
            $pair = explode('=', $pair);
            $allPairs[$pair[0]] = $pair[1];
        }
    }

    echo $u->update($id, $allPairs);

}else if($_SERVER['REQUEST_METHOD'] == 'DELETE') /* Listar deletar --------------- */
{
    if(!isset($url[3]))
        exit(json_encode(array('status'=>'error', 'message'=>"Id don't passed by URL (GET)")));

    $id = $url[3];
    
    echo $u->delete($id);
}