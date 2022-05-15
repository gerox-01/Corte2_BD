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
    $usuario = token();
    $userdata = ObtenerUsuarioDB($usuario);
    if ($userdata != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($userdata);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
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

    if ($userRegex && $passwordRegex && $nameRegex && $lastNameRegex && $dateRegex && $emailRegex && $idTipDocRegex && $numDocRegex && $idChildrenRegex && $addressesRegex && $idEstCivilRegex ) {
        echo 'Cumple con el formato';

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
                RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                if ($mensajes != NULL) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode($mensajes);
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    exit();
                }
            } else if ($data[0] == 'data:image/png') {
                $foto = base64_to_jpeg($data[1], 't.png');
                RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                if ($mensajes != NULL) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode($mensajes);
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    exit();
                }
            } elseif ($data[0] == 'data:image/gif') {
                $foto = base64_to_jpeg($data[1], 't.gif');
                RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                if ($mensajes != NULL) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode($mensajes);
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    exit();
                }
            } elseif ($data[0] == 'data:image/bmp') {
                $foto = base64_to_jpeg($data[1], 't.bmp');
                $mensajes = RegistrarUsuarioDB($usuario, $clave, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
                if ($mensajes != NULL) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode($mensajes);
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    exit();
                }
            } else {
                $foto = NULL;
            }
        } else {
            echo "<p>No coinciden con el formato solicitado</p>";
        }
        #endregion

    } else {
        echo 'No cumple con el formato de texto solicitado';
        exit();
    }
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
    $nombre = isset($_PUT['nombres']) ? $_PUT['nombres'] : NULL;
    $apellido = isset($_PUT['apellidos']) ? $_PUT['apellidos'] : NULL;
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
            ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
            if ($mensajes != NULL) {
                header("HTTP/1.1 200 OK");
                echo json_encode($mensajes);
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit();
            }
        } else if ($data[0] == 'data:image/png') {
            $foto = base64_to_jpeg($data[1], 't.png');
            ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
            if ($mensajes != NULL) {
                header("HTTP/1.1 200 OK");
                echo json_encode($mensajes);
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit();
            }
        } elseif ($data[0] == 'data:image/gif') {
            $foto = base64_to_jpeg($data[1], 't.gif');
            ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
            if ($mensajes != NULL) {
                header("HTTP/1.1 200 OK");
                echo json_encode($mensajes);
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit();
            }
        } elseif ($data[0] == 'data:image/bmp') {
            $foto = base64_to_jpeg($data[1], 't.bmp');
            $mensajes = ActualizarUsuario($usuario, $nombre, $apellido, $fecha_nac, $color, $correo, $id_tip_doc, $num_doc, $id_num_hijos, $foto, $direccion, $id_est_civil);
            if ($mensajes != NULL) {
                header("HTTP/1.1 200 OK");
                echo json_encode($mensajes);
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit();
            }
        } else {
            $foto = NULL;
        }
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



/**
 * En caso de que ninguna de las opciones anteriores se haya ejecutado
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
header("HTTP/1.1 400 Bad Request");
echo 'Esta mal todo :/';
