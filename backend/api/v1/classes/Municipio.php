<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Municipio
{
    /* Atributos */
    private $id;
    private $descMunicipio;
    private $idEstado; //foreign key (FK)
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
     * Get the value of descMunicipio
     */ 
    public function getDescMunicipio()
    {
        return $this->descMunicipio;
    }

    /**
     * Set the value of descMunicipio
     *
     * @return  self
     */ 
    public function setDescMunicipio($descMunicipio)
    {
        $this->descMunicipio = $descMunicipio;

        return $this;
    }

    /**
     * Get the value of idEstado
     */ 
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * Set the value of idEstado
     *
     * @return  self
     */ 
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;

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
        $this->setDescMunicipio($data['descMunicipio']);
        $this->setIdEstado($data['idEstado']);
    }
    
    /* Listar os Municipios */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT * FROM municipios";

        if($id) //se foi passado o id
        {
            $query .= " WHERE id = $id"; //lista apenas um Municipio, através do id
        }

        $res = $sql->select($query);
        
        echo json_encode($res); //response
    }

    /* Inserir Municipios */
    public function create($data)
    {
        $sql = new Sql();

        $this->setAll($data);
        
        $res = $sql->select("CALL sp_municipios_save(:descMunicipio, :idEstado)", array(
            ":descMunicipio"=>$this->getDescMunicipio(),
            ":idEstado"=>$this->getIdEstado()
        )); //usa a procedure do banco de dados para cadastrar Municipio (INSERT)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>'check your inputs!'
            );
        }

        echo json_encode($res);

    }

    /* Atualizar Municipio */
    public function update($id, $data)
    {
        $this->setId($id);
        $this->setAll($data);

        $sql = new Sql();

        $res = $sql->select("CALL sp_municipios_update(:id, :descMunicipio, :idEstado)", array(
            ":id"=>$this->getId(),
            ":descMunicipio"=>$this->getDescMunicipio(),
            ":idEstado"=>$this->getIdEstado()
        )); //usa a procedure do banco de dados para atualizar Municipio (UPDATE)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>"Don't updated!"
            );
        }

        echo json_encode($res);
    }

    /* Excluir Municipio */
    public function delete($id)
    {
        $this->setId($id);

        $sql = new Sql();

        $res = $sql->select("CALL sp_municipios_delete(:id)", array(
            ":id"=>$this->getId()
        )); //usa a procedure do banco de dados para excluir Municipio (DELETE)

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