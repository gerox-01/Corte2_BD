<?php

require_once('./../../../vendor/autoload.php');
require_once('./../lib/db_tools.php');
require_once('./../lib/regex.php');
require_once('./../lib/tools.php');
require_once('./../api/token.php');

LimpiarEntradas();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuario = token();
    $mensajesRecibidos = ListarMensajesRecibidos($usuario);
    $mensajesEnviados = ListarMensajesEnviados($usuario);

    if ($mensajesRecibidos != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajesRecibidos);
        exit();
    } elseif ($mensajesEnviados != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajesEnviados);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}

//crear mensajes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $usuario = token();

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($_FILES['ArchivoAdjunto'])) {
            $fileTmpPath = $_FILES['ArchivoAdjunto']['tmp_name'];
            $fileName = $_FILES['ArchivoAdjunto']['name'];
            $files_folder = '../uploaded_files/';
            if (!file_exists($files_folder)) {
                mkdir($files_folder);
            }

            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $permitidas = ['jpg', 'gif', 'png', 'jpeg', 'pdf'];
            if (in_array($fileExtension, $permitidas)) {

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $files_folder . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
                        $img = imagecreatefromjpeg($dest_path);
                        imagejpeg($img, $dest_path, 100);
                        imagedestroy($img);
                    } else if ($fileExtension == 'png') {
                        $img = imagecreatefrompng($dest_path);
                        imagejpeg($img, $dest_path, 100);
                        imagedestroy($img);
                    } else if ($fileExtension == 'gif') {
                        $img = imagecreatefromgif($dest_path);
                        imagejpeg($img, $dest_path, 100);
                        imagedestroy($img);
                    }

                    $usuario_destinoRegex = validateUser($_POST['Usuario_Destino']);
                    $textoRegex = validateTweet($_POST['Texto']);

                    if ($usuario_destinoRegex && $textoRegex) {
                        $archivo = $dest_path;
                        date_default_timezone_set('America/Bogota');
                        $fechaenvio = date('m-d-Y h:i:s a', time());

                        $usuario_destino = $_POST['Usuario_Destino'];
                        $texto = $_POST['Texto'];

                        $mensajeDB = EnviarMensaje($usuario, $usuario_destino, $texto, $fechaenvio, $archivo);
                        if ($mensajeDB != NULL) {
                            header("HTTP/1.1 200 OK");
                            echo 'Envio de mensaje exitoso';
                            exit();
                        } else {
                            header("HTTP/1.1 401 Unauthorized");
                            echo 'Error al enviar mensaje';
                            exit();
                        }
                    }else{
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al enviar mensaje: Usuario o texto invalido';
                        exit();
                    }
                } else {
                    echo '<br/>Archivo no se almaceno.<br/>';
                }
            } else {
                echo 'Archivo no permitido.';
            }
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo 'Error haciendo la petición. Comuniquese con el administrador.';
        exit();
    }
}

//en caso de que ninguna de las anteriores se cumpla
header('HTTP/1.1 400 Bad Request');
