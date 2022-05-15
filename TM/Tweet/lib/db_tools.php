<?php

//libreria de jwt
use Firebase\JWT\JWT;


/**
 * Conexión a la base de datos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 27/03/2022
 * @return PDO
 */
function ConexionDB()
{
    $servername = "localhost";
    // $database = "corte2bd";
    $password = "";
    $username = "root";
    $database = "tm";
    // $password = "123456";

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


/***
 *    SECCION DE USUARIO!!
 */



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
    $my_Db_Connection = ConexionDB();
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
function SeleccionarTipoDocDB()
{
    $my_Db_Connection = ConexionDB();
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
function SeleccionarEstadoCivilDB()
{
    $my_Db_Connection = ConexionDB();
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
function SeleccionarCanHijos()
{
    $my_Db_Connection = ConexionDB();
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
function ObtenerDatosUsuarioDB($my_Db_Connection, $usuario)
{
    $lista_usuario = [];
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $statement = $my_Db_Connection->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    try {
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return NULL;
    }
    return $lista_usuario;
}


/**
 * Obtener los datos del usuario
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 28/03/2022
 * @param string $connection
 * @param string $usuario
 * @return array
 */
function ObtenerUsuarioDB($usuario)
{
    $my_Db_Connection = ConexionDB();
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
            return $datos;
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
function ValidarLoginDB($usuario, $clave)
{
    $my_Db_Connection = ConexionDB();
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

// //encrypt password with crypt()
// function encriptarPassword($clave)
// {
//     $encryptPass = crypt($clave, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
//     return $encryptPass;
// }



/**
 * Encriptar el id del tuit
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 13/04/2022
 * @param string $idtuit
 * @return string
 */
function encriptarIdTuit($idtuit)
{
    $encryptIdTuit = md5($idtuit);
    $idtuit = crypt($idtuit, $encryptIdTuit);
    return $idtuit;
}

/**
 * desencriptar el id del tuit
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 13/04/2022
 * @param string $idtuit
 * @return string
 */
function desencriptarIdTuit($idtuit)
{
    $decryptIdTuit = md5($idtuit);
    $idtuit = crypt($idtuit, $decryptIdTuit);
    return $idtuit;
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
    $sql = "SELECT color FROM usuarios WHERE usuario = :usuario";
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
/**
 * Actualizar el usuario de la sesión
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 10/04/2022
 * @param string conexión
 * @param string usuario
 * @param string nombre
 * @param string apellido
 * @param string fecha
 * @param string color
 * @param string email
 * @param string tipodoc
 * @param string numdoc
 * @param string hijos
 * @param string foto
 * @param string direccion
 * @param string estadociv
 * @return boolean
 */
function ActualizarUsuario($usuario, $nombre, $apellido, $fecha, $color, $email, $tipodoc, $numdoc, $hijos, $foto, $direccion, $estadociv)
{
    $CONN = ConexionDB();
    $sql = "UPDATE `usuarios` SET `nombres`= case WHEN :nombre IS NULL THEN `nombres` ELSE :nombre END, `apellidos`= case WHEN :apellido IS NULL THEN `apellidos` ELSE :apellido END,
    `fecha_nac`= case WHEN :fecha IS NULL THEN `fecha_nac` ELSE :fecha END, `color`= case WHEN :color IS NULL THEN `color` ELSE :color END,
    `correo`= case WHEN :email IS NULL THEN `correo` ELSE :email END, `id_tip_doc`= case WHEN :tipodoc IS NULL THEN `id_tip_doc` ELSE :tipodoc END,
    `num_doc`= case WHEN :numdoc IS NULL THEN `num_doc` ELSE :numdoc END, `id_num_hijos`= case WHEN :hijos IS NULL THEN `id_num_hijos` ELSE :hijos END,
    `foto`= case WHEN :foto IS NULL THEN `foto` ELSE :foto END, `direccion`= case WHEN :direccion IS NULL THEN `direccion` ELSE :direccion END, 
    `id_est_civil`= case WHEN :estadociv IS NULL THEN `id_est_civil` ELSE :estadociv END WHERE `usuario`= :usuario";

    $statement = $CONN->prepare($sql);
    $statement->bindParam(':usuario', $usuario);
    $statement->bindParam(':nombre', $nombre);
    $statement->bindParam(':apellido', $apellido);
    $statement->bindParam(':fecha', $fecha);
    $statement->bindParam(':color', $color);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':tipodoc', $tipodoc);
    $statement->bindParam(':numdoc', $numdoc);
    $statement->bindParam(':hijos', $hijos);
    $statement->bindParam(':foto', $foto);
    $statement->bindParam(':direccion', $direccion);
    $statement->bindParam(':estadociv', $estadociv);
    try {
        if ($statement->execute()) {
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
 * Cambiar clave del usuario
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string usuario
 * @param string clave
 * @param string newclave
 * @return boolean
 */
function CambiarClave($usuario, $clave, $newclave)
{
    $CONN = ConexionDB();
    $encryptnew = encriptarPassword($newclave);
    $encryptold = encriptarPassword($clave);

    if ($encryptnew == $encryptold) {
        return FALSE;
    } else {
        $vsql = "SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave";
        $statement = $CONN->prepare($vsql);
        $statement->bindParam(':usuario', $usuario);
        $statement->bindParam(':clave', $encryptold);
        try {
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                if (count($result) > 0) {
                    $sql = "UPDATE usuarios SET clave = :newclave WHERE usuario = :usuario";
                    $statement = $CONN->prepare($sql);
                    $statement->bindParam(':usuario', $usuario);
                    $statement->bindParam(':newclave', $encryptnew);
                    try {
                        if ($statement->execute()) {
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }
    }
}


/***
 *  SECCION DE TWEETS!!
 */
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
function GuardarTweet($tweet, $usuario, $estado)
{
    $connection = ConexionDB();
    $sql = "INSERT INTO tuits (mensaje_tuit, id_usuario_tuit, Estado) VALUES (:tweet, :usuario, :estado)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':tweet', $tweet);
    $statement->bindParam(':usuario', $usuario);
    $statement->bindParam(':estado', $estado);
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
function MostrarTweet()
{
    $datostweets = [];
    $connection = ConexionDB();
    $sql = "SELECT tuits.id_tuit as 'idtuit', tuits.mensaje_tuit as 'mensaje', tuits.fecha_tuit as 'fecha', usuarios.usuario as 'usuario', usuarios.foto as 'foto', tuits.Estado as 'estado' FROM tuits INNER JOIN usuarios ON tuits.id_usuario_tuit = usuarios.id_usuario WHERE tuits.Estado = 1 ORDER BY tuits.fecha_tuit DESC";
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
 * Leer un Tweet o Articulo
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 03/04/2022
 * @param string conexión
 * @return array
 */
function MostrarTweetU()
{
    $datostweets = [];
    $$connection = ConexionDB();
    $sql = "SELECT tuits.id_tuit as 'idtuit', tuits.mensaje_tuit as 'mensaje', tuits.fecha_tuit as 'fecha', usuarios.usuario as 'usuario', usuarios.foto as 'foto', tuits.Estado as 'estado' FROM tuits INNER JOIN usuarios ON tuits.id_usuario_tuit = usuarios.id_usuario ORDER BY tuits.fecha_tuit DESC";
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
 * Eliminar un Tweet o Articulo
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 10/04/2022
 * @param string conexión
 * @param string id
 * @return boolean
 */
function EliminarTweet($id)
{
    $connection = ConexionDB();
    $sql = "DELETE FROM tuits WHERE id_tuit = :id";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':id', $id);
    try {
        if ($statement->execute()) {
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
 * Publicar el tweet
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 * @return boolean
 */
function Publicar($id)
{
    $CONN = ConexionDB();
    $sql = "UPDATE tuits SET Estado = 1 WHERE id_tuit = :id";
    $statement = $CONN->prepare($sql);
    $statement->bindParam(':id', $id);
    try {
        if ($statement->execute()) {
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
 * Actualizar el tweet
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 * @param string mensaje_tuit
 * @param boolean estado
 * @return boolean
 */
function Actualizar($id, $tweet, $estado)
{
    $CONN = ConexionDB();
    $sql = "UPDATE tuits SET mensaje_tuit = case when :tweet is null then mensaje_tuit else :tweet end, Estado = case when :estado is null then Estado else :estado end WHERE id_tuit = :id";
    $statement = $CONN->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->bindParam(':tweet', $tweet);
    $statement->bindParam(':estado', $estado);
    try {
        if ($statement->execute()) {
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
 * Despublicar tweet
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 */
function Despublicar($id)
{
    $CONN = ConexionDB();
    $sql = "UPDATE tuits SET Estado = 0 WHERE id_tuit = :id";
    $statement = $CONN->prepare($sql);
    $statement->bindParam(':id', $id);
    try {
        if ($statement->execute()) {
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
 * Listar Usuarios
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 */
function ListarUsuarios($usuario)
{
    $lista_usuario = [];
    $my_Db_Connection = ConexionDB();
    try {
        $my_Select_Statement =
            $my_Db_Connection->prepare("SELECT `usuario`,`nombres`,`apellidos` FROM `usuarios` WHERE `Usuario`!= :Usuario");
        $my_Select_Statement->execute(['Usuario' => $usuario]);
        $lista_usuario = $my_Select_Statement->fetchAll();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return $lista_usuario = null;
    }
    return $lista_usuario;
}

/**
 * Enviar mensajes
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 */
function EnviarMensaje($usuario_origen, $usuario_destino, $texto, $fechaenvio, $archivo)
{
    $my_Db_Connection = ConexionDB();
    try {
        $my_Insert_Statement =
            $my_Db_Connection->prepare("INSERT INTO mensajes (Usuario_origen, Usuario_destino, Texto, FechaEnvio, ArchivoAdjunto)" .
                "VALUES (:Usuario_origen, :Usuario_destino,:Texto,:FechaEnvio,:ArchivoAdjunto)");

        $my_Insert_Statement->bindParam(':Usuario_origen', $usuario_origen);
        $my_Insert_Statement->bindParam(':Usuario_destino', $usuario_destino);
        $my_Insert_Statement->bindParam(':Texto', $texto);
        $my_Insert_Statement->bindParam(':FechaEnvio', $fechaenvio);
        $my_Insert_Statement->bindParam(':ArchivoAdjunto', $archivo);

        if ($my_Insert_Statement->execute()) {
            //echo "Nuevo Usuario Creado";
            return true;
        } else {
            //echo "No se pudo crear Usuario";
            return FALSE;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // var_dump($th);
    }
}
/**
 * Listar Mensajes Recibidos
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 */
function ListarMensajesRecibidos($usuario_destino)
{
    $my_Db_Connection = ConexionDB();
    $lista_mensajes = [];
    try {
        $my_Select_Statement =
            $my_Db_Connection->prepare("SELECT Id, Usuario_origen, Texto, FechaEnvio, ArchivoAdjunto FROM mensajes WHERE Usuario_destino = :Usuario_destino");
        $my_Select_Statement->execute(['Usuario_destino' => $usuario_destino]);
        $lista_mensajes = $my_Select_Statement->fetchAll();;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        //throw $th;
    }
    return $lista_mensajes;
}

/**
 * Listar mensajes enviados
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 12/04/2022
 * @param string conexión
 * @param string id
 */
function ListarMensajesEnviados($usuario_origen)
{
    $my_Db_Connection = ConexionDB();
    $lista_mensajes = [];
    try {
        $my_Select_Statement =
            $my_Db_Connection->prepare("SELECT Id, Usuario_destino, Texto, FechaEnvio, ArchivoAdjunto FROM mensajes WHERE Usuario_origen = :Usuario_origen");
        $my_Select_Statement->execute(['Usuario_origen' => $usuario_origen]);
        $lista_mensajes = $my_Select_Statement->fetchAll();;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // echo $th;
    }
    return $lista_mensajes;
}


/**
 * Función que retorna el usuario Actual consumiendo la API con Token 
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 25/04/2022
 * @return string
 */
// function UsuarioActual(){
//     $jwt = $_SERVER['HTTP_AUTHORIZATION'];
//     $key = 'my_secret_key';

//     if(substr($jwt,0,6) == "Bearer "){
//         $jwt = str_replace('Bearer ','',$jwt);
//         try{
//             $decoded = JWT::decode($jwt, $key, array('HS256'));
//             $datos = $decoded->decoded;
//             return $datos->usuario;
//         }catch(Exception $e){
//             echo 'Credenciales incorrectas del usuario actualizar';
//             echo $e->getMessage();
//             http_response_code(401);
//             exit();
//         }
//     }
// }

function cleanString(string $value): string {
    $value = preg_replace('/<script>.*<\/script>/is', '', $value);
    $value = trim($value);
    // $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return $value;
}

function ArrayCleaner (array $stringArray) {
    foreach ($stringArray as $key => $value) {
        $stringArray[$key] = cleanString($value);
    }
    return $stringArray;
}