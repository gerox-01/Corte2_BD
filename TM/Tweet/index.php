<?php

/**
 * Autores: Alejandro Monroy & Gerónimo Quiroga
 * Fecha: 19/03/2022
 * Materia: Linea de profundización 2
 * Descripción: parcial 1
 */


header('Content-Type: text/html; charset=UTF-8');
require_once('./nav.php');
require_once "funcionesCSRF.php";
GenerarAnctiCSRF();

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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">

    <title>T1</title>
</head>

<body>

    <?php
    require_once('./lib/tools.php');
    require_once('./lib/db_tools.php');
    LimpiarEntradas();

    $user = $_SESSION['username'] ?? '';


    if($user == '' || $user == null){
        header('Location: login.php');
        die();
    }
    
    echo '<div style="width: 90vw; display: flex; justify-content: space-around; padding: 0 !important; margin-bottom: 0 !important;">';
    echo '<form method="post" style="display: flex; flex-direction: row !important; width: 98vw; justify-content: space-around !important;">';
    echo '<input type="submit" name="todosarticulos" value="Todos los artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="misarticulos" value="Mis artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="creararticulo" value="Crear artículo" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '</form>';
    echo '</div>';

    if (isset($_POST['todosarticulos'])) {
        $data = MostrarTweet();
        if ($data == null) {
            echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow: hidden;" id="style-5">';
            echo "<div class='force-overflow'>";
            echo "<h1>No hay Tweets</h1>";
            echo "</div>";
            echo "</div>";
        } else {
            echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw;" id="style-5">';
            foreach ($data as $row) {
                $html = ' <div style="background-color: ' . $_SESSION['color'] . ' !important; width: 95vw;">
                        <div style="display: flex; justify-content: center; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                          <div class="col">
                            <h2 style="text-transform: uppercase;">' . $row['usuario'] . ':' . '</h2> 
                          </div>
                          <div class="col">
                            <img weight="100px" height="100px" src="' . $row['foto'] . '" alt="User Image">
                          </div>
                        </div>
                        <div style="padding-left: 5.5rem; display:flex; flex-direction: column; width: 70%; justify-content: space-around; align-items: center;">
                          <div style="border: 1px solid #ccc; padding: 0.5rem;">
                            ' . $row['mensaje'] . ' 
                          </div>
                          <div class="col tex-end ">
                            <i>Fecha de tuit: ' . $row['fecha'] . '</i> 
                          </div>
                        </div>
                        </div>
                        <br>
                        ';
                echo $html;
                echo '</div>';
            }
            echo '</div>';
        }
    } else if (isset($_POST['misarticulos'])) {
        echo "<script>window.location.href='misarticulos.php'; </script>";
    } else if (isset($_POST['creararticulo'])) {
        echo '<script>window.location.href="tweet.php"; </script>';
    } else {
        $data = MostrarTweet();
        if ($data == null) {
            echo '<div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw; overflow: hidden;" id="style-5">';
            echo "<div class='force-overflow'>";
            echo "<h1>No hay Tweets</h1>";
            echo "</div>";
            echo "</div>";
        } else {
            echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #ccc !important; height: 50vh !important; width: 98vw;" id="style-5">';
            foreach ($data as $row) {
                $html = '<div style="background-color: ' . $_SESSION['color'] . ' !important; width: 95vw;">
                        <div style="display: flex; justify-content: center; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                          <div class="col">
                            <h2 style="text-transform: uppercase;">' . $row['usuario'] . ':' . '</h2> 
                          </div>
                          <div class="col">
                            
                            <img weight="100px" height="100px" class="" src="' . $row['foto'] . '" alt="User Image">
                          </div>
                        </div>
                        <div style="padding-left: 5.5rem; display:flex; flex-direction: column; width: 70%; justify-content: space-around; align-items: center;">
                          <div style="border: 1px solid #ccc; padding: 0.5rem;">
                            ' . $row['mensaje'] . ' 
                          </div>
                          <div class="col tex-end ">
                            <i>Fecha de tuit: ' . $row['fecha'] . '</i> 
                          </div>
                        </div>
                        </div>
                        <br>
                        ';
                echo $html;
                echo '</div>';
            }
            echo '</div>';
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