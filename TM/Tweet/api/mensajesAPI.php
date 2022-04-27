<?php

require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');

LimpiarEntradas();
$CONN = ConexionDB();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username'])) {
        $datos = ListarMensajesRecibidos($CONN, $_GET['username']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
        exit();
    } else {
        header('HTTP/1.1 400 Bad Request');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['Usuario_Origen']) && isset($_POST['Texto'])) {
        $datos = EnviarMensaje($CONN, $_POST['Usuario_Origen'], $_POST['Usuario_Destino'], $_POST['Texto'], $_POST['FechaEnvio'], $_POST['ArchivoAdjunto']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
    } else {
        header('HTTP/1.1 400 Bad Request');
    }
}