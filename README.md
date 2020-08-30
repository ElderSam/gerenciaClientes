# Sistema Gerenciador de Clientes

#TAREFAS 

Backend;

    - [x] criar Banco de Dados

    - [x] criar API: rotas (GET, POST, PUT, DELETE)
        - [x] rotas de usuários
        - [x] rotas de clientes 
        - [x] rotas de estados e municipio
        - [x] rotas de endereços 

Frontend;

    - [ ] cadastro de usuário
    - [x] área de login
    - [x] cadastro de clientes



#DOCUMENTAÇÃO

Banco de Dados:
    DER (Diagrama Entidade Relacionamento):
    <img src="backend/database/DER.png">


Rotas da API;

    Clientes:
        Listar (Method: GET)
        http://localhost/api/v1/clientes

        Cadastrar (Method: POST)
        http://localhost/api/v1/clientes

        Atualizar (Method: POST or PUT)
        http://localhost/api/v1/clientes/:id

        Excluir (Method: DELETE)
        http://localhost/api/v1/clientes/:id


    Usuários:
        Listar (Method: GET)
        http://localhost/api/v1/usuarios

        Cadastrar (Method: POST)
        http://localhost/api/v1/usuarios

        Atualizar (Method: POST or PUT)
        http://localhost/api/v1/usuarios/:id

        Excluir (Method: DELETE)
        http://localhost/api/v1/usuarios/:id


