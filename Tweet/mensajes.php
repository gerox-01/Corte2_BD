<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Aplicativo Linea de Prof 2</title>
</head>
<!-- body -->    
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li>
                    <a href="index.php"><button type="button" class="btn btn-primary" name><img height="35" src="../uploaded_files/"></button></a>    
                </li>
                <li class="nav-item">
                    <div style="width: 5px"></div>
                </li>
                <li class="nav-item">
                    <a href="mensajes.php"><button type="button" class="btn btn-primary" name>VER MENSAJES</button></a> <br>     
                </li>
                <li class="nav-item">
                    <div style="width: 5px"></div>
                </li>
                <li class="nav-item">
                    <a href="perfil.php"><button type="button" class="btn btn-primary" name>MI PERFIL</button></a><br> 
                </li>
                <li class="nav-item">
                    <div style="width: 5px"></div>
                </li>
                <li class="nav-item">
                    <a href="articulos.php"><button type="button" class="btn btn-primary" name>VER ARTICULOS</button></a>
                </li>
                <li class="nav-item">
                    <div style="width: 5px"></div>
                </li>
            </ul>
        </div>
    </nav>
    <div> 
    </div>
    <style>
        .header {
        display: flex;
        }

        .logo {
        float: right;
        }
    </style>

<?php
    require_once "lib/tools.php";
    require_once "lib/db_tools.php";

    require_once "funcionesCSRF.php";
    GenerarAnctiCSRF();
 
     IniciarSesionSegura();
    //  MostrarErrores();   
     $CONN=ConexionDB();

     if (!isset($_SESSION['username'])) {
         header("Location: login.php");
     }
?>
<div style="height: 150%; width: auto; padding: 70; background-color: #AECBDE;">
    <h1>Mensajes</h1>
    <form method="post" enctype="multipart/form-data">
        <b> Enviar Mensajes </b><br>
        <label form = "cmbDestino">Destinatario: </label>
        <select name="cmbDestino" id="cmbDestino">
            <?php
                $usuarios = ListarUsuarios($CONN,$_SESSION['username']);

                foreach($usuarios as $key => $value){
                    echo '<option value="'. $value['usuario'] .'">'.
                        $value['nombres'] .' '. $value['apellidos'] .'</option>';
                }
            ?>
        </select>   
        <br><br>
        <label for="txtMensaje"> Mensaje: </label>
        <input type="text" name="txtMensaje" id="txtMensaje"><br><br>

        <label for="fulAdjunto">Archivo:</label>
        <input type="file" name="fulAdjunto" id="fulAdjunto"><br><br>
        <!-- <input type="hidden" name="anticsrf" value="<?php echo $_SESSION['anticsrf'];?>"> -->
        <input type="submit" name="btnEnviar" value="Enviar"class="btn btn-primary"><br><br>
        <input type="submit" name="btnListarMenEnviados" Value="Listar Mensajes Enviados" class="btn btn-primary">
        <input type="submit" name="btnListarMenRecibidos" Value="Listar Mensajes Recibidos" class="btn btn-primary"><br>
    </form>
        <?php
        
            if(isset($_POST['btnEnviar'])){

                if(isset($_FILES['fulAdjunto']))
                {
                    $fileTmpPath = $_FILES['fulAdjunto']['tmp_name']; 
                    $fileName = $_FILES['fulAdjunto']['name'];
                    $files_folder = './files/';
                    if(!file_exists($files_folder)) {
                        mkdir($files_folder);
                    }

                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    $permitidas = ['jpg','gif','png','jpeg','pdf'];
                    if(in_array($fileExtension, $permitidas)){

                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $dest_path = $files_folder . $newFileName;

                        if(move_uploaded_file($fileTmpPath, $dest_path))
                        {
                            echo '<br/>Archivo subido.<br/>';

                            $img = imagecreatefromjpeg($dest_path);
                            imagejpeg($img, $dest_path, 100);
                            imagedestroy($img);

                            echo '<br>INFORMACION EXIF<br>';
                            $exif2 = exif_read_data($dest_path);
                            echo '<br><br>';
                            
                            $usuario_origen =  $_SESSION['username'];     
                            $usuario_destino = $_POST['cmbDestino'];   
                            $texto = $_POST['txtMensaje'];  
                            $archivo = $dest_path;
                            date_default_timezone_set('America/Bogota');
                            $fechaenvio = date('m-d-Y h:i:s a', time());

                            // echo '<br>';
                            // echo 'Origen: ' . $usuario_origen . '<br>';
                            // echo 'Destino: ' . $usuario_destino . '<br>';
                            // echo 'Texto: ' . $texto . '<br>';

                            EnviarMensaje($CONN,$usuario_origen,$usuario_destino,$texto,$fechaenvio,$archivo);
                        }
                        else {
                            echo '<br/>Archivo no se almaceno.<br/>';
                        }
                    }
                }
            }
            if(isset($_POST['btnListarMenRecibidos'])){ 
                echo '<b> Mensajes Recibidos </b><br>';
                $mensajes_recibidos = ListarMensajesRecibidos($CONN, $_SESSION['username']);
                foreach($mensajes_recibidos as $key =>$value){
                    echo '<b> De ' . $value['Usuario_origen'] . ' : ' . '</b>' . $value['Texto'] . '<br>';
                    echo '<b> De ' . 'Fecha'. '</b>' . $value['FechaEnvio'] . '<br>' ;
                    echo '<b>' . 'Link: '. '</b>'. $value['ArchivoAdjunto'] . '<br>';

                    $registros=ConexionDB()->query("SELECT  `Foto` FROM `usuarios`WHERE Usuario='".$value['Usuario_origen']."'")->fetchAll(PDO::FETCH_OBJ); 
                    foreach($registros as $persona){
                        echo '<b>' . 'Destinatario: '. '</b>' . '<img height=50" src="'.$persona->Foto.' ">.<br><br>';
                    }
                }
            }
            if(isset($_POST['btnListarMenEnviados'])){ 
                echo '<b> Mensajes Enviados </b><br>';


                $mensajes_enviados = ListarMensajesEnviados($CONN, $_SESSION['username']);
                foreach($mensajes_enviados as $key =>$value){
                    $archivo = $value['ArchivoAdjunto'];
                    echo '<b> Para ' . $value['Usuario_destino'] . ' : ' . '</b>' . $value['Texto'] . '<br>';
                    echo '<b>' . 'Fecha: '. '</b>' . $value['FechaEnvio'] . '<br>' ;
                    echo '<b>' . 'Link: '. '</b>'. $value['ArchivoAdjunto'] . '<br>';
                    
                    $registros=ConexionDB()->query("SELECT  `Foto` FROM `usuarios`WHERE Usuario='".$value['Usuario_destino']."'")->fetchAll(PDO::FETCH_OBJ); 
                    foreach($registros as $persona){
                        echo '<b>' . 'Destinatario: '. '</b>' . '<img height=50" src="'.$persona->Foto.' ">.<br><br>';
                    }
                }
            }
        ?>     
</div>
</body>
