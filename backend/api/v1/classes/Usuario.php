<?php


//use Exception;
namespace Classes;

use Classes\DB\Sql;

class Usuario
{
    /* Constantes */
    const SESSION = "User";
    const SECRET = "Cli_Secret"; //here your key (secret)
    const SECRET_IV = "Cli_Secret_IV"; //here your key 2 (secret)

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
    
    public static function login($login, $password){

        $sql = new Sql();
        $error = false;

        $results = $sql->select("SELECT * FROM usuarios WHERE login = :LOGIN", array(
            ":LOGIN"=>$login 
        ));

        if(count($results) === 0) //se não encontrou o login
        {
            $error = true;
            //throw new \Exception("Usuário inexistente ou senha inválida.");
        }else{
            
            $data = $results[0];

            //if(password_verify($password, $data["senha"]) === true){ //se a senha digitada é equivalente ao Hash do banco
            
            if($password == $data["senha"]){
                $user = new Usuario();
    
                $user->setAll($data);
    
                $arrUser = array();
                $arrUser['id'] = $data["id"];
                $arrUser['login'] = $login;
                $arrUser['senha'] = $password;
                
                $_SESSION[Usuario::SESSION] = $arrUser;
                
                //return $user;
                echo json_encode([
                    "error"=>false
                ]);
                
            }else {
                $error = true;
                //throw new \Exception("Usuário inexistente ou senha inválida.");
            }
        }

        if($error){
            
            echo json_encode([
                "error"=>true,
                "msg"=>"Usuário inexistente ou senha inválida!"
            ]);
        }

    }

    public static function verifyLogin(){

        if(
            !isset($_SESSION[Usuario::SESSION])
            ||
            !$_SESSION[Usuario::SESSION]
            ||
            !(int)$_SESSION[Usuario::SESSION]["id"] > 0 //se é um usuário. obs: se for vazia, transforma em 0
        ){

            header("Location: http://localhost/api/v1/session/login");
            exit;
            
        }
    }
    
    /* insere o valor de todos os Atributos */
    public function setAll($data)
    {
        $this->setNome($data['nome']);
        $this->setEmail($data['email']);
        $this->setLogin($data['login']);
        $this->setSenha($data['senha']);
    }
    
    /* Listar os usuários */
    public function list($id = false)
    {
        $sql = new Sql();

        $query = "SELECT id, nome, email, login, dtCadastro FROM usuarios"; //query sem mostrar o campo senha

        if($id) //se foi passado o id
        {
            $query .= " WHERE id = $id"; //lista apenas um usuário, através do id
        }

        $res = $sql->select($query);
        
        echo json_encode($res); //response
    }

    /* Inserir usuários */
    public function create($data)
    {
        $sql = new Sql();

        $this->setAll($data);
        
        $res = $sql->select("CALL sp_usuarios_save(:nome, :email, :login, :senha)", array(
            ":nome"=>$this->getNome(),
            ":email"=>$this->getEmail(),
            ":login"=>$this->getLogin(),
            ":senha"=>$this->getSenha()
        )); //usa a procedure do banco de dados para cadastrar usuário (INSERT)

        if(count($res) == 0)
        {
            $res = array(
                'status'=>'error',
                'message'=>'check your inputs!'
            );
        }

        echo json_encode($res);

    }

    /* Atualizar usuário */
    public function update($id, $data)
    {
        $this->setId($id);
        $this->setAll($data);

        $sql = new Sql();

        $res = $sql->select("CALL sp_usuarios_update(:id, :nome, :email, :login, :senha)", array(
            ":id"=>$this->getId(),
            ":nome"=>$this->getNome(),
            ":email"=>$this->getEmail(),
            ":login"=>$this->getLogin(),
            ":senha"=>$this->getSenha()
        )); //usa a procedure do banco de dados para atualizar usuário (UPDATE)

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

        $res = $sql->select("CALL sp_usuarios_delete(:id)", array(
            ":id"=>$this->getId()
        )); //usa a procedure do banco de dados para excluir usuário (DELETE)

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
