<?php
require_once "../lib/db_tools.php";
require_once "../lib/tools.php";
//Consultar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $datos = ObtenerUsuarioDB($CONN, $username);
        if ($datos != NULL) {
            foreach ($datos as $key => $value) {
                if ($datos[$key]['contrasena'] == $password) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode($datos);
                    exit();
                } else {
                    header("HTTP/1.1 401 Unauthorized");
                    exit();
                }
            }
        } else {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
    }
}
 // Crear
 if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['fecha_nac']) && isset($_POST['color']) && isset($_POST['email']) && isset($_POST['tipDoc']) && isset($_POST['num_doc']) && isset($_POST['numhijos']) && isset($_POST['archivo']) && isset($_POST['direccion']) && isset($_POST['estCivil'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $fecha_nac = $_POST['fecha_nac'];
        $color = $_POST['color'];
        $email = $_POST['email'];
        $tipDoc = $_POST['tipDoc'];
        $num_doc = $_POST['num_doc'];
        $numhijos = $_POST['numhijos'];
        $archivo = $_POST['archivo'];
        $direccion = $_POST['direccion'];
        $estCivil = $_POST['estCivil'];
        $datos = RegistrarUsuarioDB($CONN, $username, $password, $name, $lastname, $fecha_nac, $color, $email, $tipDoc, $num_doc, $numhijos, $archivo, $direccion, $estCivil);
        $datos = ['id' => $datos];
        header("HTTP/1.1 200 OK");
        echo json_encode($datos);
        exit();
    } else {
        header("HTTP/1.1 400 Bad Request");
    }
}

// // Borrar
// if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
//     if (isset($_GET['id'])) {
//         $id = $_GET['id'];
//         $datos = BorrarUsuarioDB($CONN, $id);
//         if ($datos == 1) {
//             header("HTTP/1.1 200 OK");
//             echo json_encode($datos);
//             exit();
//         } else {
//             header("HTTP/1.1 400 Bad Request");
//             exit();
//         }
//     } else {
//         header("HTTP/1.1 400 Bad Request");
//     }
// }

// Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['id']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['name']) && isset($_GET['lastname']) && isset($_GET['fecha_nac']) && isset($_GET['color']) && isset($_GET['email']) && isset($_GET['tipDoc']) && isset($_GET['num_doc']) && isset($_GET['numhijos']) && isset($_GET['archivo']) && isset($_GET['direccion']) && isset($_GET['estCivil'])) {
        $id = $_GET['id'];
        $username = $_GET['username'];
        $password = $_GET['password'];
        $name = $_GET['name'];
        $lastname = $_GET['lastname'];
        $fecha_nac = $_GET['fecha_nac'];
        $color = $_GET['color'];
        $email = $_GET['email'];
        $tipDoc = $_GET['tipDoc'];
        $num_doc = $_GET['num_doc'];
        $numhijos = $_GET['numhijos'];
        $archivo = $_GET['archivo'];
        $direccion = $_GET['direccion'];
        $estCivil = $_GET['estCivil'];
        $datos = ActualizarUsuario($CONN, $id, $username, $password, $name, $lastname, $fecha_nac, $color, $email, $tipDoc, $num_doc, $numhijos, $archivo, $direccion, $estCivil);
        if ($datos == 1) {
            header("HTTP/1.1 200 OK");
            echo json_encode($datos);
            exit();
        } else {
            header("HTTP/1.1 400 Bad Request");
            exit();
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
    }
}