<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">

    <title>Perfil</title>
</head>

<body>
    <?php
    require_once('./lib/db_tools.php');
    require_once('./lib/tools.php');
    require_once('./nav.php');

    LimpiarEntradas();
    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();


    $user = $_SESSION['username'] ?? '';

    if ($user == '' || $user == null) {
        header('Location: login.php');
        die();
    }
    ?>


    <form method="post" style='overflow-y: hidden !important; height: 68vh;' class="form-register" id="style-5" enctype="multipart/form-data">
        <div style='display:flex; align-items: center; justify-content: start;'>
            <!-- Nombre -->
            <div>
                <label for="name">Nombre:</label>
                <input class="r-options" type="text" name="name" id="name" required="required" pattern="([a-zA-Z\s]+)" maxlength=20 title="Escriba el nombre" value="<?php echo $_SESSION['nombre']; ?>">
            </div>
            <!-- Apellido -->
            <div>
                <label for="lastname">Apellido:</label>
                <input class="r-options" type="text" name="lastname" id="lastname" required="required" pattern="([a-zA-Z\s]+)" maxlength=20 title="Escriba apellidos" value="<?php echo $_SESSION['apellido']; ?>">
            </div>
            <!-- Correo -->
            <div>
                <label for="correo">Correo:</label>
                <input class="r-options" type="email" name="email" id="email" maxlength=20 required="required"  pattern ="([a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4})" value="<?php echo $_SESSION['email']; ?>">
            </div>
            <!-- Tipo de documento -->
            <div style='display: flex; flex-direction: column;'>
                <label for="tipodoc">Tipo de Documento:</label>
                <select name="tipDoc" required="required">
                    <?php $tiposdoc = SeleccionarTipoDocDB($CONN);
                    foreach ($tiposdoc as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_tipdoc'] ?>"><?php echo $opciones['tip_doc'] ?></option>
                    <?php

                    }
                    ?>
                </select>
            </div>
        </div>
        <div style='display:flex; align-items: center; justify-content: start;'>
            <!-- Numero de documento -->
            <div>
                <label for="num">Numero de Documento:</label>
                <input class="r-options" type="text" name="num_doc" id="num_doc" required="required" pattern="(^[0-9]{8,10}$)" value="<?php echo $_SESSION['numdoc']; ?>" title="Escriba el numero de documento">
            </div>
            <!-- Direccion -->
            <div>
                <label for="direccion">Dirección:</label>
                <input class="r-options" type="text" name="direccion" maxlength=30 id="direccion" required="required" 
                pattern="(^[#.0-9a-zA-Z\s,-]+$)" value="<?php echo $_SESSION['direccion']; ?>" title="Escriba la dirección correctamente">
            </div>
            <!-- Numero de hijos -->
            <div style='display: flex; flex-direction: column;'>
                <label for="numhijos">Numero de hijos:</label>
                <select name="numhijos" id="numhijos">
                    <?php
                    $canthijos = SeleccionarCanHijos($CONN);
                    foreach ($canthijos as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_cant_hijos'] ?>"><?php echo $opciones['cant_hijos'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <!-- Estado civil -->
            <div style='display: flex; flex-direction: column;'>
                <label for="estadocivil">Estado civil:</label>
                <select name="estCivil" id="estCivil" required="required">
                    <?php
                    $estadocivil = SeleccionarEstadoCivilDB($CONN);
                    foreach ($estadocivil as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_est_civil'] ?>"><?php echo $opciones['est_civil'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style='display:flex; align-items: center; justify-content: start;'>
            <!-- Color favorito del usuario -->
            <div>
                <label for='color'>Color favorito:</label>
                <input class="r-options" type='color' name='color' style="height: 40px;" id='color' value="<?php echo $_SESSION['color']; ?>" required='required'>
            </div>
            <!-- Foto -->
            <div style='display: flex; flex-direction: column;'>
                <label for="archivo">Foto:</label>
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <img src="<?php echo $_SESSION['foto'] ?>" style="width: 5.3rem;" alt="">
                    <input type="file" name="archivo" id="archivo" accept="image/*" /><br><br>
                </div>
            </div>
            <!-- Fecha de nacimiento -->
            <div>
                <label for="fecha_nac">Fecha de nacimiento:</label>
                <input class="r-options" type="date" name="fecha_nac" id="fecha_nac" value="<?php echo $_SESSION['fecha']; ?>" required="required">
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; width: 30vw;">
            <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
            <input type="submit" name="actualizar" value="Actualizar" class='button-r'>
            <input type="submit" name="cambiarClave" value="Cambiar clave" class='button-r'>
        </div>
    </form>


    <?php
    if (isset($_POST['actualizar'])) {
        if (
           
            preg_match("/^[a-zA-Z\s]+$/", $_POST['name']) &&
            preg_match("/^[a-zA-Z\s]+$/", $_POST['lastname']) &&
            preg_match("/^[0-9]{8,10}$/", $_POST['num_doc']) &&
            preg_match("/^[#.0-9a-zA-Z\s,-]+$/", $_POST['direccion']) &&
            preg_match("/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/", $_POST['email'])
            
        ) {

            #region Foto
            function loadImage()
            {
                $archivo = $_FILES['archivo']['name'];
                if (isset($archivo) && $archivo != "") {

                    $tipo = $_FILES['archivo']['type'];
                    $tamano = $_FILES['archivo']['size'];
                    $temp = $_FILES['archivo']['tmp_name'];

                    $fileExt = explode('.', $archivo);
                    $fileActualExt = strtolower(end($fileExt));

                    if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 8000000))) {
                        echo '<script>alert("Error. La extensión o el tamaño de los archivos no es correcta")</script>';
                    } else {
                        //Imagen concuerda, Entra
                        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                        $fileDestination = '../uploaded_files/' . $fileNameNew;

                        if (move_uploaded_file($temp, $fileDestination)) {
                            //Permisos
                            $_SESSION['foto'] =  $fileDestination;
                            //remove exif data
                            if ($fileActualExt == "jpg" || $fileActualExt == "jpeg") {
                                $img = imagecreatefromjpeg($fileDestination);
                                imagejpeg($img, $fileDestination, 100);
                                imagedestroy($img);
                            } else if ($fileActualExt == "png") {
                                $img = imagecreatefrompng($fileDestination);
                                imagejpeg($img, $fileDestination);
                                imagedestroy($img);
                            } else if ($fileActualExt == "gif") {
                                $img = imagecreatefromgif($fileDestination);
                                imagejpeg($img, $fileDestination);
                                imagedestroy($img);
                            }
                            // $image = imagecreatefromjpeg($fileDestination);
                            // imagejpeg($image, $fileDestination, 100);
                            // imagedestroy($image);

                            // echo '<script>alert("Usuario Registrado")</script>';
                            // echo '<script>window.location.href="index.php"; </script>';
                        } else {
                            echo '<script>alert("Error. credenciales incorrectas")</script>';
                        }
                    }
                }
            }
            #endregion

            #region Actualización datos
            $_SESSION['nombre'] = $_POST['name'];
            $_SESSION['apellido'] = $_POST['lastname'];
            $_SESSION['fecha'] = $_POST['fecha_nac'];
            $_SESSION['color'] = $_POST['color'];
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['tipodoc'] = $_POST['tipDoc'];
            $_SESSION['numdoc'] = $_POST['num_doc'];
            $_SESSION['hijos'] = $_POST['numhijos'];
            $_SESSION['direccion'] = $_POST['direccion'];
            $_SESSION['estcivil'] = $_POST['estCivil'];
            #endregion
            loadImage();

            $actualizar = ActualizarUsuario($CONN, $_SESSION['username'], $_SESSION['nombre'], $_SESSION['apellido'], $_SESSION['fecha'], $_SESSION['color'], $_SESSION['email'], $_SESSION['tipodoc'], $_SESSION['numdoc'], $_SESSION['hijos'], $_SESSION['foto'], $_SESSION['direccion'], $_SESSION['estcivil']);

            if ($actualizar) {
                echo '<p>Usuario Actualizado</p>';
                echo '<script>window.location.href="index.php"; </script>';
            } else {
                echo '<script>alert("Error. credenciales incorrectas")</script>';
            }
        } else {
            echo "<p>No coinciden con formato solicitado</p>";
        }
    }

    if (isset($_POST['cambiarClave'])) {
        echo '<script>window.location.href="cambioClave.php"; </script>';
    }
    ?>
</body>

</html>