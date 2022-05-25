
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
            <a class="nav-link" href="./challengs.php">Challenges</a>
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
			<?php 
				if(isset($_GET['user'])){
					$user = $_GET['user'];
					$userDat = datosUsuario($user); 
					if($userDat['existe']!=0){
						echo '
						<h1 class="text-center">'.$userDat['userFirstName'].' </span> '.$userDat['userLastName'].'</h1>
						<br><br>
						<table class="col-lg-9 offset-lg-1">
							<tr>
								<th>Nombre de usuario:</th>
								<td>'.$userDat['username'].'</td>
							</tr>
							<tr>
								<th>Miembro desde:</th>
								<td>'.$userDat['fecha'].'</td>
							</tr>
						</table>
						';
					}else{
						echo '<h1 class="jumbotron-heading">Este usuario no existe</h1>';
					}
				}
			?>

          <p>
            <!--<a href="./crearReto.php" class="btn btn-secondary my-2" type="button">ADD A CHALLENGE</a>-->             
          </p>
        </div>
		</section>
		
		<div class="container">
			<?php
				if(isset($_GET['user'])){
					if($userDat['existe']!=0){
						$retos = obtenerRetos(3,$user);
						if (!empty($retos)){
							echo '<h1 class="text-center">Retos de '.$userDat['userFirstName'].' </span> '.$userDat['userLastName'].'</h1><br>';
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
									<h6>Fecha Publicacion: </h6><p>'.$reto_dat['dataPub'].'</p>
									<h6>Description</h6>
									<p class="card-text">Descripcion .... </p>
									<a href="reto.php?id='.$reto_dat['id'].'" class="cart-btn"> Ver reto</a>
									<small class="text-muted">'.$reto_dat['punt'].' puntos</small>
									</div>
									</div>
									</div>
								';
							}
						}else{
							echo '
							<div class="col-lg-8 offset-lg-2 text-center">
								<div class="section-title">	
									<p>Este usuario aún no ha subido nungún reto.</p>
								</div>
							</div>';
						}
					}else{
						echo '
						<div class="col-lg-8 offset-lg-2 text-center">
							<div class="section-title">	
								<h3><span class="orange-text">404 </span> Error </h3>
								<p>El usuario solicitado no existe.</p>
							</div>
						</div>';
					}
				}
			?>
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