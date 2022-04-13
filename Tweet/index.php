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



    echo '<div style="width: 90vw; display: flex; justify-content: space-around; padding: 0 !important; margin-bottom: 0 !important;">';
    echo '<form method="post" style="display: flex; flex-direction: row !important; width: 98vw; justify-content: space-around !important;">';
    echo '<input type="submit" name="todosarticulos" value="Todos los artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="misarticulos" value="Mis artículos" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '<input type="submit" name="creararticulo" value="Crear artículo" style="background-color: #61C1EB; color: white; padding: 5px; cursor: pointer;">';
    echo '</form>';
    echo '</div>';


    if (isset($_POST['todosarticulos'])) {
        $data = MostrarTweet($CONN);
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

        // function EliminarT()
        // {
        //     $CONN = ConexionDB();
        //     if (isset($_POST['btneliminar'])) {
        //         $deletetweet =  EliminarTweet($CONN, $_POST['idtuit']);
        //         if ($deletetweet) {
        //             echo '<script>alert("Tweet eliminado")</script>';
        //             echo '<script>window.location.href="index.php"; </script>';
        //         } else {
        //             echo '<script>alert("Error eliminando el tweet")</script>';
        //         }
        //     }
        // }
    } else if (isset($_POST['misarticulos'])) {
        $data = MostrarTweetU($CONN);
        if ($data == null) {
            echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #cccc !important; height: 50vh !important; width: 98vw; overflow: hidden;" id="style-5">';
            echo "<div class='force-overflow'>";
            echo "<h1>No hay Tweets</h1>";
            echo "</div>";
            echo "</div>";
        } else {
            echo ' <div class="i-tweet" style="margin-top: 0 !important; background-color: #cccc !important; height: 50vh !important; width: 98vw;" id="style-5">';
            foreach ($data as $row) {
                if (isset($_SESSION['user'])) {
                    if ($_SESSION['user'] == $row['usuario']) {
                        $htmlm = '<div style="background-color: ' . $_SESSION['color'] . ' !important;">
                                    <form method="POST">
                                        <div style="display: flex; width: 50vw; justify-content: center; align-items: center;">
                                            <div style="padding-right: 10px; width: 10%;">
                                                <h3 style="margin-top: 0 !important;">Artículo:</h3>
                                                <p style="padding-top: 12px;">Es público:</p>
                                            </div>
                                            <div style="display: flex; flex-direction: column; width: 80%;">
                                                <p name="mensajetuit" style="border:1px solid #ccc; padding: 5px;">' . $row['mensaje'] . '</p>
                                                <input  name="idtuit" value="' . $row['idtuit'] . '"> 
                                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                                    <p style="margin: 0 !important; padding: 0 !important;" value="a">'. ($row['estado']== 1 ? 'Sí' : 'No') .'</p>
                                                    <p style="margin: 0 !important; padding: 0 !important;">Fecha de tuit: ' . $row['fecha'] . '</p>
                                                </div>
                                                <input style="background-color: purple; color: white;" type="submit" name="publicar" value="Publicar"> 
                                            </div>
                                        </div>
                                    </form>
                                </div>';
                        echo $htmlm;
                    }
                    // DespublicarTweet();
                    // EliminarT();
                    // if(isset($_POST['publicar'])){
                        PublicarTweet($row['idtuit']);
                    // }else{
                    //     echo '<script>alert("No puedes publicar un tweet")</script>';
                    // }
                }
            }
            echo '</div>';

            
        }
    } else if (isset($_POST['creararticulo'])) {
        echo '<script>window.location.href="tweet.php"; </script>';
    } else {
        $data = MostrarTweet($CONN);
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

    function PublicarTweet($id){
        $CONN = ConexionDB();

        if(isset($_POST['publicar'])){
            $publicar = Publicar($CONN, $id);
            if ($publicar) {
                echo '<script>alert("Artículo publicado")</script>';
                echo '<script>window.location.href="index.php"; </script>';
            } else {
                echo '<script>alert("Error publicando el artículo")</script>';
            }
        }
    }

    // function DespublicarTweet(){
    //     $CONN = ConexionDB();

    //     if(isset($_POST['despublicar'])){
    //         $despublicar = Despublicar($CONN, $_POST['idtuit']);
    //         if ($despublicar) {
    //             echo '<script>alert("Artículo despublicado")</script>';
    //             echo '<script>window.location.href="index.php"; </script>';
    //         } else {
    //             echo '<script>alert("Error despublicando el artículo")</script>';
    //         }
    //     }
    // }

    // function EliminarT(){
    //     $CONN = ConexionDB();

    //     if(isset($_POST['btneliminar'])){
    //         $deletetweet =  EliminarTweet($CONN, $_POST['idtuit']);
    //         if ($deletetweet) {
    //             echo '<script>alert("Artículo eliminado")</script>';
    //             echo '<script>window.location.href="index.php"; </script>';
    //         } else {
    //             echo '<script>alert("Error eliminando el artículo")</script>';
    //         }
    //     }
    // }
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