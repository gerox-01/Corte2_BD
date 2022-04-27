<?php


require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');

LimpiarEntradas();
$CONN = ConexionDB();



/**
 * Consultar los usuarios desde la API con un GET
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = $_GET['username'];
    $datos = ObtenerUsuarioDB($CONN, $id);
    if ($datos != NULL) {
        foreach ($datos as $key => $value) {
            header("HTTP/1.1 200 OK");
            echo json_encode($datos);
            exit();
        }
    }
}

/**
 * Crear un usuario desde la API con un POST
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {
        $_SESSION['estCivil'] =  $_POST['estCivil'];
        $datos = RegistrarUsuarioDB($CONN, $_POST['username'], $_POST['password'], $_POST['name'], $_POST['lastname'], $_POST['fecha_nac'], $_POST['color'], $_POST['email'], $_POST['tipDoc'], $_POST['num_doc'], $_POST['numhijos'], $_POST['archivo'], $_POST['direccion'], $_POST['estCivil']);
        $datos = ['id' => $datos];
        header("HTTP/1.1 200 OK");
        echo json_encode($datos);
        exit();
    } else {
        header("HTTP/1.1 400 Bad Request");
    }
}


/**
 * Actualizar un usuario con la API
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['id']) && isset($_GET['uid'])) {
        // $datos = ActualizarUsuario($CONN,);
        $datosd = ['id' => $datos];
        header("HTTP/1.1 200 OK");
        echo json_encode($datos);
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
 * En caso de que ninguna de las opciones anteriores se haya ejecutado
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
header("HTTP/1.1 400 Bad Request");
