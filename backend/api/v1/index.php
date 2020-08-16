<?php
header('Content-Type: application/json; charset=utf-8');

class API
{
    public static function open($requisicao)
    {
        if(isset($requisicao['url'])){ //se foi passada uma requisição
                        
            $url = explode('/', $requisicao['url']);
            
            array_shift($url); //reorganiza o array, tirando o que está na primeira posição
            array_shift($url);

            $classe = $url[0];
            
            try
            {
                $route = __DIR__ . "./routes/".$classe.".php"; //monta o caminho para o arquivo da rota solicitada

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
			echo "Server is running! API is up!";
		}
    }

}

if(isset($_REQUEST))
{
    echo API::open($_REQUEST);
}