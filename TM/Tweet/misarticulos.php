<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">

    <title>Document</title>
</head>

<body>
    <?php
    require_once './nav.php';
    require_once('./lib/tools.php');
    require_once './lib/db_tools.php';
    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();

    $user = $_SESSION['username'] ?? '';

    if ($user == '' || $user == null) {
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
        echo '<script>window.location.href = "index.php";</script>';
    } else if (isset($_POST['misarticulos'])) {
        echo '<script>window.location.href = "misarticulos.php";</script>';
    } else if (isset($_POST['creararticulo'])) {
        echo '<script>window.location.href="tweet.php";</script>';
    }

    $data = MostrarTweetU();
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
                    $encryptid = encriptarIdTuit($row['idtuit']);
                    $htmlm = '<div style="background-color: ' . $_SESSION['color'] . ' !important;">
                            <form method="POST">
                                <div style="display: flex; width: 85vw; justify-content: center; align-items: center;">
                                    <div style="padding-right: 30px; width: 10%;">
                                        <h3 style="margin-top: 0 !important;">Artículo:</h3>
                                        <p style="padding-top: 12px;">Es público:</p>
                                    </div>
                                    <div style="display: flex; flex-direction: column; width: 80%;">
                                        <p name="mensajetuit" style="border:1px solid #ccc; padding: 5px;">' . $row['mensaje'] . '</p>
                                        <input hidden style="display:none;" id="idtuit" name="idtuit" value="' . $row['idtuit'] . '"> 
                                        <div style="display: flex; justify-content: space-between; align-items: start;">
                                            <p style="margin: 0 !important; padding: 0 !important;" value="a">' . ($row['estado'] == 1 ? 'Sí' : 'No') . '</p>
                                            <p style="margin: 0 !important; padding: 0 !important;">Fecha de tuit: ' . $row['fecha'] . '</p>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <input style="background-color: purple; color: white; cursor: pointer;" type="submit" name="publicar" value="Publicar"> 
                                            <input style="background-color: purple; color: white; cursor: pointer;" type="submit" name="despublicar" value="Despublicar"> 
                                            <input style="background-color: red; color: white; cursor: pointer;" type="submit" name="eliminar" value="Borrar"> 
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>';

                    echo $htmlm;
                }
                $_SESSION['idtuit'] = $_POST['idtuit'] ?? '';
                EliminarT();
            }
        }
        echo '</div>';
        DespublicarTweet();
        PublicarTweet();
    }

    // echo $_GET['idtuit'];

    function PublicarTweet()
    {
        if (isset($_POST['publicar'])) {
            $encrypid = encriptarIdTuit($_SESSION['idtuit']);
            $publicar = Publicar($_SESSION['idtuit']);
            if ($publicar) {
                echo '<p style="color: green">Artículo publicado</p>';
            } else {
                echo '<p style="color: red">Error publicando el artículo</p>';
            }
        }
    }

    function DespublicarTweet()
    {

        if (isset($_POST['despublicar'])) {
            $encrypid = encriptarIdTuit($_SESSION['idtuit']);
            $despublicar = Despublicar($_SESSION['idtuit']);
            if ($despublicar) {
                echo '<p style="color: green">Artículo despublicado</p>';
            } else {
                echo '<p style="color: red">Error despublicando el artículo</p>';
            }
        }
    }

    function EliminarT()
    {
        if (isset($_POST['eliminar'])) {
            $encrypid = encriptarIdTuit($_SESSION['idtuit']);
            $deletetweet =  EliminarTweet($_SESSION['idtuit']);
            if ($deletetweet) {
                echo '<p style="color: green">Artículo eliminado</p>';
            } else {
                echo '<p style="color: red">Error eliminando el artículo</p>';
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

    <script>
        // function EliminarID(){
        // var tweet = document.getElementById('idtuit');
        // var idtuit = tweet.value;
        // tweet.removeAttribute('value');
        // console.log(idtuit);
        // }
    </script>
</body>

</html>