<?php
session_start();
$user = $_SESSION['username'];
require_once('connecta_db_persistent.php');
require_once('funciones.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_GET["id"]) && isset($_POST["flag"])){
        $id = $_GET["id"];
        $reto_dat = datosReto($id);
        $user_dat = datosUsuario(strtoupper($user));
        $flagReal = strtoupper($reto_dat['flag']);
        $flagUser = strtoupper($_POST["flag"]);
        if($reto_dat['creador']!=$user){
            if($flagReal == $flagUser){     
                //Flag buena
                añadirPuntuacion($reto_dat['id'],$user_dat['iduser']);
                header("Location:../reto.php?id=".$id."&checked=1");
                exit;
            }else{
                //Flag mala
                sumarIntentoFallido($reto_dat['id'],$user_dat['iduser']);
                header("Location:../reto.php?id=".$id."&checked=0");
                exit;
            }
        }else{
            header("Location:../reto.php?id=".$id."&checked=2");
        }
    }else{
        header("Location:../home.php");
        exit;
    }
}else{
    header("Location:../home.php");
    exit;
}
?>