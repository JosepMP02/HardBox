<?php 
require_once('funciones.php');

session_start();
if(!isset($_SESSION['username']) && !isset($_COOKIE['nombre'])){
    header("Location: ../login.php?redirected=1");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["flag"]) && isset($_POST["puntuacion"]) && isset($_FILES["fichero"])){
        $titulo = $_POST["titulo"];
        $desc = $_POST["descripcion"];
        $hashtags = $_POST["hashtags"];
        $flag = $_POST["flag"];
        $punt = $_POST["puntuacion"];
        $usuario = $_SESSION['username'];
            
        //insertar los datos del reto en la base de datos        
        insertarReto($titulo,$desc,$flag,$punt,$usuario);

        //Buscar la id del reto:
        $retoDat = datosReto($titulo);
        $idReto = $retoDat['id'];
        //insertar los datos de la categoria en la base de datos
        insertarRetoCategoria($idReto,$hashtags);
        //insertar fichero en db
        if(strlen($_FILES["fichero"]["name"])>0){
            $nomCod = genRandHash()."_".$_FILES["fichero"]["name"];
            $ruta = "../uploads/".$nomCod;
            insertarFitxer($nomCod,$ruta,$idReto);
            $res = move_uploaded_file($_FILES["fichero"]["tmp_name"],$ruta);
        }
        header("Location:/home.php");
    }else{
        header("Location:/home.php?error=1");
    }
}