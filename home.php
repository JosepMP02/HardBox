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
            <a class="nav-link" href="./#seccionChallenges">Challenges</a>
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
          <h1 class="jumbotron-heading">WELCOME TO HARDBOX</h1>
		  <br>
		  <h2 class="jumbotron-heading">Bienvenidos a la pagina web de Hardbox!</h2>
          <p class="lead text-muted">
  			Con este proyecto buscamos ayudar a todas las personas interesadas en Cyberseguridad proporcionando una manera de
			aprender mediante retos de ctf y proteccion de máquinas. Haciendo mas sencillo y divertido el aprendizaje!  
		  </p>
		  <iframe width="560" height="315" src="https://www.youtube.com/embed/DMawWBwHnBU?loop=1&controls=0&autoplay=1&mute=1"></iframe>
		  <br>
		  <p class="lead text-muted"><b>A continuación teneis diferentes retos. A por ellos!</b></p>
          <p>
            <!--<a href="./crearReto.php" class="btn btn-secondary my-2" type="button">ADD A CHALLENGE</a>-->             
          </p>
        </div>
      </section>

      <div class="album py-5 bg-light">
	  	<div class="container">
          <h3>Aprende Cyberseguridad</h3>
          <br>
          <div class="row">
			<!-- Temario de ejemplo Seria hacer un foreach de php, y con cada temario en un registro de base de datos -->
      <?php
          $temariototal = gettemarioID();
          foreach($temariototal as $temario){
            $temario_data = datostemario($temario);
            echo '
            <div class="col-md-4">
            <div class="card mb-4 box-shadow">
            <div class="card-body">
            <h5>'.$temario_data['titulo'].'</h5>
            <h6>Descripcion: </h6>
            <p>'.ucfirst(substr($temario_data['desc'],0,25)).'...</p>
            <a href="temario/'.$temario_data['url'].'" class="cart-btn"> Más información...</a>
            </div>
            </div>
            </div>
          ';
          }
          
          ?>
  			<!-- FIN Temario de ejemplo -->
        </div>
        <br><br><br><br>
		<div class="container" id="seccionRedTeam">
    
    <h3>Maquinas disponibles:</h3>
          <h3 style="color:Red;">Red Team</h3>
          <br>
          <div class="row">
			<!-- Maquina de ejemplo no PHP -->
        <?php
          $maquinasataque = maquinasActivas(1);
          if(empty($maquinasataque)){
            echo "<h5>No hay maquinas disponibles en este momento...</h5>";
          }
          foreach($maquinasataque as $maquina){
            $maquina_dat = datosMaquina($maquina);
            echo '
            <div class="col-md-4">
            <div class="card mb-4 box-shadow">
            <div class="card-body">
            <h6>Descripcion: </h6>
            <h5>'.$maquina_dat['nombre'].'</h5></p>
            <p>'.ucfirst(substr($maquina_dat['desc'],0,50)).'...</p>
            <a href="maquina.php?id='.$maquina_dat['id'].'" class="cart-btn"> Ver VM</a>
            <!--<small class="text-muted">10 puntos</small>-->
            </div>
            </div>
            </div>
          ';
          }
          
          ?>
  			<!-- FIN Maquina de ejemplo -->
        </div></div>
        <div class="container" id="seccionBlueTeam">
          <h3 style="color:#0096FF;">Blue Team</h3>
          <br>
          <div class="row">
			<!-- Maquina de ejemplo no PHP -->
        <?php
          $maquinasdefensa = maquinasActivas(2);
          if(empty($maquinasdefensa)){
            echo "<h5>No hay maquinas disponibles en este momento...</h5>";
          }
          foreach($maquinasdefensa as $maquina){
            echo '
            <div class="col-md-4">
            <div class="card mb-4 box-shadow">
            <div class="card-body">
            <h5>'.$maquina_dat['nombre'].'</h5></p>
            <h6>Descripcion: </h6>
            <p>'.ucfirst(substr($maquina_dat['desc'],0,50)).'...</p>
            <a href="maquina.php?id='.$maquina_dat['id'].'" class="cart-btn"> Ver VM</a>
            <!--<small class="text-muted">10 puntos</small>-->
            </div>
            </div>
            </div>
          ';
          }
          
          ?>
  			<!-- FIN Maquina de ejemplo -->
        </div></div>
        <br><br><br><br>
		<div class="container" id='seccionChallenges'>
          <h3>Retos</h3>
          <br>
          <div class="row">
				
				<!-- For each de retos -->
				<?php
				$retos = obtenerRetos(1,'');
				foreach($retos as $reto){
					$reto_dat = datosReto($reto);
					$hastagsReto = obtenerCategoriaReto($reto_dat['id']);
					echo '
					<div class="col-md-4">
					<div class="card mb-4 box-shadow">
					<div class="card-body">
					<h5>'.$reto_dat['nombre'].'</h5>
					<h6>'.$hastagsReto.'</h6>
					<h6>Dificultad: </h6><p>';
					if($reto_dat['punt']<=10){
						echo 'Facil';
					}elseif($reto_dat['punt']>10 && $reto_dat['punt']<=50){
						echo 'Medio';
					}else{
						echo 'Dificil';
					} 
					echo '</p>
					<h6>Creador: </h6><p><a href="./profile.php?user='.$reto_dat['creador'].'">'.$reto_dat['creador'].'</a></p>
					<h6>Fecha Publicacion: </h6><p>'.substr($reto_dat['dataPub'],0,10).'</p>
					<h6>Descripcion: </h6>
					<p class="card-text">'.ucfirst(substr($reto_dat['desc'],0,50)).'...</p>
					<a href="reto.php?id='.$reto_dat['id'].'" class="cart-btn"> Ver reto</a>
					<small class="text-muted">'.$reto_dat['punt'].' puntos</small>
					</div>
					</div>
					</div>
				';
				}
				
				?>
  				<!-- FIN For each de retos -->
          </div>
        </div>
      </div>
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