<?php

require_once('connecta_db_persistent.php');
require_once('funciones.php');


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["password"]) && isset($_POST["password2"])){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $password = $_POST["password"];
        $vpassword = $_POST["password2"];

        if($password != $vpassword){
            header("Location:../login.php?redirected=222&username=$username&mail=$email&fname=$fname&lname=$lname");
            exit;
        }else{
            $NumUsersExistents = verificarExistencia($username,$email);

            if($NumUsersExistents >= 1){
                header("Location:../login.php?redirected=223&username=$username&mail=$email&fname=$fname&lname=$lname");
                exit;
            }else{
                $passHash = password_hash($password,PASSWORD_DEFAULT);
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    insertarDatos($username,$email,$fname,$lname,$passHash);
                    mailActivateUser($email);
                }else{
                    header("Location:../login.php?redirected=221");
                    exit;
                }
                header("Location:../login.php?redirected=221");
                exit;
            }
        }
    }else{
        header("Location:../login.php?redirected=221");
        exit;
    }
}else{
    header("Location:../login.php?redirected=221");
    exit;
}