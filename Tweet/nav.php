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

    $CONN = ConexionDB();
    $usuario = $_SESSION['username'] ?? '';

    if ($CONN != NULL) {
        $datosuser = ObtenerUsuarioDB($CONN, $usuario);
        if ($datosuser != NULL) {
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
            <div style="display: flex; flex-direction: row; justify-content: space-around;  align-items: start;">
                <?php
                if (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] != "") {
                    echo "<div>
                            <a href='index.php' style='font-weight: bold; font-size: 1.2rem;'>🏠</a>
                        </div>
                        <img src='" . $_SESSION['foto'] . "' style='width: 50px; height: 50px; border-radius: 50%;'>
                        <div style='display: flex; align-items: start;'>
                                <p style='font-weight: bold; text-transform: uppercase;'>" . $_SESSION['nombre'] . '  ' . $_SESSION['apellido'] . "</p>
                            <form method='post' style='margin-top: 0 !important;' >
                                <input type='submit' style='border: 5px solid #0000; cursor: pointer; ' value='🔓 Cerrar sesión' name='exit' id='exit'>
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
            <div style="display: flex; justify-content: space-around; width: 90vw; padding: 0 !important; margin: 0 !important;">
                <?php
                // Boton de ver articulos
                if (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] != "") {
                    echo "<form method='post' style='margin-top: 0 !important;' >
                            <input type='submit' style='border: 5px solid #0000; cursor: pointer; ' value='📚 Ver articulos' name='verarticulos' id='verarticulos'>
                        </form>";
                }

                // Boton de ver mensajes
                if (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] != "") {
                    echo "<form method='post' style='margin-top: 0 !important;' >
                                <input type='submit' style='border: 5px solid #0000; cursor: pointer; ' value='📨 Ver mensajes' name='vermensajes' id='vermensajes'>
                            </form>";
                }

                // Boton de ver perfil
                if (isset($_SESSION['username']) && isset($_SESSION['password']) && $_SESSION['username'] != "") {
                    echo "<form method='post' style='margin-top: 0 !important;' >
                                <input type='submit' style='border: 5px solid #0000; cursor: pointer; ' value='📋 Ver perfil' name='verperfil' id='verperfil'>
                            </form>";
                }
                ?>

                <?php 

                if (isset($_POST['verarticulos'])) {
                    header("Location: index.php");
                }
                if (isset($_POST['vermensajes'])) {
                    header("Location: mensajes.php");
                }
                if (isset($_POST['verperfil'])) {
                    header("Location: perfil.php");
                }
                ?>

            </div>
        </div>
    </nav>


</body>

</html>