<?php
  session_start();
  require_once('php/funciones.php');
  if(isset($_SESSION['username'])){
    header("Location: home.php?redirected=1");
    exit;
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
          <a href="#" class="navbar-brand d-flex align-items-center">
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
            <a class="nav-link" href="#seccionChallenges">Challenges</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./ranking.php">Ranking</a>
          </li>
			<li class="nav-item">
			<a class="nav-link" href="./login.php"><i class="fas fa-sign-in-alt"></i></a>
        </ul>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">WELCOME TO HARDBOX</h1>
          <p> ¡Aprende y practica cyberseguridad!</p>
          <p> (Video de ejemplo, haremos uno explicando la web) </p>
          <!-- Video introductorio -->
          <iframe width="560" height="315"
          src="https://www.youtube-nocookie.com/embed/K4TOrB7at0Y?loop=1&controls=0&autoplay=1&mute=1">
          </iframe>
          <!-- FIN Video introductorio -->
            <p>
            <a href="./login.php" class="btn btn-primary my-2">Login</a>
            <a href="./login.php" class="btn btn-secondary my-2">Register</a>
          </p>
          <p>
            <!--<a href="./crearReto.php" class="btn btn-secondary my-2" type="button">ADD A CHALLENGE</a>-->             
          </p>
        </div>
      </section>

      <div class="container" id="seccionRedTeam">
          <h3 style="color:Red;">Red Team</h3>
          <br>
          <div class="row">
			<!-- Maquina de ejemplo no PHP -->
        <?php
          $maquinasataque = maquinasActivas(1);
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
            <small class="text-muted">10 puntos</small>
            </div>
            </div>
            </div>
          ';
          }
          
          ?>
  			<!-- FIN Maquina de ejemplo -->
        </div></div>
        <div class="container" id="seccionBlueTeam">
          <h3 style="color:LightBlue;">Blue Team</h3>
          <br>
          <div class="row">
			<!-- Maquina de ejemplo no PHP -->
        <?php
          $maquinasdefensa = maquinasActivas(2);
          foreach($maquinasdefensa as $maquina){
            echo '
            <div class="col-md-4">
            <div class="card mb-4 box-shadow">
            <div class="card-body">
            <h5>'.$maquina_dat['nombre'].'</h5></p>
            <h6>Descripcion: </h6>
            <p>'.ucfirst(substr($maquina_dat['desc'],0,50)).'...</p>
            <a href="maquina.php?id='.$maquina_dat['id'].'" class="cart-btn"> Ver VM</a>
            <small class="text-muted">10 puntos</small>
            </div>
            </div>
            </div>
          ';
          }
          
          ?>
  			<!-- FIN Maquina de ejemplo -->
        </div></div>
		<div class="container" id='seccionChallenges'>
          <h3>Challenges</h3>
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