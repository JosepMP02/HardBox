<?php
    session_start();
    $_SESSION = array();
    setcookie(session_name(),"",time()-3600,"/");
    setcookie('nombre',"",time()-3600,"/");
    session_destroy();
    if(isset($_GET['login']) && $_GET['login']==1){
        header("Location: ../login.php");
    }else{
        header("Location: ../index.php?redirected=3");
    }
    exit;
