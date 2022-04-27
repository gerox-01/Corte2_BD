<?php


require_once('./../lib/db_tools.php');
require_once('./../lib/tools.php');
require_once('./../../../vendor/autoload.php');
require_once('./../api/token.php');

LimpiarEntradas();
$CONN = ConexionDB();



/**
 * Obetener usuarios con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuario = token();
    $mensajes = ObtenerUsuarioDB( $usuario);
    if ($mensajes != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajes);
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
    $mensajes = RegistrarUsuarioDB($CONN, 
    $_POST['usuario'],    
    $_POST['clave'],
    $_POST['nombres'],
    $_POST['apellidos'],
    $_POST['fecha_nac'],
    $_POST['color'],
    $_POST['correo'],
    $_POST['id_tip_doc'],
    $_POST['num_doc'],
    $_POST['id_num_hijos'],
    $_POST['foto'],
    $_POST['direccion'],
    $_POST['id_est_civil']);

    if ($mensajes != NULL) {
        header("HTTP/1.1 200 OK");
        echo json_encode($mensajes);
        exit();
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
}
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {
//         $_SESSION['estCivil'] =  $_POST['estCivil'];
//         $datos = RegistrarUsuarioDB($CONN, $_POST['username'], $_POST['password'], $_POST['name'], $_POST['lastname'], $_POST['fecha_nac'], $_POST['color'], $_POST['email'], $_POST['tipDoc'], $_POST['num_doc'], $_POST['numhijos'], $_POST['archivo'], $_POST['direccion'], $_POST['estCivil']);
//         $datos = ['id' => $datos];
//         header("HTTP/1.1 200 OK");
//         echo json_encode($datos);
//         exit();
//     } else {
//         header("HTTP/1.1 400 Bad Request");
//     }
// }

/**
 * PENDIENTE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * Actualizar un usuario con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $usuario = token();
    if (
        isset($usuario) &&
        isset($_POST['usuario']) &&
        isset($_POST['clave']) &&
        isset($_POST['nombres']) &&
        isset($_POST['apellidos']) &&
        isset($_POST['fecha_nac']) &&
        isset($_POST['color']) &&
        isset($_POST['correo']) &&
        isset($_POST['id_tip_doc']) &&
        isset($_POST['num_doc']) &&
        isset($_POST['id_num_hijos']) &&
        isset($_POST['foto']) &&
        isset($_POST['direccion']) &&
        isset($_POST['id_est_civil'])
    ) {
        $fileTmpPath = $_FILES['foto']['tmp_name']; 
            $fileName = $_FILES['foto']['name'];
            $files_folder = '../files/';
            if(!file_exists($files_folder)) {
                mkdir($files_folder);
            }

            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $permitidas = ['jpg','gif','png','jpeg'];
            if(in_array($fileExtension, $permitidas)){

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $files_folder . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)){

                    $img = imagecreatefromjpeg($dest_path);
                    imagejpeg($img, $dest_path, 100);
                    imagedestroy($img);

                    $exif2 = exif_read_data($dest_path);
                    
                    $foto = $dest_path;
                    
                    $CONN=ConexionDB();
                    if ($CONN !=NULL)
                    {
                        ActualizarUsuario(
                            ConexionDB(),     
                            $usuario,
                            $_POST['usuario'],
                            $_POST['clave'],
                            $_POST['nombres'],
                            $_POST['apellidos'],
                            $_POST['fecha_nac'],
                            $_POST['color'],
                            $_POST['correo'],
                            $_POST['id_tip_doc'],
                            $_POST['num_doc'],
                            $_POST['id_num_hijos'],
                            $foto,
                            $_POST['direccion'],
                            $_POST['id_est_civil']
                        );
                        header("HTTP/1.1 200 OK");
                        echo json_encode($_POST);
                        exit();

                    }
                    else
                    {
                        header("HTTP/1.1 500 Internal Server Error");
                        exit();
                    }
                }
            } 
        }
    
}
/**
 * Actualizar un usuario con el token de autenticación
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
// if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
//     if ($_POST['nombres'] != NULL) {
//         echo "hola";
//     }
//     else {
//         $_POST['nombres'] =NULL;
//         echo $_POST['nombres'];
//     }
    // $nombre = $_POST['nombres']??null;
    // $apellido = $_POST['apellidos']??null;
    // $fecha_nac = $_POST['fecha_nac']??null;
    // $color = $_POST['color']??null;
    // $correo = $_POST['correo']??null;
    // $id_tip_doc = $_POST['id_tip_doc']??null;
    // $num_doc = $_POST['num_doc']??null;
    // $id_num_hijos = $_POST['id_num_hijos']??null;
    // $foto = $_POST['foto']??null;
    // $direccion = $_POST['direccion']??null;
    // $id_est_civil = $_POST['id_est_civil']??null;

    // // $usuario = token();
    // echo $apellido;
    // $mensajes = ActualizarUsuario($CONN, 
    // $usuario, 
    // $nombre,
    // $apellido,
    // $fecha_nac,
    // $color,
    // $correo,
    // $id_tip_doc,
    // $num_doc,
    // $id_num_hijos,
    // $foto,
    // $direccion,
    // $id_est_civil
    // );

    // if ($mensajes != NULL) {
    //     header("HTTP/1.1 200 OK");
    //     echo json_encode($mensajes);
    //     exit();
    // } else {
    //     header("HTTP/1.1 401 Unauthorized");
    //     exit();
    // }
// }
// if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
//     if (isset($_GET['id']) && isset($_GET['uid'])) {
//         // $datos = ActualizarUsuario($CONN,);
//         $datosd = ['id' => $datos];
//         header("HTTP/1.1 200 OK");
//         echo json_encode($datos);
//         exit();
//     }
// }


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




/**
 * En caso de que ninguna de las opciones anteriores se haya ejecutado
 * Autor: Alejandro Monroy y Gerónimo Quiroga
 * Fecha: 18/04/2022
 */
header("HTTP/1.1 400 Bad Request");
