<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">7

    <link rel="stylesheet" href="css/styles.css">

    <title>Signup</title>
</head>

<body>

    <?php
    require_once "./lib/tools.php";
    require_once "./lib/db_tools.php";
    require_once './nav.php';
    LimpiarEntradas();
    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();


    // $user = $_SESSION['username'] ?? '';

    // if($user == '' || $user == null){
    //     header('Location: login.php');
    //     die();
    // }


    ?>


    <?php

    #region CodigoRevisar-Alejo
    #region Variables
    $name = '';
    $nameErr = '';
    $lastname = '';
    $lastnameErr = '';
    $tipodoc = '';
    $tipodocErr = '';
    $num = '';
    $email = '';
    $emailErr = '';
    $web = '';
    $webErr = '';
    $username = '';
    $usernameErr = '';
    $password = '';
    $passwordErr = '';
    $confirmpassword = '';
    $confirmpasswordErr = '';
    $fechanacimiento;
    $archivo;
    $fileDestination;
    #endregion

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
        }
        if (empty($_POST["lastname"])) {
            $lastnameErr = "lastname is required";
        } else {
            $lastname = test_input($_POST["lastname"]);
        }
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }
        if (empty($_POST["web"])) {
            $webErr = "web is required";
        } else {
            $web = test_input($_POST["web"]);
        }
        if (empty($_POST["tipodoc"])) {
            $tipodocErr = "You must select 1 or more";
        } else {
            $tipodoc = $_POST["tipodoc"];
        }
        if (empty($_POST["username"])) {
            $usernameErr = "username is required";
        } else {
            $username = test_input($_POST["username"]);
        }
        if (empty($_POST["password"])) {
            $passwordErr = "password is required";
        } else {
            $password = test_input($_POST["password"]);
        }
        if (empty($_POST["confirmpassword"])) {
            $confirmpasswordErr = "confirmpassword is required";
        } else {
            $confirmpassword = test_input($_POST["confirmpassword"]);
        }
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['tipodoc']) && isset($_POST['num'])) {
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $tipodoc = $_POST['tipodoc'];
        $num = $_POST['num'];

        if (
            isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])
            && isset($_POST['confirmpassword']) && isset($_POST['archivo'])
        ) {
            $archivo = $_POST['archivo'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];
            if (empty($password) && empty($confirmpassword)) {
                $display = '<p>Las contrase??as no pueden estar vac??as.</p>';
            } else {
                if ($password == $confirmpassword) {
                    $display = '<p>El usuario se ha registrado correctamente</p>';
                } else {
                    $display = '<p>Las contrase??as no coinciden</p>';
                }
            }
        } else {
            $display = '<p>No se han completado todos los campos</p>';
        }
    }
    #endregion

    ?>


    <form method="post" style='overflow-x: hidden !important;' class="form-register" id="style-5" enctype="multipart/form-data">
        <div style="width: 55vh; display: flex; justify-content: center; flex-direction: column;">
            <!-- Nombre -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="name">Nombre:</label>
                <input class="r-options" type="text" name="name" id="name" required="required" pattern="(/^[a-z ,.'-]+{3,30}$/i)" maxlength=20 title="Escriba el nombre">
            </div>
            <!-- Apellido -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="lastname">Apellido:</label>
                <input class="r-options" type="text" name="lastname" id="lastname" required="required" pattern="(/^[a-z ,.'-]+{3,30}$/i)" maxlength=20 title="Escriba apellidos">
            </div>
            <!-- Correo -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="correo">Correo:</label>
                <input class="r-options" type="email" name="email" id="email" required="required" pattern="([a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4})" maxlength=40>
            </div>
            <!-- Tipo de documento -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="tipodoc">Tipo de Documento:</label>
                <select name="tipDoc" required="required">
                    <?php $tiposdoc = SeleccionarTipoDocDB();
                    foreach ($tiposdoc as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_tipdoc'] ?>"><?php echo $opciones['tip_doc'] ?></option>
                    <?php

                    }
                    ?>
                </select>
            </div>
            <!-- Numero de documento -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="num">Numero de Documento:</label>
                <input class="r-options" type="text" name="num_doc" id="num_doc" required="required" pattern="(^[0-9]{8,10}$)" maxlength=10 title="Escriba el numero de documento">
            </div>
            <!-- Direccion -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="direccion">Direcci??n:</label>
                <input class="r-options" type="text" name="direccion" id="direccion" required="required" pattern="(^[#.0-9a-zA-Z\s,-]+$)" maxlength=30 title="Escriba la direcci??n correctamente">
            </div>
            <!-- Numero de hijos -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="numhijos">Numero de hijos:</label>
                <select name="numhijos" id="numhijos">
                    <?php
                    $canthijos = SeleccionarCanHijos();
                    foreach ($canthijos as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_cant_hijos'] ?>" required="required"><?php echo $opciones['cant_hijos'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <!-- Estado civil -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="estadocivil">Estado civil:</label>
                <select name="estCivil" id="estCivil" required="required">
                    <?php
                    $estadocivil = SeleccionarEstadoCivilDB();
                    foreach ($estadocivil as $opciones) {
                    ?>
                        <option value="<?php echo $opciones['id_est_civil'] ?>" required="required"><?php echo $opciones['est_civil'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <!-- Color favorito del usuario -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for='color'>Color favorito:</label>
                <input class="r-options" type='color' name='color' style="height: 40px;" id='color' required='required'>
            </div>
            <!-- Foto -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="archivo">Foto:</label>
                <input type="file" name="archivo" id="archivo" accept="image/*" required="required" /><br><br>
            </div>
            <!-- Fecha de nacimiento -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="fecha_nac">Fecha de nacimiento:</label>
                <input class="r-options" type="date" name="fecha_nac" id="fecha_nac" required="required">
            </div>
            <!-- usuario -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="username">Usuario:</label>
                <input class="r-options" type="text" name="username" id="username" required="required" pattern="^[a-z0-9_-]{3,16}$" maxlength=16 title="Escriba usuario sin espacios y tildes, mas de 3 y menos de 13  caracteres">
            </div>
            <!-- Contrase??a -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="password">Contrase??a:</label>
                <input class="r-options" type="password" name="password" id="password" required="required" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" maxlength=20 title="m??s de 8 caracteres, 1 minuscula, mayuscula, n??mero y caracter especial">
            </div>
            <!-- Confirmar contrase??a -->
            <div style="display:flex; align-items:center; justify-content: space-between;">
                <label for="confirmpassword">Confirmar contrase??a:</label>
                <input class="r-options" type="password" name="confirmpassword" id="confirmpassword" required="required" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" maxlength=20 title="m??s de 8 caracteres, 1 minuscula, mayuscula, n??mero y caracter especial">
            </div>
            <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
            <div style="display:flex; align-items:center; justify-content: center;">
                <input type="submit"  name="btnRegistrar" value="Registrarse" class='button-r'>
            </div>
        </div>
    </form>

    <?php

    #region CargarImagen
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
                echo '<script>alert("Error. La extensi??n o el tama??o de los archivos no es correcta")</script>';
            } else {
                //Imagen concuerda, Entra
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = '../uploaded_files/' . $fileNameNew;
                if (move_uploaded_file($temp, $fileDestination)) {
                    //Permisos
                    $_SESSION['archivo'] =  $fileDestination;
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
                } else {
                    echo '<script>alert("Error. Imagen no subida")</script>';
                }
            }
        }
    }
    #endregion


    #region GuardarDatos

    if (isset($_POST['name'])) {
        if (
            preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['password']) &&
            preg_match("/^[a-z0-9_-]{3,16}$/", $_POST['username']) &&
            preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['confirmpassword']) &&
            preg_match("/^[a-zA-Z\s]+$/", $_POST['name']) &&
            preg_match("/^[a-zA-Z\s]+$/", $_POST['lastname']) &&
            preg_match("/^[0-9]{8,10}$/", $_POST['num_doc']) &&
            preg_match("/^[#.0-9a-zA-Z\s,-]+$/", $_POST['direccion']) &&
            preg_match("/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/", $_POST['email'])
        ) {


            $_SESSION['username'] =  $_POST['username'];
            $_SESSION['password'] =  $_POST['password'];
            $_SESSION['confirmpassword'] =  $_POST['confirmpassword'];
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['lastname'] =  $_POST['lastname'];
            $_SESSION['fecha_nac'] =  $_POST['fecha_nac'];
            $_SESSION['color'] =  $_POST['color'];
            $_SESSION['email'] =  $_POST['email'];
            $_SESSION['tipDoc'] =  $_POST['tipDoc'];
            $_SESSION['num_doc'] =  $_POST['num_doc'];
            $_SESSION['numhijos'] =  $_POST['numhijos'];
            $_SESSION['direccion'] =  $_POST['direccion'];
            $_SESSION['estCivil'] =  $_POST['estCivil'];


            if ($_POST['password'] == $_POST['confirmpassword']) {
                loadImage();
                $insertuser = RegistrarUsuarioDB(
                    $_SESSION['username'],
                    $_SESSION['password'],
                    $_SESSION['name'],
                    $_SESSION['lastname'],
                    $_SESSION['fecha_nac'],
                    $_SESSION['color'],
                    $_SESSION['email'],
                    $_SESSION['tipDoc'],
                    $_SESSION['num_doc'],
                    $_SESSION['numhijos'],
                    $_SESSION['archivo'],
                    $_SESSION['direccion'],
                    $_SESSION['estCivil']

                );
                session_destroy();
                if ($insertuser) {
                    echo '<script>alert("Usuario Registrado")</script>';
                    echo '<script>window.location.href="login.php"; </script>';
                } else {
                    echo '<script>alert("Error. credenciales incorrectas")</script>';
                    echo '<script>window.location.href="signup.php"; </script>';
                }
            }
        } else {
            echo "<p>No coinciden con formato solicitado</p>";
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