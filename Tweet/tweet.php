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

    $CONN = ConexionDB();
    
    LimpiarEntradas();
    $tweet = $_POST['tweet'] ?? '';
    ?>

    <form method="post">
        <div class="form-register" style="overflow: hidden; width: 100%; height: 75vh;">
            <h1>Tweet</h1>
            <p style="font-size: 12px; ">Recuerda que tu tweet no debe superar los 140 caracteres<span style="color: red;">*</span></p>
            <div style="display: flex; justify-content: center; align-items: center;">
                <label for="tweet">Tweet: </label>
                <textarea name="tweet" id="tweet" cols="30" style="width: 70vw; height: 30vh;" rows="10" maxlength="140" placeholder="Escribe tu tweet"></textarea>
            </div>
            <div>
                <input type="submit" value="Tweet">
            </div>
    </form>

    
    <?php

    
    $tweet = $_POST['tweet'] ?? '';
    $estado = 1;
    // $DateAndTime = date('m-d-Y');

    if(isset($_POST['tweet'])){
        $savet = GuardarTweet($CONN, $tweet, $_SESSION['iduser'], $estado);
        if($savet){
            echo '<script>alert("Tweet creado")</script>';
            echo '<script>window.location.href="index.php"; </script>';
        }else{
            echo '<script>alert("Error publicando el tweet")</script>';
        }
    }


    ?>

</body>

</html>