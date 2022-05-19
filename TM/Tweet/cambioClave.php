<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/styles.css">

    <title>Cambiar contraseña</title>
</head>

<body>

    <?php
    require_once('././nav.php');
    require_once('./../Tweet/lib/db_tools.php');
    LimpiarEntradas();
    require_once "funcionesCSRF.php";

    $user = $_SESSION['username'] ?? '';

    if ($user == '' || $user == null) {
        header('Location: login.php');
        die();
    }

    $CONN = ConexionDB();

    ?>

    <form method="post">
        <div class="form-register" style="overflow: hidden; width: 50vw; height: auto;">
            <h1>Actualizar contraseña</h1>
            <div>
                <label for="password">Contraseña actual: </label>
                <input type="password" name="password" required="required" id="password" placeholder="Contraseña actual">
            </div>
            <div>
                <label for="password">Contraseña nueva: </label>
                <input type="password" name="passwordn" required="required" id="passwordn" placeholder="Contraseña nueva" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" maxlength=20 title="más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial">
            </div>
            <div>
                <label for="confirmpassword">Confirmar contraseña: </label>
                <input type="password" name="confirmpassword" required="required" id="confirmpassword" placeholder="Confirmar contraseña" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" maxlength=20 title="más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial">
            </div>
            <div>
                <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf']; ?>">
                <input type="submit" class='button-r' name="cambiarClave" value="Actualizar contraseña">
            </div>
    </form>

    <?php



    if ($CONN != NULL) {
        $user = $_SESSION['username'];

        if (isset($_POST['cambiarClave'])) {
            if (
                preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['passwordn'])
                && preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['confirmpassword'])
                && preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $_POST['password'])
            ) {
                if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                    $password = $_POST['password'];
                    $passwordn = $_POST['passwordn'];
                    $confirmpassword = $_POST['confirmpassword'];

                    if ($password != "" && $passwordn != "" && $confirmpassword != "") {
                        if ($passwordn == $confirmpassword) {
                            $result = CambiarClave($user, $password, $passwordn);
                            if ($result) {
                                echo "<p>Contraseña cambiada correctamente</p>";
                            } else {
                                echo "<p>Error al cambiar contraseña</p>";
                            }
                        } else {
                            echo "<p>Las contraseñas no coinciden</p>";
                        }
                    }
                } else {
                    echo "<script>alert('CSRF detectado');</script>";
                }
            }
            echo "<p>La contraseña debe tener más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial</p>";
        }
        $anticsrf = random_int(1000, 9999);
        $_SESSION['anticsrf'] = $anticsrf;
    }
    ?>

</body>

</html>