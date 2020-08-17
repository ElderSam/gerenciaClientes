<?php
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . './../../vendor/autoload.php';

use \Classes\Usuario;

session_start();

class API
{
    public static function open($requisicao)
    {
        
        if(isset($requisicao['url'])) //se foi passada uma requisição
        { 
                        
            $url = explode('/', $requisicao['url']);

            if($url['0'] == 'api') //se foi feita uma requisição para o Back-end
            { 
                array_shift($url); //reorganiza o array, tirando o que está na primeira posição
                array_shift($url);

                $file = $url[0];
                $route = __DIR__ . "\\routes\\".$file.".php"; //monta o caminho para o arquivo da rota solicitada
     
            }else{
                $file = $url[0];

                header('Location: http://localhost/'.$file.".html");
            }       

           
            try
            {
   
                if(file_exists($route)) //se o arquivo da rota existe
                {
                    require $route;
                }else
                {
                    return json_encode(array(
                        'status'=>'erro',
                        'dados'=>'Rota inexistente!'
                    ));
                }

            }catch(Exception $e){

                return json_encode(array(
                    'status'=>'erro',
                    'dados'=>$e->getMessage()
                ));
            }
            
		}else{
            //return "Server is running! API is up!";
            Usuario::verifyLogin();
            
            header('Location: http://localhost/clientes');
            //return "<script>window.location.href='http://localhost/';</script>";
		}
    }

}

if(isset($_REQUEST))
{
    echo API::open($_REQUEST);
}