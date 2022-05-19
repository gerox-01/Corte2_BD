<?php


require_once('./../lib/db_tools.php');
require_once('./../lib/regex.php');
require_once('./../lib/tools.php');
require_once('./../../../vendor/autoload.php');
require_once('./../api/token.php');

LimpiarEntradas();
/**
 * Obetener usuarios con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $usuario = token();
        $userdata = ObtenerUsuarioDB($usuario);
        if ($userdata != NULL) {
            header("HTTP/1.1 200 OK");
            echo json_encode($userdata);
            exit();
        } else {
            header("HTTP/1.1 401 Unauthorized");
            echo 'No fue posible recuperar la información del usuario.';
            exit();
        }
    } catch (Exception $e) {
        header("HTTP/1.1 400 Bad Request");
        echo 'Error haciendo la petición. Comuniquese con el administrador.';
        exit();
    }
}

/**
 * Crear un usuario con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Limpia las entradas por el método POST
    $_POST = ArrayCleaner($_POST);

    //Campos obligatorios
    $userRegex = validateUser($_POST['user']);
    $passwordRegex = validatePassword($_POST['password']);
    $nameRegex = validateName($_POST['name']);
    $lastNameRegex = validateLastName($_POST['lastName']);
    $dateRegex = validateDate($_POST['date']);
    $emailRegex = validateEmail($_POST['email']);
    $idTipDocRegex = validateID($_POST['id_tip_doc']);
    $numDocRegex = validateNumberDocument($_POST['num_doc']);
    $idChildrenRegex = validateID($_POST['id_num_hijos']);
    $addressesRegex = validateAddresses($_POST['addresses']);
    $idEstCivilRegex = validateID($_POST['id_est_civil']);

    #region Logica
    try {
        if ($userRegex && $passwordRegex && $nameRegex && $lastNameRegex && $dateRegex && $emailRegex && $idTipDocRegex && $numDocRegex && $idChildrenRegex && $addressesRegex && $idEstCivilRegex) {

            //Definimos variables
            $usuario = $_POST['user'];
            $clave = $_POST['password'];
            $nombre = $_POST['name'];
            $apellido = $_POST['lastName'];
            $fecha_nac = $_POST['date'];
            $color = $_POST['color'];
            $correo = $_POST['email'];
            $id_tip_doc = $_POST['id_tip_doc'];
            $num_doc = $_POST['num_doc'];
            $id_num_hijos = $_POST['id_num_hijos'];
            $direccion = $_POST['addresses'];
            $id_est_civil = $_POST['id_est_civil'];

            #region InsertUser
            if (isset($_POST['file'])) {
                $data = explode(';', $_POST['file']);
                if ($data[0] == 'data:image/jpeg') {
                    echo $data[0];
                    $foto = base64_to_jpeg($data[1], 't.jpg');
                    $mensajes = RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Nuevo usuario registrado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al registrar el usuario';
                        exit();
                    }
                } else if ($data[0] == 'data:image/png') {
                    $foto = base64_to_jpeg($data[1], 't.png');
                    $mensajes = RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Nuevo usuario registrado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al registrar el usuario';
                        exit();
                    }
                } elseif ($data[0] == 'data:image/gif') {
                    $foto = base64_to_jpeg($data[1], 't.gif');
                    $mensajes = RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Nuevo usuario registrado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al registrar el usuario';
                        exit();
                    }
                } elseif ($data[0] == 'data:image/bmp') {
                    $foto = base64_to_jpeg($data[1], 't.bmp');
                    $mensajes = RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL && $mensajes) {
                        header("HTTP/1.1 200 OK");
                        echo 'Nuevo usuario registrado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al registrar el usuario';
                        exit();
                    }
                } else {
                    echo 'Formato de archivo inválido.';
                }
            } else {
                echo "Coloque un archivo para poder registrar un usuario.";
            }
            #endregion

        } else {
            echo 'No cumple con el formato de texto solicitado';
            exit();
        }
    } catch (Exception $e) {
        header("HTTP/1.1 400 Bad Request");
        echo 'Error haciendo la petición. Comuniquese con el administrador.';
        exit();
    }
    #endregion
}


/**
 * Actualizar un usuario con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 * 
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {



    $usuario = token();
    $_PUT = array();
    parse_str(file_get_contents('php://input'), $_PUT);


    //Limpia las entradas por el método POST
    $_PUT = ArrayCleaner($_PUT);

    $nombreRegex = isset($_PUT['nombre']) ? validateName($_PUT['nombre']) : NULL;
    $apellidoRegex = isset($_PUT['apellido']) ? validateLastName($_PUT['apellido']) : NULL;
    $fecha_nacRegex = isset($_PUT['fecha_nac']) ? validateDate($_PUT['fecha_nac']) : NULL;
    $colorRegex = isset($_PUT['color']) ? $_PUT['color'] : NULL;
    $correoRegex = isset($_PUT['correo']) ? validateEmail($_PUT['correo']) : NULL;
    $id_tip_docRegex = isset($_PUT['id_tip_doc']) ? validateID($_PUT['id_tip_doc']) : NULL;
    $num_docRegex = isset($_PUT['num_doc']) ? validateNumberDocument($_PUT['num_doc']) : NULL;
    $id_num_hijosRegex = isset($_PUT['id_num_hijos']) ? validateID($_PUT['id_num_hijos']) : NULL;
    $direccionRegex = isset($_PUT['direccion']) ? validateAddresses($_PUT['direccion']) : NULL;
    $id_est_civilRegex = isset($_PUT['id_est_civil']) ? validateID($_PUT['id_est_civil']) : NULL;


    try {
        if ($nombreRegex || $apellidoRegex || $fecha_nacRegex || $colorRegex || $correoRegex || $id_tip_docRegex || $num_docRegex || $id_num_hijosRegex || $direccionRegex || $id_est_civilRegex) {

            //Campos tipiados
            $nombre = isset($_PUT['nombre']) ? $_PUT['nombre'] : NULL;
            $apellido = isset($_PUT['apellido']) ? $_PUT['apellido'] : NULL;
            $fecha_nac = isset($_PUT['fecha_nac']) ? $_PUT['fecha_nac'] : NULL;
            $color = isset($_PUT['color']) ? $_PUT['color'] : NULL;
            $correo = isset($_PUT['correo']) ? $_PUT['correo'] : NULL;
            $id_tip_doc = isset($_PUT['id_tip_doc']) ? $_PUT['id_tip_doc'] : NULL;
            $num_doc = isset($_PUT['num_doc']) ? $_PUT['num_doc'] : NULL;
            $id_num_hijos = isset($_PUT['id_num_hijos']) ? $_PUT['id_num_hijos'] : NULL;
            $direccion = isset($_PUT['direccion']) ? $_PUT['direccion'] : NULL;
            $id_est_civil = isset($_PUT['id_est_civil']) ? $_PUT['id_est_civil'] : NULL;


            if (isset($_PUT['file'])) {
                $data = explode(';', $_PUT['file']);
                if ($data[0] == 'data:image/jpeg') {
                    echo $data[0];
                    $foto = base64_to_jpeg($data[1], 't.jpg');
                    $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Usuario actualizado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al actualizar el usuario';
                        exit();
                    }
                } else if ($data[0] == 'data:image/png') {
                    $foto = base64_to_jpeg($data[1], 't.png');
                    $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Usuario actualizado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al actualizar el usuario';
                        exit();
                    }
                } elseif ($data[0] == 'data:image/gif') {
                    $foto = base64_to_jpeg($data[1], 't.gif');
                    $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Usuario actualizado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al actualizar el usuario';
                        exit();
                    }
                } elseif ($data[0] == 'data:image/bmp') {
                    $foto = base64_to_jpeg($data[1], 't.bmp');
                    $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                    if ($mensajes != NULL) {
                        header("HTTP/1.1 200 OK");
                        echo 'Usuario actualizado';
                        exit();
                    } else {
                        header("HTTP/1.1 401 Unauthorized");
                        echo 'Error al actualizar el usuario';
                        exit();
                    }
                } else {
                    $foto = NULL;
                }
            } else {
                $foto = NULL;

                $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                if ($mensajes != NULL) {
                    header("HTTP/1.1 200 OK");
                    echo 'Usuario actualizado';
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    echo 'Error al actualizar el usuario';
                    exit();
                }
            }
        } else {
            echo "No coinciden con el formato solicitado";
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo $e->getMessage();
        exit();
    }
}

/**
 * Función que retorna el usuario Actual consumiendo la API con Token 
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 25/04/2022
 * @return string
 */
function UsuarioActual()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';

    if (substr($jwt, 0, 6) == "Bearer") {
        $jwte = str_replace("Bearer ", "", $jwt);
        try {
            // echo $jwte;
            $data = Firebase\JWT\JWT::decode($jwte, $key, array('HS256'));
            // echo $data;
            // Serializar data
            $datas = (array)$data;
            foreach ($datas as $key => $value) {
                $usuario = $value;
            }
            // var_dump($usuario);
            $json_data  = json_encode((array)$usuario->usuario);
            // print_r($json_data);
            $json_data = str_replace('[', "", $json_data);
            $json_data = str_replace(']', "", $json_data);
            return $json_data;
        } catch (Exception $e) {
            echo 'Credenciales incorrectas del usuario actualizar';
            echo $e->getMessage();
            http_response_code(404);
            exit();
        }
    }
    return "";
}



/**
 * Función que retorna el idusuario Actual consumiendo la API con Token
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 25/04/2022
 * @return string
 */
function UsuarioActualId()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';

    if (substr($jwt, 0, 6) == "Bearer") {
        $jwte = str_replace("Bearer ", "", $jwt);
        try {
            // echo $jwte;
            $data = Firebase\JWT\JWT::decode($jwte, $key, array('HS256'));
            // echo $data;
            // Serializar data
            $datas = (array)$data;
            foreach ($datas as $key => $value) {
                $usuario = $value;
            }
            // var_dump($usuario);
            $json_data  = json_encode((array)$usuario->iduser);
            // print_r($json_data);
            $json_data = str_replace('[', "", $json_data);
            $json_data = str_replace(']', "", $json_data);
            return $json_data;
        } catch (Exception $e) {
            echo 'Credenciales incorrectas del usuario actualizar';
            echo $e->getMessage();
            http_response_code(404);
            exit();
        }
    }
    return "";
}

function base64_to_jpeg($base64_string, $output_file)
{
    $ifp = fopen($output_file, "wb");
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    return $output_file;
}
