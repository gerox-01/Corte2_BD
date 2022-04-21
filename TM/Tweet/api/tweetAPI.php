<?php

require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');

LimpiarEntradas();
$CONN = ConexionDB();


/**
 * Consultar los tweets desde la API con un GET request
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['username'])){
        $datos = ListarMensajesRecibidos($CONN, $_GET['username']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
        exit();
    }else{
        header('HTTP/1.1 400 Bad Request');
    }
}

/**
 * Crear un tweet desde la API con un POST request
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['mensaje']) && isset($_POST['iduser'])){
        $datos = GuardarTweet($CONN, $_POST['mensaje'], $_POST['iduser'], $_POST['estado']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
    }else{
        header('HTTP/1.1 400 Bad Request');
    }
}

/**
 * Borrar un tweet con la API
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 20/04/2022
 */
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    if(isset($_GET['id'])){
        $datos = EliminarTweet($CONN, $_GET['id']);
        header('HTTP/1.1 200 OK');
        echo json_encode($datos);
    }else{
        header('HTTP/1.1 400 Bad Request');
    }
}

?>