<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js?hl=es" async defer></script>

    <title>Log in</title>
</head>

<body>
    <?php
    require_once('./lib/db_tools.php');
    require_once('./nav.php');
    require_once './../../vendor/autoload.php';

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
            <div class="g-recaptcha" data-sitekey="6LeJHckfAAAAAG2c6hio14S_Y9vObuxd2Gvb3Mz3"></div>
            <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
            <button name="send" type="submit" value="send">Enviar</button>
        </form>


        <?php


        // foreach ($_POST as $key => $value) {
        //     echo '<p><strong>' . $key . ':</strong> ' . $value . '</p>';
        // }
        #region Validar Inicio de Sesión

        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
            if (isset($_POST['exit'])) {
              session_destroy();
              header('Location: loginsec.php');
            }
          }

        if (isset($_POST['send'])) {
            $secretKey = '6LeJHckfAAAAAAmlCL4cRhGSWEkVqt_ifM6-Nrmy';
            $response = null;
            // comprueba la clave secreta
            $recaptcha = new \ReCaptcha\ReCaptcha($secretKey);
            $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if ($resp->isSuccess()) :
                // If the response is a success, that's it!
        ?>
                <!-- <h2>Success!</h2> -->
                <!-- <kbd>
                    <pre><?php 
                    // var_export($resp); 
                    if (isset($_POST['username']) && isset($_POST['password'])) {
                        $vlogin = ValidarLoginDB($_POST['username'], $_POST['password']);
                        if (
                            preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['password']) &&
                            preg_match("/^[a-z0-9_-]{3,16}$/", $_POST['username'])
                        ) {
                            if ($vlogin) {
                                $_SESSION['username'] = $_POST['username'];
                                $_SESSION['password'] = $_POST['password'];
                                header("Location: index.php");
                            } else {
                                echo "<p>Usuario o clave incorrectos</p>";
                            }
                        } else {
                            echo "<p>No coinciden con formato solicitado</p>";
                        }
                        LimpiarEntradas();
                    }
                    ?></pre>
                </kbd> -->
                
                <!-- <p>That's it. Everything is working. Go integrate this into your real project.</p> -->
                <!-- <p><a href="/login.php">⤴️ ENTRAR</a></p> -->

                
            <?php
            else :
                // If it's not successful, then one or more error codes will be returned.
            ?>
                <h2>ReCaptcha no valido</h2>
                <!-- <kbd>
                    <pre><?php 
                    // var_export($resp); 
                    ?></pre>
                </kbd> -->
                <!-- <p>Check the error code reference at <kbd><a href="https://developers.google.com/recaptcha/docs/verify#error-code-reference">https://developers.google.com/recaptcha/docs/verify#error-code-reference</a></kbd>. -->
                <!-- <p><strong>Note:</strong> Error code <kbd>missing-input-response</kbd> may mean the user just didn't complete the reCAPTCHA.</p> -->
                <!-- <p><a href="/signup.php">⤴️ REGISTRESE</a></p> -->
        <?php
            endif;
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