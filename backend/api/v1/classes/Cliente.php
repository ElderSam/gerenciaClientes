<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Cliente
{
    /* Atributos */
    private $id;
    private $nome;
    private $dtNasc;
    private $CPF;
    private $RG;
    private $telefone;
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
     * Get the value of nome
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of dtNasc
     */ 
    public function getDtNasc()
    {
        return $this->dtNasc;
    }

    /**
     * Set the value of dtNasc
     *
     * @return  self
     */ 
    public function setDtNasc($dtNasc)
    {
        $this->dtNasc = $dtNasc;

        return $this;
    }

    /**
     * Get the value of CPF
     */ 
    public function getCPF()
    {
        return $this->CPF;
    }

    /**
     * Set the value of CPF
     *
     * @return  self
     */ 
    public function setCPF($CPF)
    {
        $this->CPF = $CPF;

        return $this;
    }

    /**
     * Get the value of RG
     */ 
    public function getRG()
    {
        return $this->RG;
    }

    /**
     * Set the value of RG
     *
     * @return  self
     */ 
    public function setRG($RG)
    {
        $this->RG = $RG;

        return $this;
    }

    /**
     * Get the value of telefone
     */ 
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set the value of telefone
     *
     * @return  self
     */ 
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

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
        $this->setNome($data['nome']);
        $this->setDtNasc($data['dtNasc']);
        $this->setCPF($data['CPF']);
        $this->setRG($data['RG']);
        $this->setTelefone($data['telefone']);
    }
    
    /* Listar os clientes */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT * FROM clientes";

        if($id) //se foi passado o id
        {
            $query .= " WHERE id = $id"; //lista apenas um cliente, através do id
        }

        $res = $sql->select($query);
        
        echo json_encode($res); //response
    }

    /* Inserir clientes */
    public function create($data)
    {
        $sql = new Sql();

        $this->setAll($data);
        
        $res = $sql->select("CALL sp_clientes_save(:nome, :dtNasc, :CPF, :RG, :telefone)", array(
            ":nome"=>$this->getNome(),
            ":dtNasc"=>$this->getDtNasc(),
            ":CPF"=>$this->getCPF(),
            ":RG"=>$this->getRG(),
            ":telefone"=>$this->getTelefone()
        )); //usa a procedure do banco de dados para cadastrar cliente (INSERT)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>'check your inputs!'
            );
        }

        echo json_encode($res);

    }

    /* Atualizar cliente */
    public function update($id, $data)
    {
        $this->setId($id);
        $this->setAll($data);

        $sql = new Sql();

        $res = $sql->select("CALL sp_clientes_update(:id, :nome, :dtNasc, :CPF, :RG, :telefone)", array(
            ":id"=>$this->getId(),
            ":nome"=>$this->getNome(),
            ":dtNasc"=>$this->getDtNasc(),
            ":CPF"=>$this->getCPF(),
            ":RG"=>$this->getRG(),
            ":telefone"=>$this->getTelefone()
        )); //usa a procedure do banco de dados para atualizar cliente (UPDATE)

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

        $res = $sql->select("CALL sp_clientes_delete(:id)", array(
            ":id"=>$this->getId()
        )); //usa a procedure do banco de dados para excluir cliente (DELETE)

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