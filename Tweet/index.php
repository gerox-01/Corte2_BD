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
    if ($CONN != NULL) {
        $tweet = MostrarTweet($CONN);
        if ($tweet != NULL) {
            foreach ($tweet as $key => $value) {
                global $tuit;
                $tuit = $value['tweet'];
            }
        }
    } else {
        echo "<div class='i-tweet' style='background-color: #ccc !important;' id='style-5' >";
        echo "<div class='force-overflow'>";
        echo "<h1>No hay Tweets</h1>";
        echo "</div>";
        echo "</div>";
    }

    #region Tweets Nuevo Formato
    $color = color($CONN, $user);
    echo "<div class='i-tweet' style='background-color: $color !important;' id='style-5'>";
    echo "<div style='display: flex; height: auto Im !important;'>";
    echo '<div style="displa: flex; flex-direction: column; justify-content: center;padding-right: 20px; color: #ccc; text-transform: uppercase;">';
    echo "<h2>" . $value['usuario'] . "</h2>";
    echo '<img style="height:100px; width: 100px;"  src="' . $_SESSION['foto'] . '">';
    echo "</div>";
    echo "<div class='i-card' style='height: auto I !important;'>";
    echo "<div class='i-card-body'>";
    echo "<p style='color: #cccc'> " . $value['tweet'] . "</p>";
    echo "<p> <small> " . $value['fecha'] . "</small> </p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    #endregion

    #region Tweets
    // if ($tweet == 'Hola') {
    //     echo "<div class='i-tweet' style='background-color: $color !important;' id='style-5' >";
    //     echo "<div class='force-overflow'>";
    //     echo "<h1>No hay Tweets</h1>";
    //     echo "</div>";
    //     echo "</div>";
    // } else {
    //     $userTweet = $_SESSION['username'];
    //     if (count($tweet) > 0) {
    //         echo "<div class='i-tweet' style='background-color: $color !important;' id='style-5'>";
    //         echo "<div class='force-overflow'>";
    //         foreach ($tweet as $t) {
    //             $tweetS = explode(":", $t);
    //             if (isset($tweetS) && count($tweetS) > 2) {
    //                 echo "<div class='i-card'>";
    //                 echo "<div class='i-card-header'>";
    //                 echo "<h2>" . $tweetS[0] . "</h2>";
    //                 echo "<p>" . $tweetS[2] . "</p>";
    //                 echo "</div>";
    //                 echo "<div class='i-card-body'>";
    //                 echo "<p>" . $tweetS[1] . "</p>";
    //                 echo "</div>";
    //                 echo "</div>";

    //                 if ($tweetS[0] == $userTweet) {
    //                     echo '<form method="get" style="margin-top: 0 !important;" >';
    //                     echo '    <div style="padding: 0 !important; margin: 0 !important;" >';
    //                     echo '        <input type="hidden" name="tweet" value="'. $tweetS[1] .'">';
    //                     echo '        <input type="hidden" name="date" value="'. $tweetS[2] .'">';
    //                     echo '        <input style="background: red; color: white; font-weight: bold; border-radius: 10px !important;" type="submit" value="Eliminar">';
    //                     echo '    </div>';
    //                     echo '</form>';
    //                     if (isset($_GET['tweet'])) {
    //                         $tweet = $_GET['tweet'];
    //                         $date = $_GET['date'];
    //                         eliminarTweet($_SESSION['username'], $tweet, $date);
    //                     }
    //                 }
    //             }
    //         }
    //         echo "</div>";
    //         echo "</div>";
    //     } else {
    //         echo "<div class='i-tweet' id='style-5'>";
    //         echo "<div class='force-overflow'>";
    //         echo "</div>";
    //         echo "</div>";
    //     }
    // }
    #endregion
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