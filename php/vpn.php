<?php
  session_start();
  require_once('funciones.php');
  if(!isset($_SESSION['username'])){
    header("Location: login.php?redirected=1");
    exit;
  }else{
    require_once('funciones.php');
    $user = $_SESSION['username'];
    $userDat = datosUsuario($user);
  }

  
  $resp = moverVPNfile($userDat['mail']);
  if($resp == 1){
    //echo '<script> document.location.href = "/user/'.$userDat['username'].'/'.$userDat['username'].'.ovpn"; </script>';
    //echo '<script> browser.downloads.download({url: "/user/'.$userDat['username'].'/'.$userDat['username'].'.ovpn"}) </script>';
    echo '<a href="/user/'.$userDat['username'].'/'.$userDat['username'].'.ovpn" download> Descarga tu fichero VPN </a>';
    
  }else{
    echo "Tu fichero VPN no existe. <br><br> Contacta con el soporte de hardbox. <b>vpn_support@hardbox.ga</b> <br><br> <a href='../vpnInfo.php'>Volver</a>";
  }

?>