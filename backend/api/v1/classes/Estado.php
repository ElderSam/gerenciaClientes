<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Estado
{
    /* Atributos */
    private $id;
    private $descEstado;
    private $sigla;
    //private $dtCadastro;

    /* Getters and Setters ------------------------- */

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of descEstado
     */ 
    public function getDescEstado()
    {
        return $this->descEstado;
    }

    /**
     * Set the value of descEstado
     *
     * @return  self
     */ 
    public function setDescEstado($descEstado)
    {
        $this->descEstado = $descEstado;

        return $this;
    }

    /**
     * Get the value of sigla
     */ 
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set the value of sigla
     *
     * @return  self
     */ 
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get the value of dtCadastro
     */ 
    public function getDtCadastro()
    {
        return $this->dtCadastro;
    }

    /**
     * Set the value of dtCadastro
     *
     * @return  self
     */ 
    public function setDtCadastro($dtCadastro)
    {
        $this->dtCadastro = $dtCadastro;

        return $this;
    }

    /* Methods ----------------------------------- */
    
    /* insere o valor de todos os Atributos */
    public function setAll($data)
    {
        $this->setDescEstado($data['descEstado']);
        $this->setSigla($data['sigla']);
    }
    
    /* Listar os Estados */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT * FROM estados";

        if($id) //se foi passado o id
        {
            $query .= " WHERE id = $id"; //lista apenas um Estado, através do id
        }

        $res = $sql->select($query);
        
        echo json_encode($res); //response
    }

    /* Inserir Estados */
    public function create($data)
    {
        $sql = new Sql();

        $this->setAll($data);
        
        $res = $sql->select("CALL sp_estados_save(:descEstado, :sigla)", array(
            ":descEstado"=>$this->getDescEstado(),
            ":sigla"=>$this->getSigla()
        )); //usa a procedure do banco de dados para cadastrar Estado (INSERT)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>'check your inputs!'
            );
        }

        echo json_encode($res);

    }

    /* Atualizar Estado */
    public function update($id, $data)
    {
        $this->setId($id);
        $this->setAll($data);

        $sql = new Sql();

        $res = $sql->select("CALL sp_estados_update(:id, :descEstado, :sigla)", array(
            ":id"=>$this->getId(),
            ":descEstado"=>$this->getDescEstado(),
            ":sigla"=>$this->getSigla()
        )); //usa a procedure do banco de dados para atualizar Estado (UPDATE)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>"Don't updated!"
            );
        }

        echo json_encode($res);
    }

    /* Excluir Estado */
    public function delete($id)
    {
        $this->setId($id);

        $sql = new Sql();

        $res = $sql->select("CALL sp_estados_delete(:id)", array(
            ":id"=>$this->getId()
        )); //usa a procedure do banco de dados para excluir Estado (DELETE)

        if(count($res) == 0) //se não retornou nada, então excluiu com sucesso
        {
            $status = 'success';
            $message = 'Data deleted!';
        }else
        {
            $status = 'error';
            $message = 'Unable to delete!';
        }

        echo json_encode(array(
            'status'=>$status,
            'message'=>$message
        ));

    }

}