
<?php
  session_start();
  require_once('php/funciones.php');
  if(!isset($_SESSION['username'])){
    header("Location: login.php?redirected=1");
    exit;
  }else{
    require_once('php/funciones.php');
    $user = $_SESSION['username'];
    $userDat = datosUsuario($user);
    eliminarVPNfile($userDat['mail']);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="./media/cubologo_azul.png">

    <title>Hardbox</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/81f9e3e169.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="./css/album.css" rel="stylesheet">
    <script src="./js/prueba.js"></script>
  </head>

  <body>

  <header>
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
          <a href="./home.php" class="navbar-brand d-flex align-items-center">
            <strong>Hardbox</strong>
          </a>

        <ul class="nav justify-content-end">
		<li class="nav-item">
            <a class="nav-link" href="./#seccionRedTeam">Red Team</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./#seccionBlueTeam">Blue Team</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./home.php#seccionChallenges">Challenges</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./ranking.php">Ranking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./vpnInfo.php">VPN</a>
          </li>
		  <li class="nav-item">
		  		<a class="nav-link" href="myprofile.php"><i class="fas fa-user"></i><b>   -   <?php echo $userDat['userFirstName']." ".$userDat['userLastName'];?> </b></a>
		  </li>
		  <li class="nav-item">
				<a class="nav-link" href="php/logout.php"><i class="fas fa-sign-out-alt"></i></a>
		  </li>
        </ul>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Como Acceder a HardBox</h1><br>
			      <p>
              Para poder acceder a nuestra red de maquinas vulnerables, primero tenemos que conectarnos por VPN, a continuación explicamos como llevar a cabo la conexion.
            </p>
            <br>
            <b>WINDOWS 10 / 11:</b>
            <br><br>
            <p>
              <b>PASO 1:</b> Nos tenemos que descargar <a href="https://openvpn.net/client-connect-vpn-for-windows/" target="_blank">OpenVPN</a>, 
              que es el cliente de VPN que utilizaremos, para ello podemos descragarlo de <a href="https://openvpn.net/client-connect-vpn-for-windows/" target="_blank">este enlace</a>. 
            </p>
            <p>
              <b>PASO 2:</b> Una vez instalado el cliente <a href="https://openvpn.net/client-connect-vpn-for-windows/" target="_blank">OpenVPN</a>, 
              descargaremos nuestro fichero de configuracion de la plataforma, recuerda, tu fuchero es personal e intransferible, en caso de perdida contacta con un administrador. 
            </p>
            <a href="./php/vpn.php" class="btn btn-secondary my-2" type="button">Descarga el fichero VPN</a>
            <p>
              <b>PASO 3:</b> Añadimos nuestro fichero de configuracion al cliente <a href="https://openvpn.net/client-connect-vpn-for-windows/" target="_blank">OpenVPN</a>, 
              podemos añadirlo arrastrando el fichero en la aplicaion. 
            </p>
            <p>
              <b>PASO 4:</b> Finalmente nos conectamos a la red de hardbox, podemos comprobar la conexion enviando un paquete ICMP, tambein dicho PING a 
              la ip 10.8.0.1 y si recibimos respuesta quiere decir que tenemos conexion con HardBox.
            </p>

            <br>
            <b>LINUX:</b>
            <br><br>
            <p>
              <b>PASO 1:</b> Nos tenemos que descargar OpenVPN</a>, que es el cliente de VPN que utilizaremos, para ello podemos descragarlo con el comando <b>apt install openvpn</b>. 
            </p>
            <p>
              <b>PASO 2:</b> Una vez instalado el cliente OpenVPN, descargaremos nuestro fichero de configuracion de la plataforma, recuerda, tu fuchero es personal e intransferible, en caso de perdida contacta con un administrador. 
            </p>
            <a href="./php/vpn.php" class="btn btn-secondary my-2" type="button">Descarga el fichero VPN</a>
            <p>
              <b>PASO 3:</b> Nos conectamos a la red ejecutando el siguiente comando: <br><b>sudo openvpn --config *ruta del fichero de configuracion*</b>. 
              <br>Por ejemplo, si mi usuario es admin y tengo el fichero en mi home, tendria que ejecutar el siguiente comando:
              <b>sudo openvpn --config /home/admin/admin.ovpn</b>
            </p>
            <p>
              <b>PASO 4:</b> Finalmente ya estaremos conectados a la red de hardbox, podemos comprobar la conexion lanzando un paquete ICMP, tambein dicho PING a 
              la ip 10.8.0.1 y si recibimos respuesta quiere decir que tenemos conexion con HardBox.
            </p>
            <br>
            <b>CONTACTO:</b>
            <br><br>
            <p>
              Si hay algun problema en la conexión o en la obtención del fichero de configuracion, hay que contactar a <b>vpn_support@hardbox.ga</b>.
            </p>

        </div>
		</section>
      
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
  <footer class="text-muted">
    <div class="footer vw-100">
      <div class="btn-group vw-100" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-primary vw-100"><img class="footernav" src="../assets/img/home.png" alt="Girl in a jacket"></button>
        <button type="button" class="btn btn-primary vw-100"><img class="footernav" src="../assets/img/signo.png" alt="Girl in a jacket"></button>
        <button type="button" class="btn btn-primary vw-100"><img class="footernav" src="../assets/img/profile.png" alt="Girl in a jacket"></button>
      </div>
    </div>
    <div class="container">
		<p>Hardbox.ga © 2022</p>
    </div>
  </footer>
    
</html> 