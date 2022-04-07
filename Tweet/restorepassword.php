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
    ?>

    <form method="post">
        <div class="form-register">
            <h1>Cambiar contraseña</h1>
            <div>
                <label for="password">Contraseña actual: </label>
                <input type="password" name="password" required="required" id="password" placeholder="Contraseña actual">
            </div>
            <div>
                <label for="password">Contraseña nueva: </label>
                <input type="password" name="passwordn" required="required" id="passwordn" placeholder="Contraseña nueva" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" title="más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial">
            </div>
            <div>
                <label for="confirmpassword">Confirmar contraseña: </label>
                <input type="password" name="confirmpassword" required="required" id="confirmpassword" placeholder="Confirmar contraseña" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" title="más de 8 caracteres, 1 minuscula, mayuscula, número y caracter especial">
            </div>
            <div>
                <input type="submit" value="Cambiar contraseña">
            </div>
    </form>

    <?php
    require_once('./lib/tools.php');

    $_SESSION['username'] =  $_SESSION['username'];
    $password = $_POST['password'] ?? '';
    $passwordn = $_POST['passwordn'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if (isset($_POST['passwordn']) == isset($_POST['confirmpassword'])) {
            $msg = restorepassword($_SESSION['username'], $passwordn, $confirmpassword) ? "Contraseña cambiada exitosamente" : "Contraseña no cambiada";

            echo "<div class='alert'>";
            echo "<p>$msg</p>";
            echo "</div>";
    }else{
        echo "<div class='alert'>";
        echo "<p>No coinciden!</p>";
        echo "</div>";
    }
    ?>

</body>

</html>