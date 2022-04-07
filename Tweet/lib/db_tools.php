<?php

/**
 * Conexión a la base de datos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 27/03/2022
 * @return PDO
 */
function ConexionDB()
{
    $servername = "localhost";
    $database = "corte2bd";
    $username = "root";
    $password = "";

    $sql = "mysql:host=$servername; dbname=$database;";
    $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    try {
        $my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
        return $my_Db_Connection;
    } catch (PDOException $error) {
        echo 'Connection error ' . $error->getMessage();
        return NULL;
    }
}


/**
 * Registra un usuario en la base de datos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 27/03/2022
 * @param string $connection
 * @param string $usuario
 * @param string $clave
 * @param string $nombre
 * @param string $apellido
 * @param date $nacimiento
 * @param number $cantidad_hijos
 * @param color $color
 * @param string $photo 
 * @return boolean
 */
function RegistrarUsuarioDB(
    $my_Db_Connection,
    $usuario,
    $clave,
    $nombre,
    $apellido,
    $fecha_nac,
    $color,
    $correo,
    $id_tip_doc,
    $num_doc,
    $num_hijos,
    $foto,
    $dir,
    $id_est_civ
) {

    $encryptPass = encriptarPassword($clave);

    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $statement = $my_Db_Connection->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                return FALSE;
            } else {
                $my_Insert_Statement =
                    $my_Db_Connection->prepare("INSERT INTO usuarios (usuario, clave, nombres, apellidos, 
            fecha_nac, color, correo, id_tip_doc, num_doc, id_num_hijos, foto, direccion, id_est_civil)
        VALUES (:usuario, :clave, :nombres, :apellidos, 
        :fecha_nac, :color, :correo, :id_tip_doc, :num_doc, :num_hijos, :foto, :direccion, :id_est_civil)");

                $my_Insert_Statement->bindParam(':usuario', $usuario);
                $my_Insert_Statement->bindParam(':clave', $encryptPass);
                $my_Insert_Statement->bindParam(':nombres', $nombre);
                $my_Insert_Statement->bindParam(':apellidos', $apellido);
                $my_Insert_Statement->bindParam(':fecha_nac', $fecha_nac);
                $my_Insert_Statement->bindParam(':color', $color);
                $my_Insert_Statement->bindParam(':correo', $correo);
                $my_Insert_Statement->bindParam(':id_tip_doc', $id_tip_doc);
                $my_Insert_Statement->bindParam(':num_doc', $num_doc);
                $my_Insert_Statement->bindParam(':num_hijos', $num_hijos);
                $my_Insert_Statement->bindParam(':foto', $foto);
                $my_Insert_Statement->bindParam(':direccion', $dir);
                $my_Insert_Statement->bindParam(':id_est_civil', $id_est_civ);

                if ($my_Insert_Statement->execute()) {
                    echo "Nuevo Usuario Creado";
                    return TRUE;
                } else {
                    echo "No se pudo crear Usuario";
                    return FALSE;
                }
            }
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return FALSE;
    }
}


/**
 * Seleccionar los tipos de documentos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 07/04/2022
 * @param string $connection
 * @return array
 */
function SeleccionarTipoDocDB($my_Db_Connection)
{
    $sql = "SELECT `id_tipdoc`, `tip_doc` FROM `tipdoc`";
    $statement = $my_Db_Connection->prepare($sql);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
}

/**
 * Seleccionar los estados civiles
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 07/04/2022
 * @param string $connection
 * @return array
 */
function SeleccionarEstadoCivilDB($my_Db_Connection)
{
    $sql = "SELECT `id_est_civil`, `est_civil` FROM `estadocivil`";
    $statement = $my_Db_Connection->prepare($sql);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
}

/**
 * Seleccionar cantidad de hijos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 07/04/2022
 * @param string $connection
 * @return array
 */
function SeleccionarCanHijos($my_Db_Connection)
{
    $sql = "SELECT `id_cant_hijos`, `cant_hijos` FROM `cantidadhijos`";
    $statement = $my_Db_Connection->prepare($sql);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
}


/**
 * Obtener los datos del usuario
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 28/03/2022
 * @param string $connection
 * @param string $usuario
 * @return array
 */
function ObtenerUsuarioDB($my_Db_Connection, $usuario)
{
    $datos = [];
    $sql = "SELECT `id_usuario`, `usuario`, `clave`, `nombres`, `apellidos`, `fecha_nac`, `color`, `correo`, TP.tip_doc as `tipodoc`, `num_doc`, NH.cant_hijos as `cant_hijos`, `foto`, `direccion`, EC.est_civil as `est_civil` FROM `usuarios` U 
    INNER join tipdoc TP on TP.id_tipdoc = U.id_tip_doc
    inner join cantidadhijos NH on NH.id_cant_hijos = U.id_num_hijos
    inner join estadocivil EC on EC.id_est_civil = U.id_est_civil
    WHERE `usuario` = :usuario";
    $statement = $my_Db_Connection->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    try {
        if ($statement->execute()) {
            $datos = $statement->fetchAll();
            if (count($datos) > 0) {
                return $datos;
            }
        } else {
            return NULL;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
}


/**
 * Validar Login para que el usuario acceda
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 30/03/2022
 * @param string $connection
 * @param string $usuario
 * @param string $clave
 * @return boolean
 */
function ValidarLoginDB($my_Db_Connection, $usuario, $clave){

    try {
        $clavecryp = encriptarPassword($clave);
        $my_Select_Statement =
            $my_Db_Connection->prepare("SELECT `id_usuario`, `usuario`, `clave`, `nombres`, `apellidos`, `fecha_nac`, `color`, `correo`, TP.tip_doc as `tipdoc`, `num_doc`, NH.cant_hijos as `cant_hijos`, `foto`, `direccion`, EC.est_civil as `estcivil` FROM `usuarios` U 
            INNER join tipdoc TP on TP.id_tipdoc = U.id_tip_doc
            inner join cantidadhijos NH on NH.id_cant_hijos = U.id_num_hijos
            inner join estadocivil EC on EC.id_est_civil = U.id_est_civil WHERE `usuario` = :usuario and `clave` = :clavecryp");
        $my_Select_Statement->execute([':usuario' => $usuario, ':clavecryp' => $clavecryp]);
        $user = $my_Select_Statement->fetch(); //user->usuario
        if ($user) {
            $_SESSION['iduser'] = $user['id_usuario'];
            $_SESSION['user'] = $user['usuario'];
            $_SESSION['nombre'] = $user['nombres'];
            $_SESSION['apellido'] = $user['apellidos'];
            $_SESSION['fecha'] = $user['fecha_nac'];
            $_SESSION['color'] = $user['color'];
            $_SESSION['email'] = $user['correo'];
            $_SESSION['tipodoc'] = $user['tipdoc'];
            $_SESSION['numdoc'] = $user['num_doc'];
            $_SESSION['hijos'] = $user['cant_hijos'];
            $_SESSION['foto'] = $user['foto'];
            $_SESSION['direccion'] = $user['direccion'];
            $_SESSION['estadociv'] = $user['estcivil'];
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return FALSE;
    }
}


/**
 * Encriptar la clave
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 30/03/2022
 * @param string $clave
 * @return string
 */
function encriptarPassword($clave)
{
    $encryptPass = md5($clave);
    $password = crypt($clave, $encryptPass); 
            return $password;
}


/** 
 * Guardar un Tweet o Articulo
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 03/04/2022
 * @param string $usuario
 * @param string $tweet
 * @param string $foto
 * @param string $fecha
 * @return boolean
 */
function GuardarTweet($connection, $usuario, $tweet, $fecha, $foto)
{
    $sql = "INSERT INTO tweet (usuario, tweet, fecha, foto) VALUES (:usuario, :tweet, :fecha, :foto)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    $statement->bindParam(':tweet', $tweet);
    $statement->bindParam(':fecha', $fecha);
    $statement->bindParam(':foto', $foto);
    try {
        if ($statement->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return NULL;
    }
}

/**
 * Leer un Tweet o Articulo
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 03/04/2022
 * @param string conexión
 * @return array
 */
function MostrarTweet($connection)
{
    $datostweets = [];

    $sql = "SELECT `tweet`, `fecha`, `usuario`, `foto` FROM `tweet`";
    $statement = $connection->prepare($sql);
    try {
        if ($statement->execute()) {
            $datostweets = $statement->fetchAll();
            if (count($datostweets) > 0) {
                return $datostweets;
            }
        } else {
            return NULL;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
}

/**
 * Obtener color del usuario
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 03/04/2022
 * @param string conexión
 * @param string usuario
 * @return string
 */
function color($conexion, $usuario)
{
    $sql = "SELECT color FROM usuario WHERE usuario = :usuario";
    $statement = $conexion->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            if (count($result) > 0) {
                return $result[0]['color'];
            }
        } else {
            return NULL;
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return NULL;
    }
}
