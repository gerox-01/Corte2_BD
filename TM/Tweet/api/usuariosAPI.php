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

    // var_dump($_GET);

    if (isset($_GET['username'])) {
        $id = $_GET['username'];
        $datos = ObtenerUsuarioDB($CONN, $id);
        if ($datos != NULL) {
            foreach ($datos as $key => $value) {
                header("HTTP/1.1 200 OK");
                echo json_encode($datos);
                exit();
            }
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
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
 * Borrar un usuario con la API
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
// if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
//     if (isset($_GET['id'])) {
//         // $datos = BorrarUsuario($CONN, $_GET['id']);
//         header("HTTP/1.1 200 OK");
//         echo json_encode($datos);
//         exit();
//     } else {
//         header("HTTP/1.1 400 Bad Request");
//     }
// }

/**
 * En caso de que ninguna de las opciones anteriores se haya ejecutado
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
header("HTTP/1.1 400 Bad Request");
