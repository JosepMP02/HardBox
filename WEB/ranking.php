
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
			<h1 class="jumbotron-heading">Ranking de Usuarios</h1><br>
  				<div class="container col-12">
			<?php 
				$res = getRanking();
				$pos = 1;
				echo '<table class="col-12">
				<tr>
				<th class="col-4">
					Posicion
				</th>
				<th class="col-4">
					Usuario
				</th>
				<th class="col-4">
					Puntuacion<br>
				</th>
				</tr>';
				foreach($res as $registro){
					echo '
					<tr>
					<th class="col-4">
						'.$pos.'
					</th>
					<td class="col-4">
						'.$registro['usuario'].'
					</td>
					<td class="col-4">
						'.$registro['puntuacion'].'<br>
					</td>
					</tr>
					';
					$pos = $pos+1;
				}
				echo '</table>';
			?>
			</div>
			<div class="container col-3"></div>
          <p>
            <!--<a href="./crearReto.php" class="btn btn-secondary my-2" type="button">ADD A CHALLENGE</a>-->             
          </p>
        </div>
		</section>
		<section class="jumbotron text-center">
        <div class="container">
			<h1 class="jumbotron-heading">Retos más completados</h1><br>
  				<div class="container col-12">
			<?php 
				$res = getRankingretos();
				$pos = 1;
				echo '<table class="col-12">
				<tr>
				<th class="col-4">
					Posicion
				</th>
				<th class="col-4">
					Reto
				</th>
				<th class="col-4">
					Veces Completado<br>
				</th>
				</tr>';
				foreach($res as $registro){
					echo '
					<tr>
					<th class="col-4">
						'.$pos.'
					</th>
					<td class="col-4">
						'.$registro['titulo'].'
					</td>
					<td class="col-4">
						'.$registro['vecesCompletado'].'<br>
					</td>
					</tr>
					';
					$pos = $pos+1;
				}
				echo '</table>';
			?>
			</div>
			<div class="container col-3"></div>
          <p>
            <!--<a href="./crearReto.php" class="btn btn-secondary my-2" type="button">ADD A CHALLENGE</a>-->             
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