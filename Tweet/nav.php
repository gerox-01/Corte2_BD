<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="styleheet" href="css/styles.css">
</head>

<body>

    <?php
    #region Conexión y ObtenerDatos
    require_once './lib/tools.php';
    require_once './lib/db_tools.php';

    IniciarSesionSegura();

    $CONN=ConexionDB();
    $usuario=$_SESSION['username'] ?? '';
    
    if($CONN!=NULL){
        $datosuser=ObtenerUsuarioDB($CONN, $usuario);
        if ($datosuser!=NULL) {
            foreach ($datosuser as $key => $value) {
                global $colorr;
                $colorr = $value["color"];
            }
        }
    }

    $_SESSION['foto'] = $value['foto'] ?? '';
    #endregion
    ?>

    <nav style='overflow-y: auto; height: 8rem;'>
        <div>
            <!-- Navigación -->
            <div style="display: flex; flex-direction: row; justify-content: space-around;  align-items: center;  margin-top: 10px;">
                <?php
                if (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] != "") {
                    echo "<div>
                    <a href='index.php' style='font-weight: bold; font-size: 1.2rem;'>🏠 Inicio</a>
                </div>
                <div>
                    <a href='tweet.php'>🐦Tweet</a>
                </div>
                <div>
                    <a href='perfil.php'>💬 Mensajes</a>
                </div>
                <div>
                    <a href='perfil.php'>🔎 Perfil</a>
                </div>
                <div>
                    <a href='./restorepassword.php'>🖊 Cambiar contraseña</a>
                </div>
                <div>
                    <form method='post' style='margin-top: 0 !important;' >
                        <input type='submit' style='border: 5px solid #0000; cursor: pointer; ' value='🔓 Salir' name='exit' id='exit'>
                    </form>
                </div>";
                } else {
                    echo "
                    <div>
                        <a href='login.php'>🙋‍♂️ Ingresar</a>
                    </div>
                    <div>
                        <a href='signup.php'>🗳 Registrar</a>
                    </div>";
                }

                if (isset($_POST['exit'])) {
                    session_destroy();
                    header("Location: index.php");
                }
                ?>
            
        </div>
            <!-- Datos Usuario -->
            <div style="display: flex; justify-content: center;">
                <?php
                #region MostrarDatosUsuario
                if (isset($_SESSION['username'])) {
                ?>
                    <!-- Nombre usuario -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Nombre: ⬇</strong></p>
                        <p><?php echo $value['nombre']; ?></p>
                    </div>
                    <!-- Apellido del usuario -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Apellido: ⬇</strong></p>
                        <p><?php echo $value['apellido']; ?></p>
                    </div>
                    <!-- Correo del usuario -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Correo: ⬇</strong></p>
                        <p><?php echo $value['correo']; ?></p>
                    </div>
                    <!-- Tipo de documento -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Dirección: ⬇</strong></p>
                        <p><?php echo $value['direccion']; ?></p>
                    </div>
                    <!-- Numero de documento -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Cantidad hijos: ⬇</strong></p>
                        <p><?php echo $value['cantidad_hijos']; ?></p>
                    </div>
                    <!-- Cantidad de hijos -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Estado civil: ⬇</strong></p>
                        <p><?php echo $value['estado_civil']; ?></p>
                    </div>
                    <!-- Color -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Color: ⬇</strong></p>
                        <p><?php echo $value['color']; ?></p>
                    </div>
                    <!-- Foto del usuario -->
                    <div style='margin-right: 15px;'>
                        <p><strong>Foto: ⬇</strong></p>
                        <p><?php echo '<img style="height:100px; width: 100px;"  src="' . $value['foto'] . '">'; ?></p>
                    </div>
                <?php
                }
                #endregion
                ?>
            </div>
        </div>
    </nav>


</body>

</html>