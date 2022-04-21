<?php
//libreria de composer
require_once '../vendor/autoload.php';

//libreria de jwt
use Firebase\JWT\JWT;

$key = 'my_secret_key';
$time = time();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $credenciales = json_decode(file_get_contents("php://input"), true);
    $usuario = $credenciales['usuario'];
    $contrasena = $credenciales['contrasena'];
    $usuarioDB = ObtenerUsuarioDB($CONN, $usuario);
    if($usuarioDB != NULL){
        if($usuarioDB['contrasena'] == $contrasena){
            $token = array(
                'usuario' => $usuario,
                'iat' => $time,
                'exp' => $time + (60*60)
            );
            $jwt = JWT::encode($token, $key, true);
            $datos = ['token' => $jwt];
            header("HTTP/1.1 200 OK");
            echo json_encode($datos);
            exit();
        }else{
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    }else{
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    $token = $_GET['token'];
    try{
        $decoded = JWT::decode($token, $key, array('HS256'));
        $decoded_array = (array) $decoded;
        $usuario = $decoded_array['usuario'];
        $usuarioDB = ObtenerUsuarioDB($CONN, $usuario);
        if($usuarioDB != NULL){
            header("HTTP/1.1 200 OK");
            echo json_encode($usuarioDB);
            exit();
        }else{
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    }catch(Exception $e){
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}

header("HTTP/1.1 400 Bad Request");


