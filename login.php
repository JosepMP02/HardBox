<?php
  session_start();
    require_once('php/funciones.php');
    if(isset($_SESSION['username'])){
      header("Location: home.php?redirected=1");
      exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="./media/cubologo_azul.png">

    <title>Hardbox</title>

    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/cssPropio.css">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  	<!-- fontawesome -->
	  <script src="https://kit.fontawesome.com/81f9e3e169.js" crossorigin="anonymous"></script>
    <!-- Custom styles for this template -->
    <link href="./css/album.css" rel="stylesheet">
    <!--<script src="./js/prueba.js"></script>-->
  </head>
  <body>
    <div class="parent clearfix">
      <div class="bg-illustration" >
    </div> 
      <div class="login">
        <div class="container">
          <h1>Login</h1>
          <div class="login-form">
          <?php
            if(isset($_COOKIE[session_name()]) && isset($_COOKIE['nombre'])){
                header("Location: home.php");
                exit;
            }
            $username = "";
            if(isset($_GET['redirected'])){
                if($_GET['redirected']==1){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Te tienes que registrar para poder acceder.</p>';
                }elseif($_GET['redirected']==2){
                    if(isset($_GET['username'])){
                        $username = $_GET['username'];
                    }
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Hay un error en el usuario o contraseña. Por favor vuelve a intentarlo.</p>';
                }elseif($_GET['redirected']==3){
                    echo '<p class="mb-4 text-center correctoSuPrimo borderPerfe">Se ha cerrado tu session correctamente.</p>';
                }elseif($_GET['redirected']==0){
                    echo '<p class="mb-4 text-center correctoSuPrimo borderPerfe">Revise su correo para continuar y poder iniciar session.</p>';
                }elseif($_GET['redirected']==4){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Actualmente tu usuario no esta activo. Sentimos las molestias.</p>';
                }elseif($_GET['redirected']==5){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Hay un problema con la activación, contacte con el administrador.</p>';
                }elseif($_GET['redirected']==6){
                    echo '<p class="mb-4 text-center correctoSuPrimo borderPerfe">Tu usuario se ha activado correctamente, ya puede iniciar session.</p>';
                }elseif($_GET['redirected']==7){
                    echo '<p class="mb-4 text-center correctoSuPrimo borderPerfe">Revise su correo para cambiar la contraseña.</p>';
                }elseif($_GET['redirected']==8){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Hay un problema en el cambio de contraseña.</p>';
                }elseif($_GET['redirected']==9){
                    echo '<p class="mb-4 text-center correctoSuPrimo borderPerfe">Su contraseña se ha cambiado correctamente.</p>';
                }elseif($_GET['redirected']==10){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El tiempo para resetear la contraseña ha expirado o la contraseña ya ha sido cambiada.</p>';
                }elseif($_GET['redirected']==223){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El nombre de usuario o correo electronico el cual estas registrando ya existe.</p>';
                }elseif($_GET['redirected']==222){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El usuario y la contraseña introducidas no coinciden. Por favor intentalo de nuevo.</p>';
                }elseif($_GET['redirected']==112){
                    echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El mail que has introducido no existe.</p>';
                }
            }
            ?>
            <!-- LOGIN FORM -->
            <form action="php/loginCheck.php" method="POST">
              <input type="text" placeholder="Username" name="username">
              <input type="password" placeholder="Password" name="password">        
              <button type="submit">LOG-IN</button>
            </form>
              <!-- Button trigger modal -->
              <div class="forget-pass">
                <a type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Forgot Password
                </a>
              </div>  <!-- Modal -->
                  <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                      <div class="modal-body">
                          <!-- Forgot PASSWORD FORM -->

                        <form action="php/sendForgotPassword.php" method="POST">
                          <input type="email" placeholder="Email" name="username" required>
                          <input type="submit" value="Send">
                        </form>
                      </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="forget-pass">
              <a type="button"  data-bs-toggle="modal" data-bs-target="#example2">
                Register
              </a>
            </div>


      <div class="modal fade" id="example2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">REGISTER</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- REGISTER FORM -->
                <?php
					$username = "";
					$mail = "";
					$fname = "";
					$lname = "";
					if(isset($_GET['redirected'])){
						if($_GET['redirected']==221){
							echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">Tienes que rellenar el fonrmulario.</p>';
						}elseif($_GET['redirected']==222){

							if(isset($_GET['username']) && isset($_GET['mail']) && isset($_GET['fname']) && isset($_GET['lname'])){
								$username = $_GET['username'];
								$mail = $_GET['mail'];
								$fname = $_GET['fname'];
								$lname = $_GET['lname'];
							}
							echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El usuario y la contraseña introducidas no coinciden. Por favor intentalo de nuevo.</p>';
						}elseif($_GET['redirected']==223){
							echo '<p class="mb-4 text-center errorSuPrimo borderPerfe">El nombre de usuario o correo electronico el cual estas registrando ya existe.</p>';
						}
					}
			    ?>
              <form method="POST" action="php/register_checker.php" class="signup">
                  <div class="field">
                    <?php  
                        echo '<input id="user" type="text" name="username" placeholder="Username" value="'.$username.'" required>';
                    ?>
                  </div>
                  <div class="field">
                    <?php
                       	echo '<input id="nombre" type="text" name="fname" placeholder="First Name" value="'.$fname.'" required>';
					          ?>
                  </div >
                  <div class="field">
                    <?php
                       	echo '<input id="apellido" type="text" name="lname" placeholder="Last Name" value="'.$lname.'" required>';
					          ?>
                  </div>
                  <div class="field">
                    <?php
                        echo '<input id="Email" type="text" name="email" placeholder="Email Address" value="'.$mail.'" required>';
					?>
                  </div>
                  <div class="field">
                    <input id="password" name="password" type="password" placeholder="Password" required>
                  </div>
                  <div class="field">
                    <input type="password"  name="password2" placeholder="Confirm password" required>
                  </div>
                  <div class="field btn">
                    <div class="btn-layer"></div>
                    <input type="submit" value="Signup">
                  </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
      </div>
      </div>

      </div>
        </div>
    </div>  
    
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


      <script src="./js/scripts.js"></script>
      
</body>
</html>