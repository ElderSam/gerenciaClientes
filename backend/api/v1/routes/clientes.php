<?php

use \Classes\Cliente;

$c = new Cliente();

$url = explode('/', $_GET['url']);

if($_SERVER['REQUEST_METHOD'] == 'GET') /* Listar clientes --------------- */
{

    if(isset($url[3])) //listar por id
    {
        $id = $url[3];
    }else{
        $id = false; //listar todos
    }

    echo $c->list($id);

}else if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if($url[3] == "list_datatables")
    {
        //Receber a requisão da pesquisa 
        $requestData = $_REQUEST;

        $clientes = new Cliente();
        echo $clientes->ajax_list_clientes($requestData);  
   
    }else{

        echo $c->create($_POST); /* Cadastrar cliente --------------- */
    }
    

}else if($_SERVER['REQUEST_METHOD'] == 'PUT') /* Atualizar cliente --------------- */
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

    echo $c->update($id, $allPairs);

}else if($_SERVER['REQUEST_METHOD'] == 'DELETE') /* Excluir cliente --------------- */
{
    if(!isset($url[3]))
        exit(json_encode(array('status'=>'error', 'message'=>"Id don't passed by URL (GET)")));

    $id = $url[3];
    
    echo $c->delete($id);
}