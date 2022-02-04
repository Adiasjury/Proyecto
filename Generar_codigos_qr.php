
<?php
session_start();
  if (isset($_SESSION['usu']) && isset($_SESSION['pass'])) {
$usus=$_SESSION['usu'];
$pass=$_SESSION['pass'];
$datos='datos.txt';
$todos_los_datos=file($datos);
$servidor=rtrim($todos_los_datos[0]);
$base_de_datos=rtrim($todos_los_datos[1]);
$usuario=rtrim($todos_los_datos[2]);
$clave=rtrim($todos_los_datos[3]);
$serverName = "$servidor";
$connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$sql1 = "select * from usuarios where usuario ='".$usus."' and password ='".$pass."'";
$stmt1 = sqlsrv_query( $conn, $sql1);
$row = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC);

if ( $row['usuario'] != '' ){
sqlsrv_close( $conn );
  $date=date('Y-m-d');
  $hora=date('H:i:s');
    require 'phpqrcode/qrlib.php';
    $dir ='temp/';
    $datos='datos.txt';
    $todos_los_datos=file($datos);
    $servidor=rtrim($todos_los_datos[0]);
    $base_de_datos=rtrim($todos_los_datos[1]);
    $usuario=rtrim($todos_los_datos[2]);
    $clave=rtrim($todos_los_datos[3]);
    $serverName = "$servidor";
    $connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $sql1 = "select num_azar from usuarios where usuario='$usu'";
    $sql2 = "select id_u from usuarios where usuario='$usu'";
    $sql3 = "select LOWER(SUBSTRING(nombre, 1, 1)) as a from usuarios where usuario='$usu'";
    $sql4 = "select LOWER(SUBSTRING(apellido1, 1, 1)) as b from usuarios where usuario='$usu'";
    $sql5 = "select LOWER(SUBSTRING(apellido2, 1, 1)) as c from usuarios where usuario='$usu'";

    $stmt1 = sqlsrv_query( $conn, $sql1);
    $stmt2 = sqlsrv_query( $conn, $sql2);
    $stmt3 = sqlsrv_query( $conn, $sql3);
    $stmt4 = sqlsrv_query( $conn, $sql4);
    $stmt5 = sqlsrv_query( $conn, $sql5);
    while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
  $azar= $row['num_azar']; echo "</br>";
    }
    while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
  $id=$row['id_u'];
    }
    while($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
    $nom=$row['a']; echo "</br>";
    }
    while($row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)) {
  $ape1=$row['b']; echo "</br>";
    }
    while($row = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC)) {
  $ape2= $row['c'];
    }
sqlsrv_close( $conn );
$contenido= $nom.$ape1.$ape2."_".$id."_".$date."_".$hora."_".$azar;
    if (!file_exists($dir)) {
      mkdir($dir);
      $file_name= $dir.'test.png';
      $tamanio=10;
      $level = 'M';
      $frameSize=3;
      QRCode::png($contenido,$file_name,$level,$tamanio,$frameSize);
    }
    else{
      $file_name= $dir.'test.png';
      $tamanio=10;
      $level = 'M';
      $frameSize=3;
      QRCode::png($contenido,$file_name,$level,$tamanio,$frameSize);
    }

 ?>
 <html>
    <head>
      <link rel="stylesheet" href="style5.css">
    </head>
 <body>
   <div class="menu">
   <nav class="skew-menu">
     <ul>
       <li><a href="configuracion_2.php">Conexion</a></li>
       <li><a href="Creación_usuario.php">Creación de usuarios</a></li>
       <li><a href="Registro_Entradas_Salidas.php">Registro de llegadas/salidas</a></li>
       <li><a href="Generar_codigos_qr.php">Generar codigo qr</a></li>
       <li><a href="Cerrar_session.php">Cerrar session</a></li>
     </ul>
   </nav>
   </div>
   <?php
      if (isset($_POST['toma']) && isset($_SESSION['usu'])) {
        $_SESSION['usu']=$_POST['toma'];
        ?>
        <div class="imagen">
            <img src="temp/test.png"/>
        </div>
        <?php
      }
    ?>
   <div class="enviar">
       <form method="post" action="">
          <button class='submit' type='submit' name="toma" value="<?php  echo  $_SESSION['usu'];  ?>">Generar Codigo QR</button>
       </form>
   </div>
 </body>
 </html>
<?php }
else{
  header('Location: Inicio.php');
}
}
else{
  header('Location: Inicio.php');
}?>
