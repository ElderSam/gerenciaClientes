<?php

//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Endereco
{
    /* Atributos */
    private $id;
    private $idCliente;
    private $CEP;
    private $logradouro;
    private $numero;
    private $bairro;
    private $complemento;
    private $idMunicipio;
    private $dtCadastro;

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
     * Get the value of idCliente
     */ 
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * Set the value of idCliente
     *
     * @return  self
     */ 
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;

        return $this;
    }

    /**
     * Get the value of CEP
     */ 
    public function getCEP()
    {
        return $this->CEP;
    }

    /**
     * Set the value of CEP
     *
     * @return  self
     */ 
    public function setCEP($CEP)
    {
        $this->CEP = $CEP;

        return $this;
    }

    /**
     * Get the value of logradouro
     */ 
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set the value of logradouro
     *
     * @return  self
     */ 
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of bairro
     */ 
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set the value of bairro
     *
     * @return  self
     */ 
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * Get the value of complemento
     */ 
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set the value of complemento
     *
     * @return  self
     */ 
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Get the value of idMunicipio
     */ 
    public function getIdMunicipio()
    {
        return $this->idMunicipio;
    }

    /**
     * Set the value of idMunicipio
     *
     * @return  self
     */ 
    public function setIdMunicipio($idMunicipio)
    {
        $this->idMunicipio = $idMunicipio;

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
        $this->setIdCliente($data['idCliente']);
        $this->setCEP($data['CEP']);
        $this->setLogradouro($data['logradouro']);
        $this->setNumero($data['numero']);
        $this->setBairro($data['bairro']);
        $this->setComplemento($data['complemento']);
        $this->setIdMunicipio($data['idMunicipio']);
    }
    
    /* Listar os endereços */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT * FROM enderecos";

        if($id) //se foi passado o id
        {
            $query .= " WHERE id = $id"; //lista apenas um endereço, através do id
        }

        $res = $sql->select($query);
        
        echo json_encode($res); //response
    }

    /* Inserir endereços */
    public function create($data)
    {
        $sql = new Sql();

        $this->setAll($data);
        
        $res = $sql->select("CALL sp_enderecos_save(:idCliente, :CEP, :logradouro, :numero, :bairro, :complemento, :idMunicipio)", array(
            ":idCliente"=>$this->getIdCliente(),
            ":CEP"=>$this->getCEP(),
            ":logradouro"=>$this->getLogradouro(),
            ":numero"=>$this->getNumero(),
            ":bairro"=>$this->getBairro(),
            ":complemento"=>$this->getComplemento(),
            ":idMunicipio"=>$this->getIdMunicipio()
        )); //usa a procedure do banco de dados para cadastrar endereço (INSERT)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>'check your inputs!'
            );
        }

        echo json_encode($res);

    }

    /* Atualizar endereço */
    public function update($id, $data)
    {
        $this->setId($id);
        $this->setAll($data);

        $sql = new Sql();

        $res = $sql->select("CALL sp_enderecos_update(:id, :idCliente, :CEP, :logradouro, :numero, :bairro, :complemento, :idMunicipio)", array(
            ":id"=>$this->getId(),
            ":idCliente"=>$this->getIdCliente(),
            ":CEP"=>$this->getCEP(),
            ":logradouro"=>$this->getLogradouro(),
            ":numero"=>$this->getNumero(),
            ":bairro"=>$this->getBairro(),
            ":complemento"=>$this->getComplemento(),
            ":idMunicipio"=>$this->getIdMunicipio()
        )); //usa a procedure do banco de dados para atualizar endereço (UPDATE)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>"Don't updated!"
            );
        }

        echo json_encode($res);
    }

    public function delete($id)
    {
        $this->setId($id);

        $sql = new Sql();

        $res = $sql->select("CALL sp_enderecos_delete(:id)", array(
            ":id"=>$this->getId()
        )); //usa a procedure do banco de dados para excluir endereço (DELETE)

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