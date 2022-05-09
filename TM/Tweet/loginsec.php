<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://google.com/recaptcha/api.js" async defer></script>

    <title>Log in</title>
</head>

<body>
    <?php
    require_once('./lib/db_tools.php');
    require_once('./nav.php');

    LimpiarEntradas();
    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();

    $CONN = ConexionDB();


    #region CodigoRevisar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {



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
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    #endregion

    ?>

    <h3>
        <?php
        // $Captcha = rand(1000, 9999);
        // echo 'Captcha generado:' . $Captcha;
        ?>
    </h3>

    <div class="formulario">
        <form method="post" action="">
            <div>
                <label for="username">Usuario: </label>
                <input type="text" name="username" id="username" required="required" placeholder="Digite usuario" pattern="^[a-z0-9_-]{3,16}$" maxlength=16 title="Escriba usuario sin espacios y tildes, mas de 3 y menos de 13  caracteres">
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required="required" placeholder="Digite contraseña" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" maxlength=20 title="más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial">
            </div>
            <!-- Captcha -->
            <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6LeJHckfAAAAAG2c6hio14S_Y9vObuxd2Gvb3Mz3"></div>
            <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
            <button name="send" type="submit" value="send">Enviar</button>
        </form>


        <?php
        #region Validar Inicio de Sesión

        if (isset($_POST['send'])) {
            echo '<br>Validando Captcha...<br>';
            $secretKey = '6LeJHckfAAAAAAmlCL4cRhGSWEkVqt_ifM6-Nrmy';
            $captcha = $_POST['g-recaptcha'];
            $ip = $_SERVER['REMOTE_ADDR'];

            var_dump($ip);
            // Chequear captcha en Google
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
            $responseKeys = json_decode($response, true);
            // echo '<br>Respuesta de Google: ' . $responseKeys['success'];
            var_dump($responseKeys);

            // Si la captcha es correcta que escriba lo siguiente
            // if(intval($responseKeys['success'])==1){
            //     if (isset($_POST['username']) && isset($_POST['password'])) {
            //         if ($CONN != NULL) {
            //             $vlogin = ValidarLoginDB($CONN, $_POST['username'], $_POST['password']);
            //             if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['password'])&& 
            //                 preg_match("/^[a-z0-9_-]{3,16}$/", $_POST['username'])) {
            //                 if ($vlogin) {
            //                     $_SESSION['username'] = $_POST['username'];
            //                     $_SESSION['password'] = $_POST['password'];
            //                     header("Location: index.php");
            //                 } else {
            //                     echo "<p>Usuario o clave incorrectos</p>";
            //                 }
            //             } else {
            //                 echo "<p>No coinciden con formato solicitado</p>";
            //             }
            //         }
            //         LimpiarEntradas();
            //     }
            // }else{
            //     echo "<p>Captcha incorrecto</p>";
            // }
        }



        #endregion
        ?>


    </div>



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