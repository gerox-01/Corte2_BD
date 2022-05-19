<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">

    <title>Tweet</title>
</head>

<body>

    <?php
    require_once './nav.php';
    require_once './lib/tools.php';
    require_once './lib/db_tools.php';
    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();

    $user = $_SESSION['username'] ?? '';

    if($user == '' || $user == null){
        header('Location: login.php');
        die();
    }
    

    LimpiarEntradas();
    $tweet = $_POST['tweet'] ?? '';

    echo '<div style="width: 90vw; display: flex; justify-content: space-around; padding: 0 !important; margin-bottom: 0 !important;">';
    echo '<form method="post" style="display: flex; flex-direction: row !important; width: 98vw; justify-content: space-around !important;">';
    echo '<input type="hidden" name="anticsrf" value="' . $_SESSION['anticsrf'] . '">';
    echo '<input type="submit" name="todosarticulos" value="Todos los artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="misarticulos" value="Mis artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="creararticulo" value="Crear artículo" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '</form>';
    echo '</div>';

    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
        if (isset($_POST['exit'])) {
          session_destroy();
          header('Location: tweet.php');
        }
      }


    if (isset($_POST['todosarticulos'])) {
        echo '<script>window.location.href = "index.php";</script>';
    } else if (isset($_POST['misarticulos'])) {
        echo '<script>window.location.href = "misarticulos.php";</script>';
    } else if (isset($_POST['creararticulo'])) {
        echo '<script>window.location.href="tweet.php";</script>';
    }

    ?>

    <form method="post">
        <div class="form-register" style="overflow: hidden; width: 100%; height: 50vh;">
            <h1>Tweet</h1>
            <p style="font-size: 12px; ">Recuerda que tu tweet no debe superar los 140 caracteres<span style="color: red;">*</span></p>
            <div style="display: flex; justify-content: center; align-items: center;">
                <label for="tweet" style="padding-right: 30px;">Tweet: </label>
                <textarea name="tweet" id="tweet" cols="30" style="width: 70vw; height: 20vh;" rows="10" maxlength="140" placeholder="Escribe tu tweet"></textarea>
            </div>
            <div style="display: flex; width: 100%; justify-content: space-around;">
                <!-- Checkbox -->
                <div style="display: flex; justify-content: center; align-items: center;">
                    <span class="text" style="padding-right: 30px;">Es público: </span>
                    <input type="checkbox" name="checkbox" id="checkbox" style="width: 20px; height: 20px; cursor: pointer;">
                </div>
                <input type='hidden' name='anticsrf' value=".$_SESSION['anticsrf'].">
                <input type="submit" name="crearTweet" style="color: white; background-color: purple; width: 10rem; cursor: pointer;" value="Crear">
            </div>
        </div>
    </form>


    <?php


    $tweet = $_POST['tweet'] ?? '';
    // $DateAndTime = date('m-d-Y');

    if (isset($_POST['tweet'])) {

        if (isset($_POST['crearTweet'])) {
            if (!empty($_POST['checkbox'])) {
                $estado = 1;
            } else {
                $estado = 0;
            }


            $savet = GuardarTweet($tweet, $_SESSION['iduser'], $estado);
            if ($savet) {
                echo '<script>alert("Tweet creado")</script>';
                echo '<script>window.location.href="index.php"; </script>';
            } else {
                echo '<script>alert("Error publicando el tweet")</script>';
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