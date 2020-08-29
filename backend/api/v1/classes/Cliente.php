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
       // print_r($data);

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

    
    public function ajax_list_clientes($requestData)
    {

        $column_search = array("nome", "dtNasc", "RG", "CPF", "telefone"); //colunas pesquisáveis pelo datatables
        $column_order = array("nome", "dtNasc", "RG", "CPF", "telefone"); //ordem que vai aparecer (o codigo primeiro)


        $datatable = $this->get_datatable($requestData, $column_search, $column_order);

        $data = array();
        $numItem = $requestData['start']; //o índice que começa a numeração para listar os itens na tabela

        foreach ($datatable['data'] as $cliente) { //para cada registro retornado

            $numItem++;

            // Ler e criar o array de dados ---------------------
            
            $row = [
                "id"=>$cliente['id'],
                "numItem"=>$numItem,
                "nome"=>$cliente['nome'],
                "dtNasc"=>$cliente['dtNasc'],
                "RG"=>$cliente['RG'],
                "CPF"=>$cliente['CPF'],
                "telefone"=>$cliente['telefone'],      
            ];

            $data[] = $row;
        } //

        //Cria o array de informações a serem retornadas para o Javascript
        $json = array(
            "draw" => intval($requestData['draw']), //para cada requisição é enviado um número como parâmetro
            "recordsTotal" => $this->records_total(),  //Quantidade de registros que há no banco de dados
            "recordsFiltered" => $datatable['totalFiltered'], //Total de registros quando houver pesquisa
            "data" => $data,  //Array de dados completo dos dados retornados da tabela 
        );

        return json_encode($json); //enviar dados como formato json

    }

    public function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT count(id) FROM clientes");
        
        return $results[0]['count(id)'];		
    }
    
    public function records_total()
    {
        return $this->total();
    } 

    
    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT * FROM clientes";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '%$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        
        $res = $this->searchAll($query);
        $totalFiltered = count($res);

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        //echo $query;
        return array(
            'totalFiltered'=>$totalFiltered,
            'data'=>$this->searchAll($query),
        );
    }

    
    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        if ($_POST["nome"] == "") {
            $errors["#nome"] = "Nome é obrigatório!";
        }

        if ($_POST["dtNasc"] == "") {
            $errors["#dtNasc"] = "Data de Nascimento é obrigatória!";
        }
        
        if ($_POST["RG"] == "") {
            $errors["#RG"] = "RG é obrigatório!";
        }

        if ($_POST["CPF"] == "") {
            $errors["#CPF"] = "CPF é obrigatório!";
        }
        
        if ($_POST["telefone"] == "") {
            $errors["#telefone"] = "Telefone é obrigatório!";
        }

        $exists = 0;
        $exists = $this->searchName($_POST["nome"]);
        if (count($exists) > 0) { //se existe nome completo igual já registrado

            if ($update) {
                foreach ($exists as $cliente) {

          
                    if (($_POST['nome'] == $cliente['nome']) && ($_POST['id'] != $cliente['id'])) {
                        $errors["#nome"] = "Já existe um Cliente com esse Nome";
                        break;
                    }
                }
            } else {
                $errors["#nome"] = "Já existe um Cliente com esse Nome";
            }
        }

        if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário

            return json_encode([
                'error' => true,
                'error_list' => $errors
            ]);
        } else { //se ainda não tem erro

            return json_encode([
                'error' => false
            ]);

            /*if($this->getfoto() == ""){
                $json["error_list"]["#desImagePath"] = "Não foi possível fazer Upload da imagem!";               
            }*/
        }
    }/* --- fim verificaErros() ---------------------------*/

    public static function searchName($nome){ //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM clientes WHERE (nome = :nome)", array(
            ":nome"=>$nome
        ));

        return $results;
    }


}