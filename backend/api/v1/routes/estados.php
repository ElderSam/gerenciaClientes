<?php

use \Classes\Estado;

$c = new Estado();

$url = explode('/', $_GET['url']);

if($_SERVER['REQUEST_METHOD'] == 'GET') /* Listar Estados --------------- */
{

    if(isset($url[3]))
    {
        $id = $url[3];
    }else{
        $id = false;
    }

    echo $c->list($id);

}else if($_SERVER['REQUEST_METHOD'] == 'POST') /* Cadastrar Estado --------------- */
{
    echo $c->create($_POST);

}else if($_SERVER['REQUEST_METHOD'] == 'PUT') /* Atualizar Estado --------------- */
{
    if(!isset($url[3]))
        exit(json_encode(array('status'=>'error', 'message'=>"Id don't passed by URL (GET)")));
    
    $id = $url[3];
    $data = urldecode(file_get_contents('php://input'));

    if(strpos($data, '=') !== false)
    {
        $allPairs = array();
        $data = explode('&', $data); //separa tudo que tiver entre & na string e inserre em um array
        
        foreach($data as $pair) {
            $pair = explode('=', $pair);
            $allPairs[$pair[0]] = $pair[1];
        }
    }

    echo $c->update($id, $allPairs);

}else if($_SERVER['REQUEST_METHOD'] == 'DELETE') /* Excluir Estado --------------- */
{
    if(!isset($url[3]))
        exit(json_encode(array('status'=>'error', 'message'=>"Id don't passed by URL (GET)")));

    $id = $url[3];
    
    echo $c->delete($id);
}