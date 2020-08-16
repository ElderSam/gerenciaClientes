<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Usuario
{
    public function list()
    {        
        $sql = new Sql();

        $query = "SELECT * FROM usuarios";
        $res = $sql->select($query);

        echo json_encode($res);
    }
}