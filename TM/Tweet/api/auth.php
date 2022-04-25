<?php
//libreria de composer
require_once('./../../../vendor/autoload.php');
require_once('./../lib/db_tools.php');

$CONN = ConexionDB();

//libreria de jwt
use Firebase\JWT\JWT;

//Definir key y time de la api
$key = 'my_secret_key';
$time = time();

//Método POST para autenticar camster.com
if($_SERVER['REQUEST_METHOD']=='POST'){
    // $credenciales = json_decode(file_get_contents("php://input"), true);
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['clave'];
    $usuarioDB = ObtenerUsuarioDB($CONN, $usuario);
    if($usuarioDB != NULL){
        // if($usuarioDB['clave'] == $contrasena){
            $token = array(
                'data' => ['usuario' => $usuario], //datos del usuario
                'iat' => $time, //Tiempo inicio del token
                'exp' => $time + (60*60) //Tiempo de expiración del token
            );
            $jwt = JWT::encode($token, $key);
            $datos = ['token' => $jwt];
            header("HTTP/1.1 200 OK");
            echo json_encode($datos);
            exit();
        // }else{
        //     header("HTTP/1.1 401 Unauthorized");
        //     exit();
        // }
    }else{
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}

//Trae el usuario de la base de datos
if($_SERVER['REQUEST_METHOD']=='GET'){
    // $token = $_GET['token'];
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';
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


