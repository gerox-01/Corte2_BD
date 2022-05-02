<?php

require_once('./../../../vendor/autoload.php');
require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');
require_once('./../api/token.php');

LimpiarEntradas();
//libreria de jwt
use Firebase\JWT\JWT;

//listar mensajes recibidos con el token de autenticación
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     $usuario = token();
//     $mensajes = ListarMensajesRecibidos($usuario);
//     if ($mensajes != NULL) {
//         header("HTTP/1.1 200 OK");
//         echo json_encode($mensajes);
//         exit();
//     } else {
//         header("HTTP/1.1 401 Unauthorized");
//         exit();
//     }
// }


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuario = token();
    $mensajesRecibidos = ListarMensajesRecibidos($usuario);
    $mensajesEnviados = ListarMensajesEnviados($usuario);

    if ($mensajesRecibidos != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajesRecibidos);
        exit();
    }   elseif ($mensajesEnviados != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajesEnviados);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     if (isset($_GET['username'])) {
//         $datos = ListarMensajesRecibidos($CONN, $_GET['username']);
//         header('HTTP/1.1 200 OK');
//         echo json_encode($datos);
//         exit();
//     } else {
//         header('HTTP/1.1 400 Bad Request');
//     }
// }

//crear mensajes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = token();
    // $mensaje = $_POST['mensaje'];
    // $destinatario = $_POST['destinatario'];
    // $fecha = date('Y-m-d H:i:s');
    $mensajeDB = EnviarMensaje($_POST['Usuario_Origen'], $_POST['Usuario_Destino'], $_POST['Texto'], $_POST['FechaEnvio'], $_POST['ArchivoAdjunto']);
    if ($mensajeDB != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajeDB);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
   
}

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // var_dump($_POST);
//     if (isset($_POST['Usuario_Origen']) && isset($_POST['Texto'])) {
//         $datos = EnviarMensaje($CONN, $_POST['Usuario_Origen'], $_POST['Usuario_Destino'], $_POST['Texto'], $_POST['FechaEnvio'], $_POST['ArchivoAdjunto']);
//         header('HTTP/1.1 200 OK');
//         echo json_encode($datos);
//     } else {
//         header('HTTP/1.1 400 Bad Request');
//     }
// }

//listar mensajes enviados
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     $usuario = token();
//     $mensajes = ListarMensajesEnviados($CONN, $usuario);
//     if ($mensajes != NULL) {
//         header("HTTP/1.1 200 OK");
//         echo json_encode($mensajes);
//         exit();
//     } else {
//         header("HTTP/1.1 401 Unauthorized");
//         exit();
//     }
// }

//en caso de que ninguna de las anteriores se cumpla
header('HTTP/1.1 400 Bad Request');