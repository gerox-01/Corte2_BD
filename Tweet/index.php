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

    $user = $_SESSION['username'];

    $CONN = ConexionDB();

    $data = MostrarTweet($CONN);
    if ($data == null) {
        echo "<div class='i-tweet' style='background-color: #ccc !important;' id='style-5' >";
        echo "<div class='force-overflow'>";
        echo "<h1>No hay Tweets</h1>";
        echo "</div>";
        echo "</div>";
    } else {
        echo ' <div class="i-tweet" style="background-color: #ccc !important; height: auto !important; overflow: hidden;" id="style-5">';
        foreach ($data as $row) {
            $html = ' <div class="i-tweet" style="background-color: ' . $_SESSION['color'] . ' !important; height: 42vh !important;" id="style-5">
                        <div style="display: flex;">
                        <div style="display: flex; flex-direction: column;">
                          <div class="col">
                            <h2 style="text-transform: uppercase;">' . $row['usuario'] . ':' . '</h2> 
                          </div>
                          <div class="col">
                            
                            <img weight="100px" height="100px" class="" src="' . $row['foto'] . '" alt="User Image">
                          </div>
                        </div>
                        <div style="padding-left: 5.5rem; display:flex; flex-direction: column; height:100%; width: 70%; justify-content: space-around; align-items: center;">
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
            if (isset($_SESSION['user'])) {
                if ($_SESSION['user'] == $row['usuario']) {
                    echo '<div class="row">
                          <div class="col">
                          <form method="POST">
                          <div class="row">
                            <div class="col tex-end ">
                                <input hidden name="mensajetuit" value="' . $row['mensaje'] . '"> 
                                <input  hidden name="idtuit" value="' . $row['idtuit'] . '"> 
                                <input style="background-color: red; color: white;" type="submit" name="btneliminar" value="Eliminar"> 
                            </div>
                          </div>
                          </form>
                          </div>
                        </div>';
                }

                EliminarT();
            }
            echo '</div>';
        }
        echo '</div>';
    }

    function EliminarT()
    {
        $CONN = ConexionDB();
        if (isset($_POST['btneliminar'])) {
            $deletetweet =  EliminarTweet($CONN, $_POST['idtuit']);
            if ($deletetweet) {
                echo '<script>alert("Tweet eliminado")</script>';
                echo '<script>window.location.href="index.php"; </script>';
            } else {
                echo '<script>alert("Error eliminando el tweet")</script>';
            }
        }
    }
    ?>
    </div>
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