<?php
    ///////////////////////////////
    ///// FUNCIONES GLOBALES //////
    ///////////////////////////////
    
    // Funcion para elegir un numero random y crear un hash.
    function genRandHash(){
        $numRandom = rand(7,754863);
        $hash = hash('sha256',$numRandom);
        return $hash;
    }

    function taparTexto($texto,$len,$strt=0){
        $textoTapado = "";
        if($len == 0){
            for ($x = 1; $x <= strlen($texto); $x++) {
                $textoTapado = $textoTapado."*";
            }
        }else{
            for ($x = $strt+1; $x <= $len; $x++) {
                $textoTapado = $textoTapado."*";
            }
            $textoTapado = substr($texto,0,$strt).$textoTapado.substr($texto,$len);
        }
        return $textoTapado;
    }

    function crearDirectorioUsuario($userMail){
        //Crear Directiodio personal
        $userDat = datosUsuario($userMail);

        $cmd = 'sudo /home/josep/scripts/makeDirectorioUsuario.sh '.$userDat['username'];
        shell_exec( $cmd );
        //mkdir('/var/www/html/user/'.$userDat['username'],0777);
    }

    //////////////////////////
    ///// VPN FUNCTIONS //////
    //////////////////////////

    function crearVPNfile($userMail){
        //Lanzar scritp de creacion de VPN
        $userDat = datosUsuario($userMail);

        $cmd = '/home/josep/scripts/sendReq.sh vpn '.$userDat['username'];
        shell_exec( $cmd );
    }

    function moverVPNfile($userMail){
        //Mover el archivo de configuracion al "home" del usuario de la web
        $userDat = datosUsuario($userMail);

        $cmd = 'sudo /home/josep/scripts/moverVPNfile.sh '.$userDat['username'];
	    $resp = shell_exec( $cmd );
    	return $resp;
    }

    function eliminarVPNfile($userMail){
        //Lanzar scritp de eliminacion VPN
        $userDat = datosUsuario($userMail);

        $cmd = 'sudo /home/josep/scripts/removeVPN.sh '.$userDat['username'].' &';
        shell_exec( $cmd );
    }

    ///////////////////////////////
    ///// DATABASE FUNCTIONS //////
    ///////////////////////////////

    function insertarDatos($username,$email,$fname,$lname,$passHash){
        require('connecta_db_persistent.php');

        $sql = 'INSERT INTO users (username,mail,userFirstName,userLastName,passHash,creationDate,active,activationCode) VALUES (:username,:mail,:fname,:lname,:passHash,CURTIME(),:active,:actCode);';
        $usuaris = $db->prepare($sql);
        $usuaris->execute(array(":username"=>$username,":mail"=>$email,":fname"=>$fname,":lname"=>$lname,":passHash"=>$passHash,":active"=>"0",":actCode"=>genRandHash()));
    
    }

    function verificarExistencia($username,$email){
        require('connecta_db_persistent.php');
        
        $sql = 'SELECT * FROM users WHERE (UPPER(username)=UPPER(:usrFilt) OR UPPER(mail)=UPPER(:mailFilt))'; 
        $verificaExistent = $db->prepare($sql);
        $verificaExistent->execute(array(":usrFilt"=>$username,":mailFilt"=>$email));
        $NumUsersExistents = $verificaExistent->rowCount();

        return $NumUsersExistents;
    }
    
    function verificarExpiracion($userMail){
        require('connecta_db_persistent.php');

        $userMail = strtoupper($userMail);

        $sql = 'SELECT * FROM users WHERE UPPER(mail)=UPPER(:mailFilt) AND resetPassExpiry >= CURTIME()'; 
        $verificaTiempo = $db->prepare($sql);
        $verificaTiempo->execute(array(":mailFilt"=>$userMail));
        $NumUsersExistents = $verificaTiempo->rowCount();

        return $NumUsersExistents;
    }

    function updateSignIn($idUser){
        require('connecta_db_persistent.php');
        
        $sql = 'UPDATE users SET lastSignIn = CURTIME() WHERE iduser = :iduser';
        $update = $db->prepare($sql);
        $update->execute(array(":iduser"=>$idUser));
    }
 
    function getUserCode($type,$userMail){
        require('connecta_db_persistent.php');

        if($type == 1){
            // Para pillar el codigo de activacion:
            $sql = 'SELECT activationCode FROM users WHERE UPPER(mail) = :mail';
            $consulta = $db->prepare($sql);
            $consulta->execute(array(":mail"=>$userMail));

            foreach($consulta as $registro){
                $userHash = $registro['activationCode'];
            }
        }elseif($type == 2){
            // Para pillar el codigo de restauracion de contraseña:
            $sql = 'SELECT resetPassCode FROM users WHERE UPPER(mail) = :mail OR UPPER(username) = :user'; 
            $consulta = $db->prepare($sql);
            $consulta->execute(array(":mail"=>$userMail, ":user"=>$userMail));

            foreach($consulta as $registro){
                $userHash = $registro['activationCode'];
            }
        }
        return $userHash;
    } 

    function insertUserCode($type,$userMail,$hash){
        require('connecta_db_persistent.php');
        //Lo del type aqui queda raro pero al final no he hecho type 1 como en la funcion de borrar
        if($type == 2){
            // Insertar el codigo de restauracion de contraseña:
            $sql = 'UPDATE users SET resetPassCode = :code, resetPassExpiry = CURTIME() + INTERVAL 30 MINUTE WHERE UPPER(mail) = :mail;';
            $update = $db->prepare($sql);
            $update->execute(array("code"=>$hash,":mail"=>$userMail));
        }
        
    } 

    function eliminarUserCode($type,$userMail,$hash){
        require('connecta_db_persistent.php');
        $userMail = strtoupper($userMail);

        if($type == 1){
            // Borrar el codigo de activacion:
            $sql = 'UPDATE users SET activationCode = NULL WHERE UPPER(mail) = :mail AND activationCode = :code';
            $update = $db->prepare($sql);
            $update->execute(array(":mail"=>$userMail, ":code"=>$hash));
        }elseif($type == 2){
            // Borrar el codigo de restauracion de contraseña:
            $sql = 'UPDATE users SET resetPassCode = NULL, resetPassExpiry = NULL WHERE UPPER(mail) = :mail AND resetPassCode = :code;';
            $update = $db->prepare($sql);
            $update->execute(array(":mail"=>$userMail, "code"=>$hash,));
        }
    } 

    function datosUsuario($usuario){
        require('connecta_db_persistent.php');
        $iduser = ''; $username = ''; $userFirstName = ''; $userLastName = ''; $hash = ''; $activo = ''; $mail = '';
        
        $sql = 'SELECT iduser,
                        CONCAT(UPPER(SUBSTRING(userFirstName,1,1)),
                        LOWER(SUBSTRING(userFirstName,2))) as userFirstName,
                        CONCAT(UPPER(SUBSTRING(userLastName,1,1)),
                        LOWER(SUBSTRING(userLastName,2))) as userLastName,
                        DATE(creationDate) as creationDate,
                        username,passhash,active,mail
                FROM `users` 
                WHERE (UPPER(username)=:filtre OR UPPER(mail)=:filtre) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$usuario,));

        $res = $consulta->rowCount();
        
        foreach($consulta as $registro){
            $iduser = $registro['iduser'];
            $username = $registro['username'];
            $userFirstName = $registro['userFirstName'];
            $userLastName = $registro['userLastName'];
            $hash = $registro['passhash'];
            $activo = $registro['active'];
            $mail = $registro['mail'];
            $fecha = $registro['creationDate'];
        }
        $datosUser = array('iduser'=>$iduser,
                            'username'=>$username,
                            'userFirstName'=>$userFirstName,
                            'userLastName'=>$userLastName,
                            'passhash'=>$hash,
                            'active'=>$activo,
                            'mail'=>$mail,
                            'fecha'=>$fecha,
                            'existe'=>$res);
        return $datosUser; 
    }
    
    function activarUsuario($code,$mail){
        require('connecta_db_persistent.php');
        
        $sql = 'UPDATE users SET active = 1, activationDate = CURTIME() WHERE activationCode = :code AND mail = :mail';
        $update = $db->prepare($sql);
        $update->execute(array(":code"=>$code, ":mail"=>$mail));
    }

    function camviarContraseña($userMail,$code,$passHash){
        require('connecta_db_persistent.php');
        
        $userMail = strtoupper($userMail);

        $sql = 'UPDATE users SET passHash = :contra WHERE resetPassCode = :code AND UPPER(mail) = :mail AND resetPassExpiry >= CURTIME()';
        $update = $db->prepare($sql);
        $update->execute(array(":contra"=>$passHash, ":code"=>$code, ":mail"=>$userMail));
    }

    ///////////////////////////////
    /////// DATABASE RETOS ////////
    ///////////////////////////////

    //Funcion de insertar un reto (tabla: reptes)
    function insertarReto($titulo,$desc,$flag,$punt,$usuario){
        require('connecta_db_persistent.php');
        $sql = 'INSERT INTO reptes (creador,nomRepte,descripcio,puntuacio,flag,dataPub) VALUES (:usuario,:titulo,:descripcion,:punt,:flag,CURTIME());';
        $reto = $db->prepare($sql);
        $reto->execute(array(":usuario"=>$usuario,":titulo"=>$titulo,":descripcion"=>$desc,":punt"=>$punt,":flag"=>$flag));
    }

    //Funcion de insertar un reto a una categoria (tabla: categoriaRepte)
    function insertarRetoCategoria($idReto,$cat){
        require('connecta_db_persistent.php');
        
        $data = datosCategoria($cat);
        if($data['existencia']!=0){
            $sql = 'INSERT INTO categoriaRepte (idRepte,nomCategoria) VALUES (:idRep,:nomCat);';
            $retoHast = $db->prepare($sql);
            $retoHast->execute(array(":idRep"=>$idReto,":nomCat"=>$data['nombre']));
        }
    }

    //Funcion de insertar una categoria (tabla: categoria)
    function insertarCategoria($nom,$descripcio){
        require('connecta_db_persistent.php');
        $sql = 'INSERT INTO categoria (nom,descripcio) VALUES (:nom,:descripcio);';
        $cat = $db->prepare($sql);
        $cat->execute(array(":nom"=>$nom,":descripcio"=>$descripcio));
    }
    //Funcion para buscar un fichero en su ruta (tabla:fitxer)
    function buscarFichero($id){
        require('connecta_db_persistent.php');
        $url="";
        $sql = 'SELECT url FROM fitxer WHERE IDrepte = :id;';
        $cat = $db->prepare($sql);
        $cat->execute(array(":id"=>$id));
        foreach($cat as $registro){
            $url = $registro['url'];
        }
        return $url;
    }

    //Funcion de insertar un fichero (tabla: fitxer)
    function insertarFitxer($nomCod,$url,$IDrepte){
        require('connecta_db_persistent.php');
        $sql = 'INSERT INTO fitxer (nomCodificat,url,IDrepte) VALUES (:nom,:url,:IDrepte);';
        $file = $db->prepare($sql);
        $file->execute(array(":nom"=>$nomCod,":url"=>$url, ":IDrepte"=>$IDrepte));
    }
    
    // Funcion de obtener los datos de una categoria
    function datosCategoria($filtro){
        require('connecta_db_persistent.php');
        $filtro = strtoupper($filtro);
        $nombre = ''; $desc = '';
        
        $sql = 'SELECT nom, descripcio
                FROM categoria 
                WHERE (UPPER(nom)=:filtre) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$filtro,));

        $existencia = $consulta->rowCount();
        
        foreach($consulta as $registro){
            $nombre = $registro['nom'];
            $desc = $registro['descripcio'];
        }
        $datosCat = array('nombre'=>$nombre,
                            'desc'=>$desc,
                            'existencia'=>$existencia);
        return $datosCat; 
    }

    // Funcion de obtener los datos de un reto
    function datosReto($filtro){
        require('connecta_db_persistent.php');
        $filtro = strtoupper($filtro);
        $id = ''; $creador = ''; $nombre = ''; $desc = ''; $punt = ''; $flag = ''; $dataPub = '';
        
        $sql = 'SELECT ID, creador, nomRepte, descripcio, puntuacio, flag, dataPub
                FROM `reptes` 
                WHERE (ID=:filtre OR UPPER(nomRepte)=:filtre) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$filtro,));

        $existencia = $consulta->rowCount();
        
        foreach($consulta as $registro){
            $id = $registro['ID'];
            $creador = $registro['creador'];
            $nombre = $registro['nomRepte'];
            $desc = $registro['descripcio'];
            $punt = $registro['puntuacio'];
            $flag = $registro['flag'];
            $dataPub = $registro['dataPub'];
        }
        $datosReto = array('id'=>$id,
                            'creador'=>$creador,
                            'nombre'=>$nombre,
                            'desc'=>$desc,
                            'punt'=>$punt,
                            'flag'=>$flag,
                            'dataPub'=>$dataPub,
                            'existencia'=>$existencia);
        return $datosReto; 
    }

    function obtenerCategoriaReto($retoID){
        require('connecta_db_persistent.php');
        $hastags = "";
        $sql = 'SELECT nomCategoria FROM categoriaRepte WHERE idRepte = :filtre;';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$retoID));
        foreach($consulta as $registro){
            $hastags = $hastags." ".$registro['nomCategoria'];
        }
        return $hastags; 
    }

    // Funcion de obtener todos los retos o lo de un hastag / usuario
    function obtenerRetos($accion,$hastagUsr){
        require('connecta_db_persistent.php');
        $ids = array();
        $hastagUsr = strtoupper($hastagUsr);
        if($accion == 1){
            //Obtener todos los retos (variable hastag no importa)
            $sql = 'SELECT ID FROM reptes;';
            $consulta = $db->prepare($sql);
            $consulta->execute();
            foreach($consulta as $registro){
                array_push($ids, $registro['ID']);
            }
            return $ids; 
        }elseif($accion == 2){
            //Obtener los retos de un hastag en concreto
            $sql = 'SELECT r.ID FROM reptes AS r JOIN categoriaRepte AS c ON r.ID = c.idRepte WHERE UPPER(c.nomCategoria) = :filtre;';
            $consulta = $db->prepare($sql);
            $consulta->execute(array(":filtre"=>$hastagUsr));
            foreach($consulta as $registro){
                array_push($ids, $registro['ID']);
            }
            return $ids; 
        }elseif($accion == 3){
            //Obtener los retos de un usuario
            $sql = 'SELECT ID FROM reptes WHERE UPPER(creador) = :filtre;';
            $consulta = $db->prepare($sql);
            $consulta->execute(array(":filtre"=>$hastagUsr));
            foreach($consulta as $registro){
                array_push($ids, $registro['ID']);
            }
            return $ids;
        }elseif($accion == 4){
            //Obtener el ultimo reto (var hastag no importa)
            $sql = 'SELECT ID FROM reptes ORDER BY dataPub DESC LIMIT 1;';
            $consulta = $db->prepare($sql);
            $consulta->execute();
            foreach($consulta as $registro){
                array_push($ids, $registro['ID']);
            }
            return $ids;
        }else{/*no aser na (por ahora)*/}
    }

    function obtenerCategorias(){
        require('connecta_db_persistent.php');
        $nombres = array();

        //Obtener todas las cats miau
        $sql = 'SELECT nom FROM categoria;';
        $consulta = $db->prepare($sql);
        $consulta->execute();
        foreach($consulta as $registro){
            array_push($nombres, $registro['nom']);
        }
        return $nombres; 
    }

    ///////////////////////////
    ////// PUNTUACIONES ///////
    ///////////////////////////
    
    function existeReg($retoID,$userID){
        require('connecta_db_persistent.php');
        $retoID = strtoupper($retoID);
        $userID = strtoupper($userID);
            
        $sql = 'SELECT * FROM `puntuaciones` 
                WHERE (UPPER(IDuser)=:filtreUsr AND UPPER(IDrepte)=:filtreChll) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtreUsr"=>$userID,":filtreChll"=>$retoID));

        $existencia = $consulta->rowCount();
        return $existencia;
    }

    function sumarIntentoFallido($retoID,$userID){
        require('connecta_db_persistent.php');
        if(existeReg($retoID,$userID) == 0){
            $sql = 'INSERT INTO puntuaciones (IDuser,IDrepte,intentosFallidos,puntuacionObtenida) VALUES (:userid,:retoid,1,null);';
            $inser = $db->prepare($sql);
            $inser->execute(array(":userid"=>$userID, "retoid"=>$retoID));
        }else{
            $sql = 'UPDATE puntuaciones SET intentosFallidos = intentosFallidos + 1 WHERE IDuser = :userid AND IDrepte = :retoid;';
            $update = $db->prepare($sql);
            $update->execute(array(":userid"=>$userID, "retoid"=>$retoID));
        }
    }

    function añadirPuntuacion($retoID,$userID){
        require('connecta_db_persistent.php');
        $reto_dat = datosReto($retoID);
        if(existeReg($retoID,$userID) == 0){
            $sql = 'INSERT INTO puntuaciones (IDuser,IDrepte,intentosFallidos,puntuacionObtenida) VALUES (:userid,:retoid,null,:puntuacion);';
            $inser = $db->prepare($sql);
            $inser->execute(array(":userid"=>$userID, "retoid"=>$retoID, ":puntuacion"=>$reto_dat['punt']));
        }else{
            // Contemplar que no hayn puntos negativos... Que se quede en
            $sql = 'UPDATE puntuaciones SET puntuacionObtenida = :puntuacion - intentosFallidos  WHERE IDuser = :userid AND IDrepte = :retoid;';
            $update = $db->prepare($sql);
            $update->execute(array(":userid"=>$userID, "retoid"=>$retoID, ":puntuacion"=>$reto_dat['punt']));
        }
    }

    function retoRealizado($retoID,$userID){
        require('connecta_db_persistent.php');
        $ret = array("punt" => -1, "existencia"=>0);
        $puntuacion = -1;
        if(existeReg($retoID,$userID) != 0){
            $sql = 'SELECT * FROM puntuaciones 
                    WHERE (UPPER(IDuser)=:filtreUsr AND UPPER(IDrepte)=:filtreChll) AND puntuacionObtenida >= 0
                    LIMIT 1;';
            $consulta = $db->prepare($sql);
            $consulta->execute(array(":filtreUsr"=>$userID,":filtreChll"=>$retoID));

            $existencia = $consulta->rowCount();

            foreach($consulta as $registro){
                $puntuacion = $registro['puntuacionObtenida'];
            }
            $ret = array("punt" => $puntuacion, "existencia"=>$existencia);
        }
        return $ret;
    }

    ////////////////////////////////////
    ////// FUNCIONES DE MAQUINAS ///////
    ////////////////////////////////////

    // Funcion para seleccionar las id de las maquinas acivas
    function maquinasActivas($tipo){
        require('connecta_db_persistent.php');
        $ids = array();
        if($tipo == 1){
            $sql = "SELECT idMaquina FROM maquinas WHERE active = 1 AND tipo = 'ataque';";
            $consulta = $db->prepare($sql);
            $consulta->execute();
            foreach($consulta as $registro){
                array_push($ids, $registro['idMaquina']);
            }
        }elseif($tipo == 2){
            $sql = "SELECT idMaquina FROM maquinas WHERE active = 1 AND tipo = 'defensa';";
            $consulta = $db->prepare($sql);
            $consulta->execute();
            foreach($consulta as $registro){
                array_push($ids, $registro['idMaquina']);
            }
        }else{/* no aser nada jeje */}
        return $ids;
    }

     // Funcion de obtener los datos de una maquina
     function datosMaquina($filtro){
        require('connecta_db_persistent.php');
        $filtro = strtoupper($filtro);
        $id = ''; $nombre = ''; $desc = ''; $active = ''; $ip = ''; $tipo = '';
        
        $sql = 'SELECT idMaquina, nomMaquina, descripcion, active, ip, tipo
                FROM `maquinas` 
                WHERE (idMaquina=:filtre OR UPPER(nomMaquina)=:filtre) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$filtro));

        $existencia = $consulta->rowCount();
        
        foreach($consulta as $registro){
            $id = $registro['idMaquina'];
            $nombre = $registro['nomMaquina'];
            $desc = $registro['descripcion'];
            $active = $registro['active'];
            $tipo = $registro['tipo'];
            $ip = $registro['ip'];
        }
        $datosMaquina = array('id'=>$id,
                            'nombre'=>$nombre,
                            'desc'=>$desc,
                            'active'=>$active,
                            'ip'=>$ip,
                            'tipo'=>$tipo,
                            'existencia'=>$existencia);
        return $datosMaquina; 
    }

    // Actualizar maquinas (desde updateMachines.php)
    function getIdMaquina($nombre){
        require('connecta_db_persistent.php');
        $nombre = strtoupper($nombre);
        $sql = 'SELECT idMaquina FROM maquinas WHERE UPPER(nomMaquina) = :nom LIMIT 1;';
        $consulta = $db->prepare($sql); 
        $consulta->execute(array(":nom"=>$nombre));
        foreach($consulta as $registro){
            $id = $registro['idMaquina'];
        }
        return $id;
    }

    function desactivarMaquinas(){
        require('connecta_db_persistent.php');
        $sql = 'UPDATE maquinas SET active = :zero';
        $update = $db->prepare($sql);
        $update->execute(array(":zero"=>0));

    }

    function activarMaquinas($id){
        require('connecta_db_persistent.php');
        $sql = 'UPDATE maquinas SET active = 1 WHERE idMaquina = :id';
        $update = $db->prepare($sql);
        $update->execute(array(":id"=>$id));
    }

    function setIP($id,$ip){
        //Sobra
        require('connecta_db_persistent.php');
        $sql = 'UPDATE maquinas SET ip = :ip WHERE idMaquina = :id';
        $update = $db->prepare($sql);
        $update->execute(array(":id"=>$id, ":ip"=>$ip));
    }

    ////////////////////////////////
    ////// RANKING FUNCTIONS ///////
    ////////////////////////////////

    function getRanking(){
        require('connecta_db_persistent.php');
        $usuario = ''; $puntuacion = '';
        $ranking = array();
        $usuario_punt = array();

        $sql = 'SELECT u.username as usuario, sum(p.puntuacionObtenida) as puntuacionTotal 
        FROM puntuaciones as p 
        JOIN users as u ON p.IDuser = u.iduser 
        GROUP BY u.username
        ORDER BY 2 DESC;';
        $select = $db->prepare($sql);
        $select->execute(array());
        foreach($select as $registro){
            $usuario = $registro['usuario'];
            $puntuacion = $registro['puntuacionTotal'];
            $usuario_punt = array("usuario"=>$usuario,"puntuacion"=>$puntuacion);
            array_push($ranking, $usuario_punt);
        }
        return $ranking;
    }

    function getRankingretos(){
        require('connecta_db_persistent.php');
        $titulo = ''; $vecescomp = '';
        $ranking = array();
        $reto_comp = array();

        $sql = 'SELECT r.nomRepte as titulo, count(p.IDrepte) as vecesCompletado 
        FROM puntuaciones as p 
        JOIN reptes as r ON p.IDrepte = r.ID 
        GROUP BY 1 
        ORDER BY 2 DESC LIMIT 5;';

        $select = $db->prepare($sql);
        $select->execute(array());
        foreach($select as $registro){
            $titulo = $registro['titulo'];
            $vecescomp = $registro['vecesCompletado'];
            $reto_comp = array("titulo"=>$titulo,"vecesCompletado"=>$vecescomp);
            array_push($ranking, $reto_comp);
        }
        return $ranking;
    }

    ////////////////////////////////////
    ////// FUNCIONES DE TEMARIOS ///////
    ////////////////////////////////////

    // Funcion para seleccionar las id de las maquinas acivas
    function gettemarioID(){
        require('connecta_db_persistent.php');
        $ids = array();
            $sql = "SELECT idTema FROM temario;";
            $consulta = $db->prepare($sql);
            $consulta->execute();
            foreach($consulta as $registro){
                array_push($ids, $registro['idTema']);
            }
        return $ids;
    }

    // Funcion que devuelve toda la informacion del temario
    function datostemario($filtro){
        require('connecta_db_persistent.php');
        $id = ''; $titulo = ''; $desc = '';$img = '';$url = '';
        
        $sql = 'SELECT idTema, titulo, descripcion, `url`
                FROM temario 
                WHERE (idTema=:filtre) 
                LIMIT 1';
        $consulta = $db->prepare($sql);
        $consulta->execute(array(":filtre"=>$filtro));
        
        foreach($consulta as $registro){
            $id = $registro['idTema'];
            $titulo = $registro['titulo'];
            $desc = $registro['descripcion'];
            $url = $registro['url'];
//          $img = $registro['imagen']; de momento no hay imagenes
        }
        $datosTema = array('id'=>$id,
                            'titulo'=>$titulo,
                            'desc'=>$desc,
                            'url'=>$url
                        );
        return $datosTema; 
    }

    ////////////////////////////////
    ////// MAILING FUNCTIONS ///////
    ////////////////////////////////

    use PHPMailer\PHPMailer\PHPMailer;
    
    function mailActivateUser($userMail){
        $userMailUpp = strtoupper($userMail);
        $datosUser = datosUsuario($userMailUpp);
        
        $userCode = getUserCode(1,$userMailUpp);

        $contMail = contenidoMail(1,$userMail,$userCode,$datosUser);        
        sendMail($contMail['assunto'],$contMail['mensaje'],$userMail);
    }

    function mailResetPassword($userMail){
        $userMailUpp = strtoupper($userMail);
        $datosUser = datosUsuario($userMailUpp);

        $userCode = genRandHash();
        insertUserCode('2',$userMailUpp,$userCode);

        $contMail = contenidoMail(2,$userMail,$userCode,$datosUser);
        sendMail($contMail['assunto'],$contMail['mensaje'],$userMail);
    }

    function mailForgotPassword($userMail){
        $userMailUpp = strtoupper($userMail);
        $datosUser = datosUsuario($userMailUpp);

        $userCode = genRandHash();
        insertUserCode('2',$userMailUpp,$userCode);

        $contMail = contenidoMail(4,$userMail,$userCode,$datosUser);
        sendMail($contMail['assunto'],$contMail['mensaje'],$userMail);
    }

    function PasswordNotify($userMail){
        $userMailUpp = strtoupper($userMail);
        $datosUser = datosUsuario($userMailUpp);

        $contMail = contenidoMail(3,$userMail,'',$datosUser);
        sendMail($contMail['assunto'],$contMail['mensaje'],$userMail);
    }


    function sendMail($assunto,$mensaje,$userMail){ 
        
        require '../vendor/autoload.php';

        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->SMTPDebug = 0;
        //$mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = ''; //Nuestro SMTP
        $mail->Port = 25;

        //Dades del correu electrònic
        $mail->SetFrom('accounts_no-reply@hardbox.ga','HARDBOX');
        $mail->Subject = $assunto;
        $mail->MsgHTML($mensaje);
        //$mail->addAttachment("fitxer.pdf");
        
        //Destinatari
        $address = $userMail;
        $mail->AddAddress($address, 'HARDBOX');

        //Enviament
        $result = $mail->Send();
        if(!$result){
            echo 'Error: ' . $mail->ErrorInfo;
        }else{
            //header("Location:../login.php?redirected=0");
            //exit;
        }
    }

    function contenidoMail($tipo,$userMail,$userCode,$datosUser){
        $assunto = '';
        $mensaje = '';
        
        if($tipo == 1){
            // Correo de activacion
            $assunto = 'Activacion cuenta HardBox';
            $mensaje = '
            Buenas Sr./Sra. '.$datosUser['userFirstName']." ".$datosUser['userLastName'].',
            <br><br> 
            Hemos recibido una solicitud de activacion en su cuenta de HardBox vinculada a su correo electronico. 
            <b>Si ha sido usted</b>, por favor active su cuenta con las intrucciones que vera en la parte inferior del correo. <br>
            <b>Si no ha sido usted</b>, no hace falta que haga caso a este correo ya que su cuenta no esta activa, pero quiere decir que alguien ha introducido su correo en <a href="hardbox.ga">Hardbox</a>. <br>
            <br><br> 
            Puede activar su cuenta aqui: 
            <a href="https://hardbox.ga/php/activateAcc.php?code='.$userCode.'&mail='.$userMail.'">aqui</a> 
            <br><br> 
            En caso de no poder acceder por el vinculo de arriba, haga click en este enlace: 
            https://hardbox.ga/php/activateAcc.php?code='.$userCode.'&mail='.$userMail.'
            ';
        }elseif($tipo == 2){
            // Correo de reset password
            $assunto = 'Cambio de contraseña HardBox';
            $mensaje = '
            Buenas Sr./Sra. '.$datosUser['userFirstName']." ".$datosUser['userLastName'].',
            <br><br> 
            Hemos recibido su solicitud de cambio de contraseña para su cuenta de HardBox vinculada a su correo electronico. 
            <b>Si ha sido usted</b>, siga las intrucciones que vera en la parte inferior del correo. <br>
            <b>Si no ha sido usted</b>, tenga cuidado que alguien quiere cambiar su contraseña, aunque sin este codigo de verificacion no podra realizar el cambio. <br>
            <br><br> 
            Para ir al cambio de contraseña haga click 
            <a href="https://hardbox.ga/php/formPwd.php?code='.$userCode.'&mail='.$userMail.'">aqui</a> 
            <br><br> 
            En caso de no poder acceder por el vinculo de arriba, haga click en este enlace: 
            https://hardbox.ga/php/formPwd.php?code='.$userCode.'&mail='.$userMail.'
            ';
        }elseif($tipo == 3){
            // Notificacion de reset password
            $assunto = 'Cambio reciente de contraseña en HardBox';
            $mensaje = '
            Buenas Sr./Sra. '.$datosUser['userFirstName']." ".$datosUser['userLastName'].',
            <br><br> 
            Se ha cambiado de credenciales de su cuenta de HardBox. <br> 
            <b>Si ha sido usted</b>, no hace falta que haga caso a este correo. <br> 
            <b>Si no ha sido usted</b>, tenga cuidado que alguien ha cambiado la contraseña de su cuenta. <br> 
            ';
        }elseif($tipo == 4){
            // Correo de reset password
            $assunto = 'Cambio de contraseña HardBox';
            $mensaje = '
            Buenas Sr./Sra. '.$datosUser['userFirstName']." ".$datosUser['userLastName'].',
            <br><br> 
            No se acuerda de sus credenciales eh! Tranquilo a todos nos puede pasar!. 
            <b>Si ha sido usted</b>, siga las intrucciones que vera en la parte inferior del correo. <br>
            <b>Si no ha sido usted</b>, tenga cuidado que alguien quiere cambiar su contraseña, aunque sin este codigo de verificacion no podra realizar el cambio. <br>
            <br><br> 
            Para ir al cambio de contraseña haga click 
            <a href="https://hardbox.ga/php/formPwd.php?code='.$userCode.'&mail='.$userMail.'">aqui</a> 
            <br><br> 
            En caso de no poder acceder por el vinculo de arriba, haga click en este enlace: 
            https://hardbox.ga/php/formPwd.php?code='.$userCode.'&mail='.$userMail.'
            ';
        }
        return array('assunto' => $assunto, 'mensaje' => $mensaje);
    }
