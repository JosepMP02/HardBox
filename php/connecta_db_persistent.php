<?php
    $cadena_connexio = 'mysql:dbname=webapp;host='; //Host DB
    $usuari = ''; //User DB
    $passwd = ''; //Password DB
    try{
        $db = new PDO($cadena_connexio, $usuari, $passwd, 
                        array(PDO::ATTR_PERSISTENT => true));
    }catch(PDOException $e){
        echo 'Error amb la BDs: ' . $e->getMessage();
    }