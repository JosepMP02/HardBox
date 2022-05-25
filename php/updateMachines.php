<?php
require('connecta_db_persistent.php');
include_once('funciones.php');

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["code"]) && isset($_GET["nombre1"]) && isset($_GET["nombre2"]) && isset($_GET["nombre3"]) && isset($_GET["nombre4"])){
        $nombre1 = $_GET["nombre1"]; $nombre2 = $_GET["nombre2"]; $nombre3 = $_GET["nombre3"]; $nombre4 = $_GET["nombre4"];
        
        if($_GET["code"] == 'Change-Makina12354'){
            desactivarMaquinas();
            if(!empty($_GET["nombre1"])){activarMaquinas($nombre1);}
            if(!empty($_GET["nombre2"])){activarMaquinas($nombre2);}
            if(!empty($_GET["nombre3"])){activarMaquinas($nombre3);}
            if(!empty($_GET["nombre4"])){activarMaquinas($nombre4);}
            
	    //echo '1';
        }else{
            //echo '0';
        }
    }else{
       //echo '-1';
    }
}

?>
