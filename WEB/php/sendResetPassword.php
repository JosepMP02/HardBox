<!doctype html>
<html lang="es">

<?php
    require_once("connecta_db_persistent.php");
    include_once('funciones.php');

    session_start();
    $user = $_SESSION['username'];
    $userDat = datosUsuario($user);
    mailResetPassword($userDat['mail']);
    header("Location:/myprofile.php?redirected=7");
    exit;
?>
