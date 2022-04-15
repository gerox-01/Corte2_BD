<?php

/**
 * Autores: Alejandro Monroy & Gerónimo Quiroga
 * Fecha: 19/03/2022
 * Materia: Linea de profundización 2
 * Descripción: parcial 1
 */


header('Content-Type: text/html; charset=UTF-8');
require_once('./nav.php');


if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
} else {
    header('Location: login.php');
    die();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="css/styles.css">

    <title>Aplicativo Linea de Prof 2</title>
</head>
</head>
<!-- body -->

<body>

    <?php
    require_once "lib/tools.php";
    require_once "lib/db_tools.php";
    require_once "funcionesCSRF.php";

    GenerarAnctiCSRF();

    $CONN = ConexionDB();

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
    }
    ?>

    <?php
    echo '<div style="width: 90vw; display: flex; justify-content: space-around; padding: 0 !important; margin-bottom: 0 !important;">';
    echo '<form method="post" style="display: flex; flex-direction: row !important; width: 98vw; justify-content: space-around !important;">';
    echo '<input type="submit" name="msrecibidos" value="Mensajes recibidos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="msenviados" value="Mensaje enviados" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="crearmensaje" value="Crear mensaje" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '</form>';
    echo '</div>';


    if (isset($_POST['msrecibidos'])) {
        echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow-x: hidden;" id="style-5">';
        echo "<div class='force-overflow'>";
        $mensajes_recibidos = ListarMensajesRecibidos($CONN, $_SESSION['username']);
        foreach ($mensajes_recibidos as $key => $value) {
            echo ' <div style="background-color: ' . $_SESSION['color'] . ' !important; width: 95vw;">';
            echo '<b> De ' . $value['Usuario_origen'] . ' : ' . '</b>' . $value['Texto'] . '<br>';
            echo '<b> De ' . 'Fecha' . '</b>' . $value['FechaEnvio'] . '<br>';
            echo '<b>' . 'Archivo: ' . '</b>' . "<img src='" . $value['ArchivoAdjunto'] . "' style='width: 50px; height: 50px; border-radius: 50%;'>";
            echo '<br>';
            echo '<b>' . 'Descargar archivo: ' . '</b>' . '<a href="' . $value['ArchivoAdjunto'] . '" download>Descargar</a>';
            echo '<br>';
            $registros = ConexionDB()->query("SELECT  `foto` FROM `usuarios`WHERE Usuario='" . $value['Usuario_origen'] . "'")->fetchAll(PDO::FETCH_OBJ);
            foreach ($registros as $persona) {
                echo '<b>' . 'Foto del autor: ' . '</b>' . '<img height=50" src="' . $persona->foto . ' ">.<br><br>';
            }
            echo '</div>';
        }

        echo "</div>";
        echo "</div>";
    }else if (isset($_POST['msenviados'])) {
        echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow-x: hidden;" id="style-5">';
        echo "<div class='force-overflow'>";
        $mensajes_enviados = ListarMensajesEnviados($CONN, $_SESSION['username']);
        foreach ($mensajes_enviados as $key => $value) {
            $archivo = $value['ArchivoAdjunto'];
            echo ' <div style="background-color: ' . $_SESSION['color'] . ' !important; width: 95vw;">';
            echo '<b> Para ' . $value['Usuario_destino'] . ' : ' . '</b>' . $value['Texto'] . '<br>';
            echo '<b>' . 'Fecha: ' . '</b>' . $value['FechaEnvio'] . '<br>';
            echo '<b>' . 'Archivo: ' . '</b>' . "<img src='" . $value['ArchivoAdjunto'] . "' style='width: 50px; height: 50px; border-radius: 50%;'>";
            echo '<br>';
            echo '<b>' . 'Descargar archivo: ' . '</b>' . '<a href="' . $value['ArchivoAdjunto'] . '" download>Descargar</a>';
            echo '<br>';
            $registros = ConexionDB()->query("SELECT  `foto` FROM `usuarios`WHERE Usuario='" . $value['Usuario_destino'] . "'")->fetchAll(PDO::FETCH_OBJ);
            foreach ($registros as $persona) {
                echo '<b>' . 'Foto del destinatario: ' . '</b>' . '<img height=50" src="' . $persona->foto . ' ">.<br><br>';
            }
            echo '</div>';
        }

        echo "</div>";
        echo "</div>";
    } else if (isset($_POST['crearmensaje'])) {
        echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow-x: hidden;" id="style-5">';
        echo "<div class='force-overflow'>";
    ?>
        <form method="post" enctype="multipart/form-data">
            <b> Enviar Mensajes </b><br>
            <label form="cmbDestino">Destinatario: </label>
            <select name="cmbDestino" id="cmbDestino">
                <?php
                $usuarios = ListarUsuarios($CONN, $_SESSION['username']);

                foreach ($usuarios as $key => $value) {
                    echo '<option value="' . $value['usuario'] . '">' .
                        $value['nombres'] . ' ' . $value['apellidos'] . '</option>';
                }
                ?>
            </select>
            <br><br>
            <label for="txtMensaje"> Mensaje: </label>
            <input type="text" name="txtMensaje" id="txtMensaje"><br><br>

            <label for="fulAdjunto">Archivo:</label>
            <input type="file" name="fulAdjunto" id="fulAdjunto"><br><br>
            <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
            <input type="submit" name="btnEnviar" value="Enviar" class="btn btn-primary"><br><br>

        </form>
    <?php
        echo '</div>';
        echo "</div>";
    } else{
        echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow-x: hidden;" id="style-5">';
        echo "<div class='force-overflow'>";
        $mensajes_recibidos = ListarMensajesRecibidos($CONN, $_SESSION['username']);
        foreach ($mensajes_recibidos as $key => $value) {
            echo ' <div style="background-color: ' . $_SESSION['color'] . ' !important; width: 95vw;">';
            echo '<b> De ' . $value['Usuario_origen'] . ' : ' . '</b>' . $value['Texto'] . '<br>';
            echo '<b> De ' . 'Fecha' . '</b>' . $value['FechaEnvio'] . '<br>';
            echo '<b>' . 'Archivo: ' . '</b>' . "<img src='" . $value['ArchivoAdjunto'] . "' style='width: 50px; height: 50px; border-radius: 50%;'>";
            echo '<br>';
            echo '<b>' . 'Descargar archivo: ' . '</b>' . '<a href="' . $value['ArchivoAdjunto'] . '" download>Descargar</a>';
            echo '<br>';
            $registros = ConexionDB()->query("SELECT  `foto` FROM `usuarios`WHERE Usuario='" . $value['Usuario_origen'] . "'")->fetchAll(PDO::FETCH_OBJ);
            foreach ($registros as $persona) {
                echo '<b>' . 'Foto del autor: ' . '</b>' . '<img height=50" src="' . $persona->foto . ' ">.<br><br>';
            }
            echo '</div>';
        }

        echo "</div>";
        echo "</div>";
    }

    if (isset($_POST['btnEnviar'])) {

        if (isset($_FILES['fulAdjunto'])) {
            $fileTmpPath = $_FILES['fulAdjunto']['tmp_name'];
            $fileName = $_FILES['fulAdjunto']['name'];
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
                    echo '<br/>Archivo subido.<br/>';

                    $_SESSION['fulAdjunto'] =  $dest_path;

                    $usuario_origen =  $_SESSION['username'];
                    $usuario_destino = $_POST['cmbDestino'];
                    $texto = $_POST['txtMensaje'];
                    $archivo = $dest_path;
                    date_default_timezone_set('America/Bogota');
                    $fechaenvio = date('m-d-Y h:i:s a', time());

                    EnviarMensaje($CONN, $usuario_origen, $usuario_destino, $texto, $fechaenvio, $archivo);
                } else {
                    echo '<br/>Archivo no se almaceno.<br/>';
                }
            }
        }
    }

    ?>
    <footer class="i-footer">
        <p>&copy; T1</p>
        <div class="i-divfotter">
            <p class="i-integrantes">Integrantes: </p>
            <p>David Quiroga |</p>
            <p>| Alejandro Monroy</p>
        </div>
    </footer>
</body>

</html>