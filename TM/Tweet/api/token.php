<?php
    require_once('./../../../vendor/autoload.php');
    require_once('./../lib/db_tools.php');
    require_once('./../lib/tools.php');
    use Firebase\JWT\JWT;

    $time = time();
    LimpiarEntradas();

    function token(){
        $key = 'my_secret_key';

        if(!isset($usuario))
            $usuario = '';
        
        $jwt = $_SERVER['HTTP_AUTHORIZATION'];
        if(substr($jwt, 0, 6)==="Bearer"){
            $jwt = str_replace("Bearer ", "", $jwt);
            try{
                $data = JWT::decode($jwt, $key, array('HS256'));
                $usuario = $data->data->usuario;
                return $usuario;
            }catch(\Throwable $th){
                var_dump($th);
                echo 'Credenciales erroneas';
                http_response_code(401);
                exit();
            }
        }
        else {
            echo 'Acceso o autorizado';
            http_response_code(401);
            exit();
        }
    }
?>