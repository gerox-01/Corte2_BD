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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['clave'];
    $usuarioDB = ValidarLoginDB($CONN, $usuario, $contrasena);
    if ($usuarioDB != FALSE) {
        $usuarioDB = ObtenerUsuarioDB( $usuario);
        if ($usuarioDB != NULL) {
            foreach ($usuarioDB as $key => $value) {
                $usuarioDB[$key] = $value;
                $data = $value;
            }

            // $iduser = encriptarPassword($value['id_usuario']);
            $iduser = $value['id_usuario'];

            $token = array(
                'iat' => $time, //Tiempo inicio del token
                'exp' => $time + (60 * 60), //Tiempo de expiración del token
                'data' => ['usuario' => $usuario, 'iduser' => $iduser] //datos del usuario
            );
            $jwt = JWT::encode($token, $key, 'HS256');

            // $data = JWT::decode($jwt, $key, array('HS256'));

            // var_dump($data);

            $datos = ['token' => $jwt];
            header("HTTP/1.1 200 OK");
            echo json_encode($datos);
            exit();
        } else {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }


    $token = $_GET['token'];
    $decoded = JWT::decode($token, $key, array('HS256'));
    $usuario = $decoded->data->usuario;
    $usuarioDB = ObtenerUsuarioDB($usuario);
    if ($usuarioDB != NULL) {
        foreach ($usuarioDB as $key => $value) {
            $usuarioDB[$key] = $value;
            $data = $value;
            global $usuariot;
            $usuariot = $value['usuario'];
        }
        header("HTTP/1.1 200 OK");
        echo json_encode($data);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}


header("HTTP/1.1 400 Bad Request");
