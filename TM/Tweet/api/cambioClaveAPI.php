<?php

require_once('./../../../vendor/autoload.php');
require_once('./../lib/db_tools.php');
require_once('./../lib/regex.php');
require_once('./../lib/tools.php');
require_once('./../api/token.php');


if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $_PUT = array();
    parse_str(file_get_contents('php://input'), $_PUT);

    //Limpia las entradas por el método POST
    $_PUT = ArrayCleaner($_PUT);

    $passwordRegex = isset($_PUT['password']) ? validatePassword($_PUT['password']) : null;
    $newpassRegex = isset($_PUT['newPassword']) ? validatePassword($_PUT['newPassword']) : null;
    $confirmpassRegex = isset($_PUT['confirmPassword']) ? validatePassword($_PUT['confirmPassword']) : null;

    try{
        if($passwordRegex && $newpassRegex && $confirmpassRegex){
            $password = isset($_PUT['password']) ? $_PUT['password'] : null;
            $newpass = isset($_PUT['newPassword']) ? $_PUT['newPassword'] : null;
            $confirmpass = isset($_PUT['confirmPassword']) ? $_PUT['confirmPassword'] : null;

            $usuario = token();

            if($newpass === $confirmpass){
                $datos = CambiarClave($usuario, $_PUT['password'], $_PUT['newPassword']);
                header('HTTP/1.1 200 OK');
                echo 'Contraseña actualizada exitosamente';
            } else {
                header('HTTP/1.1 401 Unauthorized');
                echo 'Las contraseñas no coinciden';
            }
        }else{
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("error" => "No se cumple con el formato."));
            exit();
        }

    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Error al actualizar el árticulo';
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'No se pudo actualizar el árticulo';   
}

?>