<?php
    
    /**
     * Conexión a la base de datos
     * Autor: Alejandro Monroy y Gerónimo Quiroga
     * Fecha: 27/03/2022
     * @return PDO
     */
    function ConexionDB(){
        $servername = "localhost";
        $database = "twitter";
        $username = "root";
        $password = "123456";

        $sql = "mysql:host=$servername; dbname=$database;";
        $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        
        try {
            $my_Db_Connection = new PDO($sql,$username, $password, $dsn_Options);
            return $my_Db_Connection;
        } catch (PDOException $error) {
            echo 'Connection error '. $error->getMessage();
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
     */
    function RegistrarUsuarioDB($my_Db_Connection,$usuario,$clave,$nombre,$apellido,$nacimiento,$cantidad_hijos,$color,$foto){

        $my_Insert_Statement=
            $my_Db_Connection->prepare("INSERT INTO usuario (usuario,clave,nombre,apellido,nacimiento,cantidad_hijos,color,foto)".
            "VALUES (:usuario, :clave,  :nombre, :apellido, :nacimiento, :cantidad_hijos, :color,:foto)");
        $my_Insert_Statement->bindParam(':usuario',$usuario);
        $my_Insert_Statement->bindParam(':clave',$clave);
        $my_Insert_Statement->bindParam(':nombre',$nombre);
        $my_Insert_Statement->bindParam(':apellido',$apellido);
        $my_Insert_Statement->bindParam(':nacimiento',$nacimiento);
        $my_Insert_Statement->bindParam(':cantidad_hijos',$cantidad_hijos);
        $my_Insert_Statement->bindParam(':color',$color);
        $my_Insert_Statement->bindParam(':foto',$foto);

        if ($my_Insert_Statement->execute()) {
            echo "Nuevo Usuario Creado";
            return TRUE;
        }else{
            echo "No se pudo crear Usuario";
            return FALSE;
        }   
    }
?>