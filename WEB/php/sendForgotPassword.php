<!doctype html>
<html lang="es">

<?php
    require_once("connecta_db_persistent.php");
    include_once('funciones.php');

    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])){
        $usuarioPost = $_POST['username'];
        $existe = verificarExistencia('',$usuarioPost);
        if($existe != 0){
            mailForgotPassword($usuarioPost);
            header("Location:../login.php?redirected=7");
            exit;
        }else{
            header("Location:./sendForgotPassword.php?redirected=111");
            exit;
        }
    }else{
        header("Location:../login.php?redirected=112");
        exit;
    }
?>
