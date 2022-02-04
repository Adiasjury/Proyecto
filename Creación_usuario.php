<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mensaje='';
if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['aceptar'])) {
    $usu = $_POST['usuario'];
    $pa1 = $_POST['password1'];
    $pa2 = $_POST['password2'];
    $dni = $_POST['dni'];
    $cdp = $_POST['postal'];
    $ema = $_POST['email'];
    $nom = $_POST['nombre'];
    $ape1= $_POST['apellido1'];
    $ape2= $_POST['apellido2'];
    $datos=array($usu, $pa1, $dni, $cdp, $ema,$nom,$ape1,$ape2);
    $vacios=count($datos)-count(array_filter($datos));


    if ($vacios) {
        $mensaje = "Hay datos que son obligatorios y no se han proporcionado.";
    }

    elseif ($pa1 != $pa2) {
        $mensaje = "Las contraseñas no coinciden.";
    }

    elseif (! preg_match('/^[0-9]{8}[A-HJ-NP-TV-Z]?$/', $dni) || letra_nif(strval(substr($dni,0,8)))!=substr($dni,8,1)){
        $mensaje="Revise su DNI.";
    }
    elseif (! filter_var($ema, FILTER_VALIDATE_EMAIL)){
        $mensaje="Debe introducir una dirección de correo electrónico válida.";
    }
    elseif (! preg_match('/^[0-5][0-9]{4}$/', $cdp)) {
        $mensaje="El código Postal debe estar formado por cinco dígitos.";
    }

    else {
        $mensaje = "Usuario creado ".$usu;

        if (isset($_POST['usuario']) && isset($_POST['password1'])
        && isset($_POST['password1']) && isset($_POST['password2'])
        && isset($_POST['dni']) && isset($_POST['postal'])
        && isset($_POST['email']) && isset($_POST['nombre'])
        && isset($_POST['apellido1']) && isset($_POST['apellido2']) ){

          $usu = $_POST['usuario'];
          $pa1 = $_POST['password1'];
          $pa2 = $_POST['password2'];
          $dni = $_POST['dni'];
          $cdp = $_POST['postal'];
          $ema = $_POST['email'];
          $nom = $_POST['nombre'];
          $ape1= $_POST['apellido1'];
          $ape2= $_POST['apellido2'];
          $numa=mt_Rand(100, 999);

        try {
          $datos='datos.txt';
          $todos_los_datos=file($datos);
          $servidor=rtrim($todos_los_datos[0]);
          $base_de_datos=rtrim($todos_los_datos[1]);
          $usuario=rtrim($todos_los_datos[2]);
          $clave=rtrim($todos_los_datos[3]);
          $serverName = "$servidor";
          $connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
          $conn = sqlsrv_connect( $serverName, $connectionInfo);
          if( $conn === false ) {
               die( print_r( sqlsrv_errors(), true));
          }
          $sql1 = "select usuario from usuarios where usuario='$usu'";
          $sql2 = "select usuario from usuarios where dni='$dni'";
          $sql3 = "select usuario from usuarios where email='$ema'";

          $stmt1 = sqlsrv_query( $conn, $sql1);
          $stmt2 = sqlsrv_query( $conn, $sql2);
          $stmt3 = sqlsrv_query( $conn, $sql3);
          $p=0;
          while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            if ( $row != null) {
                $mensaje="El usuario ya existe";
                $p=1;
              }
          }
          while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
            if ($row != null) {
              $mensaje="El DNI ya existe";
              $p=1;
            }
          }
          while($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
            if ($row != null ) {
              $mensaje='El email ya existe';
              $p=1;
            }
          }

          if( $stmt1 === false || $stmt2 === false  || $stmt3 === false  ) {
               die( print_r( sqlsrv_errors(), true));
          }

          if ($p == 1){
            echo "";
          }
            else{
              $sql = "insert into usuarios (usuario,nombre,apellido1,apellido2,password,postal,email,num_azar,dni) values (?,?,?,?,?,?,?,?,?)";
              $params = array($usu,$nom,$ape1,$ape2,$pa1,$cdp,$ema,$numa,$dni);
/*
              require 'vendor/phpmailer/phpmailer/src/Exception.php';
              require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
              require 'vendor/phpmailer/phpmailer/src/SMTP.php';

              $mail = new PHPMailer();
              //Tell PHPMailer to use SMTP
              $mail->isSMTP();
              //Enable SMTP debugging
              // SMTP::DEBUG_OFF = off (for production use)
              // SMTP::DEBUG_CLIENT = client messages
              // SMTP::DEBUG_SERVER = client and server messages
              $mail->SMTPDebug = SMTP::DEBUG_SERVER;
              //Set the hostname of the mail server
              $mail->Host = 'smtp-relay.sendinblue.com';
              //Set the SMTP port number - likely to be 25, 465 or 587
              $mail->Port = 587;
              //Whether to use SMTP authentication
              $mail->SMTPAuth = true;
              //Username to use for SMTP authentication
              $mail->Username = 'cpa5794@gmail.com';
              //Password to use for SMTP authentication
              $mail->Password = 'N6BR3qFkSLhnswzW';
              //Set who the message is to be sent from
              $mail->setFrom($ema, 'Administrador Mailer');
              //Set an alternative reply-to address
              $mail->addReplyTo($ema, 'Administrador');
              //Set who the message is to be sent to
              $mail->addAddress($ema, $nom);
              //Set the subject line
              $mail->Subject = 'Password y Usuario de Generador de codigos qr';
              $mail->Body = "El usuarios es ".$usu." y el password es ".$pa1." . Para entrar es en la pagina dentro de la red de Carlos www.generarqr.com . "; // Mensaje a enviar
              //Read an HTML message body from an external file, convert referenced images to embedded,
              //convert HTML into a basic plain-text alternative body
              //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
              //Replace the plain text body with one created manually
              //$mail->AltBody = 'This is a plain-text message body';
              //Attach an image file
              //$mail->addAttachment('images/phpmailer_mini.png');

              //send the message, check for errors
              if (!$mail->send()) {
              echo 'Mailer Error: ' . $mail->ErrorInfo;
              } else {
              echo 'Message sent!';
              }
              */
              $stmt = sqlsrv_query( $conn, $sql, $params);
              if( $stmt === false ) {
                   die( print_r( sqlsrv_errors(), true));
              }
            }
          }
        catch (PDOException $ex) {
         	print "<p>Error: Imposible realizar la operación deseada</p>\n";
          print "<p>Motivo(".$ex->getCode()."): ".$ex->getMessage()."</p>\n";
        }
        sqlsrv_close( $conn );
        $datos=null;
        }
    }
}
else{
    $usu = '';
    $pa1 = '';
    $pa2 = '';
    $dni = '';
    $cdp = '';
    $ema = '';
    $nom = '';
    $ape1= '';
    $ape2= '';
}

function letra_nif($dni) {
    return substr("TRWAGMYFPDXBNJZSQVHLCKE",strtr($dni,"XYZ","012")%23,1);
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Gestión de subvenciones</title>
  <link rel="stylesheet" href="style3.css">
	<script type="text/javascript" src="public/js/view.js"></script>
    <script>

        function validarPass(){
            var ok =  (formRegistro.elements['password1'].value == formRegistro.elements['password2'].value);
            var div=document.getElementById('dErrores');
            var mensaje=document.getElementById('aMensajes');
            if (! ok)
            {
                mensaje.value="Las contraseñas no coinciden." ;
                div.style.visibility = 'visible';
            }
            else
            {
                mensaje.value="" ;
                div.style.visibility= 'hidden';
            }
            return ok;
        }

        function validarDni(d){
            var div=document.getElementById('dErrores');
            var mensaje=document.getElementById('aMensajes');
            var ok=d.value.isNif();
            if(!ok){
                mensaje.value="El valor introducido para el DNI no es correcto." ;
                div.style.visibility = 'visible';
            }
            else{
                mensaje.value="" ;
                div.style.visibility= 'hidden';
            }
            return ok;
        }

        String.prototype.isNif=function()
        {
            return /^(\d{8})([A-HJ-NP-TV-Z])$/.test(this)  && ("TRWAGMYFPDXBNJZSQVHLCKE"[(RegExp.$1%23)]==RegExp.$2);
        };

    </script>
</head>
<body>
  <nav class="skew-menu">
    <ul>
      <li><a href="configuracion_2.php">Conexion</a></li>
      <li><a href="Creación_usuario.php">Creación de usuarios</a></li>
      <li><a href="Registro_Entradas_Salidas.php">Registro de llegadas/salidas</a></li>
      <li><a href="Generar_codigos_qr.php">Generar codigo qr</a></li>
      <li><a href="Cerrar_session.php">Cerrar session</a></li>
    </ul>
  </nav>

		<form method="post" action="" id="check" name="check" class="register">
      <h1>Registro de usuarios</h1>
      <fieldset class="row1">
          <legend>Datos
          </legend>
          <p>
            <label for="nombre">Nombre *</label>
            <input id="nombre" name="nombre" type="text" minlength="3" maxlength="16" required
                                value="<?= $nom ?>"  />

        <label for="usuario">Nombre usuario *</label>
        <input id="usuario" name="usuario" type="text" minlength="6" maxlength="16" required
                            value="<?= $usu ?>"  />
      </p>

      <p>
        <label for="apellido1">Primer apellido *</label>
        <input id="apellido1" name="apellido1" type="text" minlength="3" maxlength="16" required
                      value="<?= $ape1 ?>"  />

        <label for="apellido2">Sengudo apellido *</label>
        <input id="apellido2" name="apellido2" type="text" minlength="3" maxlength="16" required
                            value="<?= $ape2 ?>"  />
      </p>
      <p>

        <label for="password1">Contraseña *</label>
        <input id="password1" name="password1" type="password" minlength="8" maxlength="16" required
                            value=""  onchange="validarPass();" />

        <label  for="password2">Confirmación contraseña *</label>
        <input id="password2" name="password2"  type="password"  minlength="8" maxlength="16" required
                            value=""   onchange="validarPass();"/>
      </p>
      <p>
        <label for="dni">DNI *</label>
        <input id="dni" name="dni"  type="text" minlength="9" maxlength="9" required
               value="<?= $dni ?>" onchange="validarDni(this)"/>
      </p>
      <p>

        <label for="description">Codigo Postal</label>
        <input id="postal" name="postal"  minlength="5" maxlength="5" value="<?= $cdp ?>"
               type="text" required pattern ="[0-5][0-9]{4}" title="El código postal está formado por cinco dígitos." />
      </p>
      <p>

        <label for="email">Email *</label>
        <input id="email" name="email" type="email" maxlength="255" required
               value="<?= $ema ?>"  />
      </p>
        <div><button class="button"  id="aceptar" type="submit" name="aceptar" value="Registrar">Aplicar &raquo;</button></div>
        <p>


        <div>
            <div class="infobox" sstyle="visibility:<?= ($mensaje=='') ? 'hidden' : 'visible' ?>" >
                <h2 for="aMensajes">AVISO</h2>
                <h3 name="aMensajes" id="aMensajes"  rows="4" readonly="readonly" > <?= $mensaje  ?></h3>
            </div>
            </p>
        </div>
        </fieldset>
		</form>
    </div>
    <h1>Tabla de usuarios</h1>
    <p class="lol">Pulsar dos veces para aplicar cambios</p>
    <table border = '1'>
      <tr>
        <th>ID</th>
        <th>ID_U</th>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Apellido1</th>
        <th>Apellido2</th>
        <th>Password</th>
        <th>Postal</th>
        <th>Email</th>
        <th>Azar</th>
        <th>DNI</th>
        <th>¿Eliminar?</th>
        <th>¿Super_usuario?</th>
      </tr>
    <?php



$datos='datos.txt';
$todos_los_datos=file($datos);
$servidor=rtrim($todos_los_datos[0]);
$base_de_datos=rtrim($todos_los_datos[1]);
$usuario=rtrim($todos_los_datos[2]);
$clave=rtrim($todos_los_datos[3]);
$serverName = "$servidor";
$connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
     echo "Could not connect.\n";
     die( print_r( sqlsrv_errors(), true));
}

$tsql = "SELECT *
         FROM usuarios
         ";
$stmt = sqlsrv_query( $conn, $tsql);
if( $stmt === false)
{
     echo "Error in query preparation/execution.\n";
     die( print_r( sqlsrv_errors(), true));
}


?>

<form method="post" action="" >


<?php
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){

echo "<tr>";
echo "<td>" . $row['id'] . "</td>";
echo "<td>" . $row['id_u'] . "</td>";
echo "<td>" . $row['usuario'] . "</td>";
echo "<td>" . $row['nombre'] . "</td>";
echo "<td>" . $row['apellido1'] . "</td>";
echo "<td>" . $row['apellido2'] . "</td>";
echo "<td>" . $row['password'] . "</td>";
echo "<td>" . $row['postal'] . "</td>";
echo "<td>" . $row['email'] . "</td>";
echo "<td>" . $row['num_azar'] . "</td>";
echo "<td>" . $row['dni'] . "</td>";
echo "<td><button class='button2'  type='submit' id='toma' name='toma' value='".$row['id_u']."'  />Eliminar </button></td>";
  $sql1 = "select admin from usuarios where id ='".$row['id']."'";
  $stmt5 = sqlsrv_query( $conn, $sql1);
  while($row1 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC)) {
    if ( $row1['admin'] == 1) {
      ?>
      <td>
        <input type="radio" name="<?php echo $row['id']; ?>" value="1" onclick="submit();" checked >Si</input>
        <input type="radio" name="<?php echo $row['id']; ?>" value="0" onclick="submit();">No</input>
      </td>

      <?php

      }
    elseif ($row['admin'] == 0) {
        ?>
        <td>
          <input type="radio" name="<?php echo $row['id']; ?>" value="1" onclick="submit();">Si</input>
          <input type="radio" name="<?php echo $row['id']; ?>" value="0" onclick="submit();" checked>No</input>
        </td>
        <?php
    }

  }
  if(isset($_POST[$row['id']])){
    $r=$_POST[$row['id']];
    if($r == '1'){
      $sql1 = "UPDATE usuarios SET admin='1' WHERE id='".$row['id']."'";
      $stmt6 = sqlsrv_query( $conn, $sql1);
    }
    elseif ($r == '0') {
      $sql1 = "UPDATE usuarios SET admin='0' WHERE id='".$row['id']."'";
      $stmt6 = sqlsrv_query( $conn, $sql1);
    }
  }


echo "</tr>";
}
echo "</table>";

sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);
if (isset($_POST['toma'])) {
  $eli=$_POST['toma'];
  try {
    $datos='datos.txt';
    $todos_los_datos=file($datos);
    $servidor=rtrim($todos_los_datos[0]);
    $base_de_datos=rtrim($todos_los_datos[1]);
    $usuario=rtrim($todos_los_datos[2]);
    $clave=rtrim($todos_los_datos[3]);
    $serverName = "$servidor";
    $connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    if( $conn === false ) {
         die( print_r( sqlsrv_errors(), true));
    }

    $sql = "DELETE FROM usuarios WHERE id_u='$eli' ";
    $sql1 = "DBCC CHECKIDENT (usuarios, RESEED, 0)";
    $stmt = sqlsrv_query( $conn, $sql, $params);
    $stmt1= sqlsrv_query( $conn, $sql1, $params);
    if( $stmt === false && $stmt1 === false ) {
         die( print_r( sqlsrv_errors(), true));
    }
    sqlsrv_close( $conn );
  }
  catch (PDOException $ex) {
    print "<p>Error: Imposible realizar la operación deseada</p>\n";
      print "<p>Motivo(".$ex->getCode()."): ".$ex->getMessage()."</p>\n";
  }
}

?>

</form>

</body>
</html>
