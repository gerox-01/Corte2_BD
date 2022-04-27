<?php

require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');

require_once('./../../../vendor/autoload.php');



LimpiarEntradas();
$CONN = ConexionDB();



// if($_SERVER['REQUEST_METHOD'] == 'GET'){
//     echo UsuarioActual();
// }


/**
 * Consultar los tweets desde la API con un GET request
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $CONN = ConexionDB();
    $datos = MostrarTweet($CONN);
    header('HTTP/1.1 200 OK');
    echo json_encode($datos);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mensaje'])) {
        $CONN = ConexionDB();
        $usuarioActual = UsuarioActualId();
        $datos = GuardarTweet($CONN, $_POST['mensaje'], $usuarioActual, $_POST['estado']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
    } else {
        header('HTTP/1.1 400 Bad Request');
    }
}

// /**
//  * Borrar un tweet con la API
//  * Autor: Alejandro Monroy y Gerónimo Quiroga
//  * Fecha: 20/04/2022
//  */
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['id'])) {
        $datos = EliminarTweet($CONN, $_GET['id']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
    } else {
        header('HTTP/1.1 400 Bad Request');
    }
}


/**
 * Función que retorna el usuario Actual consumiendo la API con Token 
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 25/04/2022
 * @return string
 */
function UsuarioActual()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';

    if (substr($jwt, 0, 6) == "Bearer") {
        $jwte = str_replace("Bearer ", "", $jwt);
        try {
            // echo $jwte;
            $data = Firebase\JWT\JWT::decode($jwte, $key, array('HS256'));
            // echo $data;
            // Serializar data
            $datas = (array)$data;
            foreach ($datas as $key => $value) {
                $usuario = $value;
            }
            // var_dump($usuario);
            $json_data  = json_encode((array)$usuario->usuario);
            // print_r($json_data);
            $json_data = str_replace('[', "", $json_data);
            $json_data = str_replace(']', "", $json_data);
            return $json_data;
        } catch (Exception $e) {
            echo 'Credenciales incorrectas del usuario actualizar';
            echo $e->getMessage();
            http_response_code(404);
            exit();
        }
    }
    return "";
}

/**
 * Función que retorna el idusuario Actual consumiendo la API con Token
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 25/04/2022
 * @return string
 */
function UsuarioActualId()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';

    if (substr($jwt, 0, 6) == "Bearer") {
        $jwte = str_replace("Bearer ", "", $jwt);
        try {
            // echo $jwte;
            $data = Firebase\JWT\JWT::decode($jwte, $key, array('HS256'));
            // echo $data;
            // Serializar data
            $datas = (array)$data;
            foreach ($datas as $key => $value) {
                $usuario = $value;
            }
            // var_dump($usuario);
            $json_data  = json_encode((array)$usuario->iduser);
            // print_r($json_data);
            $json_data = str_replace('[', "", $json_data);
            $json_data = str_replace(']', "", $json_data);
            return $json_data;
        } catch (Exception $e) {
            echo 'Credenciales incorrectas del usuario actualizar';
            echo $e->getMessage();
            http_response_code(404);
            exit();
        }
    }
    return "";
}


?>