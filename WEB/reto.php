
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
	<link rel="stylesheet" href="css/cssPropio.css">
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
		<?php $idReto = $_GET['id'];
				if(is_numeric($idReto)){
					$reto_dat = datosReto($idReto);
					$hastagsReto = obtenerCategoriaReto($reto_dat['id']);
					$rutaFile = buscarFichero($reto_dat['id']);
					if($reto_dat['existencia'] != 0){
						echo '
						<h3>'.$reto_dat['nombre'].'</h3>
						<p>'.$reto_dat['desc'].'</p>
						<table class="col-lg-8 offset-lg-2">';
							if(isset($_GET['checked'])){
								if($_GET['checked']==1){
									echo '<p class="correctoSuPrimo borderPerfe">¡Buena flag, a por todas!</p>';
								}elseif($_GET['checked']==0){
									echo '<p class="errorSuPrimo borderPerfe">Esa flag no esta bien, vuelve a intentarlo!</p>';
								}elseif($_GET['checked']==2){
									echo '<p class="errorSuPrimo borderPerfe">No puedes validar tu propio reto...</p>';
								}else{
									echo '<p class="errorSuPrimo borderPerfe">No cambies peticiones, esta feo eso</p>';
								}
							}
							echo '
							<tr>
								<th>Creador: </th>
								<td>'.$reto_dat['creador'].'</td>
							</tr>
							<tr>
								<th>Fecha de creación: </th>
								<td>'.$reto_dat['dataPub'].'</td>
							</tr>
							<tr>
								<th>Categoria: </th>
								<td>'.$hastagsReto.'</td>
							</tr>
							<tr>
								<th>Dificultad: </th>
								<td>';
									if($reto_dat['punt']<=10){
										echo 'Facil';
									}elseif($reto_dat['punt']>10 && $reto_dat['punt']<=50){
										echo 'Medio';
									}else{
										echo 'Dificil';
									} 
									echo '</td>
							</tr>
							<tr>
								<th>Puntuacion maxima: </th>
								<td>'.$reto_dat['punt'].'</td>
							</tr>
							<tr>
								<th>Archivos adjuntos: </th>
								';
								if(strlen($rutaFile)>0){
									echo '<td>Si</td>';	
									echo '<td><a href="/'.$rutaFile.'" target="_blank" class="cart-btn"> Descargar</a></td>';
								}else{
									echo '<td>No</td>';	
								}
								echo '
							</tr>
							<tr>
								<th>Flag: </th>
								<td>
									<form action="php/checkReto.php?id='.$reto_dat['id'].'" method="post">'; 
									$realizado_arr = retoRealizado($reto_dat['id'],$userDat['iduser']);
									if($realizado_arr['existencia'] != 0){
										echo $reto_dat['flag'].'<br></td>
										</form>
										</td></tr><tr><th>
										Puntuacion:
										</th><td>
										'.$realizado_arr['punt'].' / '.$reto_dat['punt'].'
										</td></tr>';
									}else{
										echo '
										<input type="text" id="flag" name="flag" placeholder="'.taparTexto($reto_dat['flag'],0).'"><br></td><td>
										<input type="submit" value="Enviar" class="cart-btn">
										</form></td></tr>';
									}
								echo '
						</table>
						<br>
						';
					}else{
						echo '
						<h3><span class="orange-text">404</span> Error</h3>
						<p>El reto que estas buscando no existe en la plataforma. X_x</p><br>';
					}
				}else{
					echo '
					<h3><span class="orange-text">404</span> Error</h3>
					<p>El reto que estas buscando no existe en la plataforma. X_x</p><br>';
				}
			?>
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