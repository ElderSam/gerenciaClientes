<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Usuario
{
    /* Atributos */
    private $id;
    private $nome;
    private $email;
    private $login;
    private $senha;
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
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login
     *
     * @return  self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of senha
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /* Methods ----------------------------------- */
    /* lista todos os usuários */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT id, nome, email, login, dtCadastro FROM usuarios";

        if($id)
        {
            $query .= " WHERE id = $id";
        }

        $res = $sql->select($query);

        echo json_encode($res); //response
    }

}
