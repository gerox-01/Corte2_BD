<?php

require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');
require_once('./../api/token.php');
require_once('./../lib/regex.php');
require_once('./../../../vendor/autoload.php');



LimpiarEntradas();


/**
 * Consultar los tweets desde la API con un GET request
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $datos = MostrarTweet();
    if($datos != NULL){
        header("HTTP/1.1 200 OK");
        echo json_encode($datos);
        exit();
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(array("error" => "No se encontraron tweets"));
        exit();
    }
}

/**
 * Actualizar los tweets desde la API con un PUT request
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $_PUT = array();
    parse_str(file_get_contents('php://input'), $_PUT);


    //Limpia las entradas por el método POST
    $_PUT = ArrayCleaner($_PUT);

    $estadoRegex = isset($_GET['estado']) ? validateBool($_GET['estado']) : null;
    $idRegex = isset($_GET['id']) ? validateID($_GET['id']) : null;

    try{
        if($estadoRegex && $idRegex){

            $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
            $id = isset($_GET['id']) ? $_GET['id'] : null;
    
            if ($estado != null) {
                $datos = Actualizar($_GET['id'], $_GET['estado']);
                header('HTTP/1.1 200 OK');
                echo 'Árticulo actualizado exitosamente';
            } else {
                header('HTTP/1.1 401 Unauthorized');
                echo 'No se pudo actualizar el árticulo';
                exit();
            }
        }else{
            header('HTTP/1.1 400 Bad Request');
            echo 'No coincide con el formato solicitado';
            exit();
        }
    }catch(Exception $e){
        header('HTTP/1.1 400 Bad Request');
        echo 'Error realizando la petición. Comuniquese con el administrador.';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Limpia las entradas por el método POST
    $_POST = ArrayCleaner($_POST);

    $tweetRegex = isset($_POST['articulo']) ? validateTweet($_POST['articulo']) : null;
    $estadoRegex = isset($_POST['espublico']) ? validateBool($_POST['espublico']) : null;

    if ($tweetRegex && $estadoRegex) {

        if (isset($_POST['articulo'])) {
            $usuarioActual = UsuarioActualId();
            $datos = GuardarTweet($_POST['articulo'], $usuarioActual, $_POST['espublico']);
            header('HTTP/1.1 200 OK');
            echo 'Se ha guardado el tweet: ' . $_POST['articulo'] . ' con el estado: ' . $_POST['espublico'];
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo 'No coincide con el formato de soliciado';
    }
}

// /**
//  * Borrar un tweet con la API
//  * Autor: Alejandro Monroy y Gerónimo Quiroga
//  * Fecha: 20/04/2022
//  */
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $idRegex = isset($_GET['id']) ? validateID($_GET['id']) : null;

    try{
        if($idRegex){
            if (isset($_GET['id'])) {
                $datos = EliminarTweet($_GET['id']);
                header('HTTP/1.1 200 OK');
                echo 'Eliminado exitosamente';
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo 'No fue posible eliminar.';
            }
        }else{
            header('HTTP/1.1 400 Bad Request');
            echo 'No coincide con el formato solicitado';
            exit();
        }
    }catch (Exception $e) {
        header('HTTP/1.1 400 Bad Request');
        echo 'Error realizando la petición. Comuniquese con el administrador.';
        exit();
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
            $json_data = str_replace('["', "", $json_data);
            $json_data = str_replace('"]', "", $json_data);
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
// if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
//     if(isset($_GET['id'])){
//         $datos = EliminarTweet($CONN, $_GET['id']);
//         header('HTTP/1.1 200 OK');
//         echo json_encode($datos);
//     }else{
//         header('HTTP/1.1 400 Bad Request');
//     }
// }
