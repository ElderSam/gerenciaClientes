<?php

require __DIR__ . "/DB/Sql.php";

class Usuario
{
    public function list()
    {        
        
        echo json_encode(['method'=>'GET']);
    }
}