
<?php
  session_start();
  require_once('../php/funciones.php');
  if(!isset($_SESSION['username'])){
    header("Location: ../login.php?redirected=1");
    exit;
  }else{
    require_once('../php/funciones.php');
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
    <link rel="icon" type="image/png" href="./media/image-asset.png">

    <title>Hardbox</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/81f9e3e169.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="../css/album.css" rel="stylesheet">
    <script src="../js/prueba.js"></script>
  </head>

  <body>

    <header>
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
          <a href="../home.php" class="navbar-brand d-flex align-items-center">
            <strong>Hardbox</strong>
          </a>
        

        <ul class="nav justify-content-end">
		  <li class="nav-item">
            <a class="nav-link" href="../challengs.php">Maquinas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../challengs.php">Challenges</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../ranking.php">Ranking</a>
          </li>
		  <li class="nav-item">
		  		<a class="nav-link" href="../myprofile.php"><i class="fas fa-user"></i><b>   -   <?php echo $userDat['userFirstName']." ".$userDat['userLastName'];?> </b></a>
		  </li>
		  <li class="nav-item">
				<a class="nav-link" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i></a>
		  </li>
        </ul>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <br><br>
		  <table class="col-lg-9 offset-lg-1">
        <h2>Ficheros Sudoers y Shadow:</h2>
        <br>
		  <p>Son dos ficheros básicos en el directorio “/etc”.
        El fichero Shadow contiene la información de todas las contraseñas de todos los usuarios. La contraseña de estos usuarios esta en formato hash, lo que hace que podamos usar una web como https://crackstation.net/ para intentar crackear la contraseña.
        <br><br>
        El fichero Sudoers es el que tiene la información de que comandos tiene permitidos cada usuario, que permisos puede acceder y que accesos tiene. Prácticamente es la información de que usuarios tienen permisos rasos, de root…
        <br><br>
        Estos dos ficheros son importantes porque contienen información muy sensible a la hora de proteger el sistema. Es por eso que los permisos tienen que estar bien puestos.
        <br><br>
        Como funcionan los permisos de los ficheros/directorios:
        <br><br>
        Lo primero que haríamos para ver los permisos que tiene X fichero o los ficheros de un directorio es uns “ls -l” y nos mostrará esto: </p>	
        <img class="col-lg-9 offset-lg-1" src="../media/Captura2.PNG" alt="un listado de todos los archivos de la carpeta etc">
        <p>
        <br>
        En este caso podemos ver las dos columnas que pone “root”. La primera es el propietario de el propio archivo y la segunda es el grupo.
        <br>
        </p>
        <img class="col-lg-9 offset-lg-1" src="../media/Captura3.PNG" alt="El listado de los archivos con la columna de propietarios marcada en amarillo">
        <p>
          <br>
          La primera columna es el listado de permisos donde podemos ver los distintos tipos de permisos que tienen los diferentes grupos.
          <br>
        </p>
        <img class="col-lg-9 offset-lg-1" src="../media/Captura4.PNG" alt="El listado de los archivos con la columna de permisos marcada en amarillo">
        <p>
          <br>
          <table class="col-lg-11 offset-lg-1">
            <tr>
              <th><b>Permiso</b></th>
              <th><b>Identifica</b></th>
            </tr>
            <tr>
              <td>d</td>
              <td>Directorio</td>
            </tr>
            <tr>
              <td>b</td>
              <td>Archivo de bloques especiales (Archivos especiales de dispositivo)</td>
            </tr>
            <tr>
              <td>c</td>
              <td>Archivo de caracteres especiales (Dispositivo tty, impresora…)</td>
            </tr>
            <tr>
              <td>l</td>
              <td>Archivo de vinculo o enlace (soft/symbolic link)</td>
            </tr>
            <tr>
              <td>p</td>
              <td>Archivo especial de cauce (pipe o tubería)</td>
            </tr>
          </table>
          <br>
        </p>
        <p>
          <br>
          Los siguientes nueve caracteres identifican que permisos tienen:
          <br>
        </p>
        <table class="col-lg-9 offset-lg-1">
            <tr>
              <th><b>Permiso</b></th>
              <th><b>Identifica</b></th>
            </tr>
            <tr>
              <td>-</td>
              <td>Sin permiso</td>
            </tr>
            <tr>
              <td>r</td>
              <td>Permiso de lectura(<b>r</b>ead)</td>
            </tr>
            <tr>
              <td>w</td>
              <td>Permiso de escritura (<b>w</b>rite)</td>
            </tr>
            <tr>
              <td>x</td>
              <td>Permiso de ejecución (e<b>x</b>ecute)</td>
            </tr>
          </table>
          <p>
            <br>
            La manera para cambiar estos permisos es con el comando “chmod” seguido de los parámetros requeridos.
            <br>
          </p>
          <table class="col-lg-9 offset-lg-1">
            <tr>
              <th><b>Parámetro</b></th>
              <th><b>Nivel</b></th>
              <th><b>Descripción</b></th>
            </tr>
            <tr>
              <td>u</td>
              <td>dueño</td>
              <td>dueño del archivo o directorio(<b>u</b>ser)</td>
            </tr>
            <tr>
              <td>g</td>
              <td>grupo</td>
              <td>grupo al que pertenece el archivo(<b>g</b>roup)</td>
            </tr>
            <tr>
              <td>o</td>
              <td>otros</td>
              <td>todos los demás usuarios que no son el dueño ni del grupo (<b>o</b>thers)</td>
            </tr>
          </table>
          

       
          <h4>Ejemplos: </h4>
          <p>
            <br>
            <b>Dar permiso de ejecución al dueño:</b>
            <br>
            $ chmod u+x hardbox.sh
            <br><br>
            <b>Quitar permiso de ejecución a todos los usuarios:</b>
            <br>
            $ chmod -x hardbox.sh
            <br><br>
            <b>Dar permiso de lectura y escritura a los demás usuarios:</b>
            <br>
            $ chmod o+r+w hardbox.sh
            <br><br>
            <b>Dejar solo permiso de lectura al grupo al que pertenece el archivo:</b>
            <br>
            $ chmod g+r-w-x hardbox.sh
            <br>
          </p>

          <h1>**hacer un form con un par de preguntas sobre los comandos o algo sobre esto**</h1>
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
      <p>hardbox.ga © 2022</p>
    </div>
  </footer>
    
</html>