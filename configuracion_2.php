<!DOCTYPE html>
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
?>
<html>
  <head>
    <title>Instascan</title>
    <script type="text/javascript" src="https://rawcdn.githack.com/tobiasmuehl/instascan/4224451c49a701c04de7d0de5ef356dc1f701a93/bin/instascan.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <link rel="stylesheet" href="style.css">
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
    <div class="h2">
      <h2>Pon delante de la camara tu codigo QR</h2>
    </div>
    <div class="video">


    <video id="preview"></video>
    <script type="text/javascript">

    function envia_datos(datos) {
      var obj = {
          val1: datos,
      };
      $.ajax({
          type: "POST",
          url: "service.php",
          data: {
               json: JSON.stringify(obj)
          },
          success: function (response) {
              console.log(response);
              setTimeout(function(response){// wait for 5 secs(2)
               location.reload(); // then reload the page.(3)
          }, 500);
          }

      });
    }

      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        console.log(content);//aqui los datos
        envia_datos(content)
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $(".Respuesta").fadeOut(1500);
        },6000);
    });
    </script>
    </div>
    <?php
    if(isset($_SESSION['nombre'])){


    $frase =$_SESSION['nombre'];
    $date = date("Y-m-d");
    $horas=date("H");
    $minutos=date("i");
    $segundo=date("s");
    $segundos = ($horas * 3600 ) + ($minutos * 60 ) + $segundo;
    	if(preg_match("/[a-z]{3}_[id]{2}[0-9]{3}_[0-9]{4}-[0-9]{2}-[0-9]{2}_([0-9]+(:[0-9]+)+)_[0-9]{3}/i", $frase))
    	{
       $usuario = substr($frase,0,3);
    	 $id_u = substr($frase,4,5) ;
       $fecha_de_hoy = substr($frase,10,10);
       $hora = substr($frase,21,8);
    	 $numero_azar = substr($frase,-3);
       $hora0=substr($hora,0,2);
    	 $minuto0=substr($hora,3,2);
    	 $segundo0=substr($hora,6,2);
    	 $segundos1 = ($hora0 * 3600 ) + ($minuto0 * 60 ) + $segundo0;
    	 $prueba=$segundos-300;
    	 if ($fecha_de_hoy == "$date" && $segundos1 < $segundos && $prueba < $segundos1) {
         $datos='datos.txt';
         $todos_los_datos=file($datos);
         $servidor=rtrim($todos_los_datos[0]);
         $base_de_datos=rtrim($todos_los_datos[1]);
         $usuario=rtrim($todos_los_datos[2]);
         $clave=rtrim($todos_los_datos[3]);
         $serverName = "$servidor";
         $connectionInfo = array( "Database"=>"$base_de_datos", "UID"=>"$usuario", "PWD"=>"$clave");
     		$conn = sqlsrv_connect( $serverName, $connectionInfo);
        $sql31= "select nombre, apellido1 , apellido2 from  usuarios where id_u='$id_u'";
        $stmt31 = sqlsrv_query( $conn, $sql31);
        $row67 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
        $sql1 = "select id from usuarios where id_u='$id_u' and num_azar='$numero_azar'";
        $stmt1 = sqlsrv_query( $conn, $sql1);
        $row0 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC);
        $id=$row0['id'];
        if($id != null){
          ?>
                       <audio id="myvideo" autoplay>
                       <source src="sonido.mp3" />
                       </audio>

                       <script>
                       var audio = document.getElementById("myvideo");
                       function toggleControls() {
                       if (audio.hasAttribute("controls")) {
                          audio.removeAttribute("controls")
                       } else {
                          audio.setAttribute("controls","controls")
                       }
                       }

                       </script>
            <?php
            $sql_con="select count(*) from hora where id_u='$id'";
            $stmt_con = sqlsrv_query( $conn, $sql_con);
            $contador = sqlsrv_fetch_array( $stmt_con, SQLSRV_FETCH_ASSOC);
            $i=0;
            while ($i <= $contador){
              $sql2="select id_u from hora where fecha ='$fecha_de_hoy' and id_u='$id' and contador='$i'";
              $stmt2 = sqlsrv_query( $conn, $sql2);
              $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
              $comp=$row1['id_u'];
            if($comp != null  ){
              $sql4="select * from hora where id_u='$id' and fecha = '$fecha_de_hoy' and contador='$i'";
              $stmt4 = sqlsrv_query( $conn, $sql4);
              $row2 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
              $comp1=$row2['hora_salida'];
              $sql5="select hora_entrada from hora where id_u='$id' and fecha = '$fecha_de_hoy' and contador='$i'";
              $stmt5 = sqlsrv_query( $conn, $sql5);
              $row = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
              $comp2=$row['hora_entrada'];
             $hora2= number_format($comp2->format('H'));
             $minuto2=number_format($comp2->format('i'));
             $segundo2=number_format($comp2->format('s'));
             $segundos_comp=($hora2 * 3600 ) + ($minuto2 * 60 ) + $segundo2;
            if($comp1 == null && ($segundos1 - $segundos_comp ) > 60 ){
              $sql6="UPDATE hora SET hora_salida = '$hora' WHERE id_u='$id' and fecha='$fecha_de_hoy' and contador ='$i' ";
              $stmt6 = sqlsrv_query( $conn, $sql6);

              echo "<p class='Respuesta'>Registrada Salida</p>";
              echo "<p class='Respuesta'>".$row67['nombre']." ".$row67['apellido1']." ".$row67['apellido2']." que pases una Buena Tarde</p>";
                 break;
              }
              if (($segundos1 - $segundos_comp) <= 60){
                echo "<p class='Respuesta'>Espera para insertar los nuevos datos y no olvide generar nuevo codigo</p>";
                break;
              }
            }
            else{
              if($i == 0){
              $sql3 = "INSERT INTO hora (id_u, fecha, hora_entrada, contador) values (? , ?, ?, ?);";
              $params = array($id, $fecha_de_hoy, $hora, $i);
              $stmt3 = sqlsrv_query( $conn, $sql3, $params);

              $num_azar1=random_int(100, 999);
              $sql31 = "UPDATE usuarios SET num_azar = '".$num_azar1."' WHERE id='".$id."' ";
              $stmt31 = sqlsrv_query( $conn, $sql31);
              echo "<p class='Respuesta'>Registrada Entrada  </p>";
              echo "<p class='Respuesta'>".$row67['nombre']." ".$row67['apellido1']." ".$row67['apellido2']." que pases un buen dia</p>";
              if( $stmt3 === false ) {
                die( print_r( sqlsrv_errors(), true));
              }
              }
              if($i > 0 ){
                $o=$i-1;
                $sql5="select * from hora where id_u='$id' and fecha = '$fecha_de_hoy' and contador='$o'";
                $stmt5 = sqlsrv_query( $conn, $sql5);
                $row = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
                $comp2=$row['hora_salida'];
               $hora2= number_format($comp2->format('H'));
               $minuto2=number_format($comp2->format('i'));
               $segundo2=number_format($comp2->format('s'));
               $segundos_comp=($hora2 * 3600 ) + ($minuto2 * 60 ) + $segundo2;
               if(($segundos1 - $segundos_comp ) > 60 ){
                 $sql3 = "INSERT INTO hora (id_u, fecha, hora_entrada, contador) values (? , ?, ?, ?);";
                 $params = array($id, $fecha_de_hoy, $hora, $i);
                 $stmt3 = sqlsrv_query( $conn, $sql3, $params);
                 echo "<p class='Respuesta'>Registrada Entrada  </p>";
                 echo "<p class='Respuesta'>".$row67['nombre']." ".$row67['apellido1']." ".$row67['apellido2']." que pases un buen dia</p>";
                 if( $stmt3 === false ) {
                   die( print_r( sqlsrv_errors(), true));
                 }
                    break;
                 }
                 else{
                   echo "<p class='Respuesta'>Espera para insertar los nuevos datos y no olvide generar nuevo codigo</p>";
                   break;
                 }
              }
            break;
            }
            $i++;
            }
        }
        else{
          incorrecto();
          ?>

         <p class='Error'>Error, Genere de nuevo su codigo QR</p>
         <?php
        }
        sqlsrv_close( $conn );
      }
    	 else {
         incorrecto();
         ?>
         <p class='Error'>Error, Genere de nuevo su codigo QR</p>
    		 <?php
    	 }

    }
    else{
      incorrecto();
      ?>
      <p class='Error'>Error, Genere de nuevo su codigo QR</p>
      <?php
    }
    unset ($_SESSION['nombre']);
    }

}
else{
  header('Location: Inicio.php');
}
}

else{
  header('Location: Inicio.php');
}

function incorrecto(){
  ?>
  <audio id="myvideo" autoplay>
  <source src="incorrecto.mp3" />
  </audio>

  <script>
  var audio = document.getElementById("myvideo");
   function toggleControls() {
  if (audio.hasAttribute("controls")) {
     audio.removeAttribute("controls")
  } else {
     audio.setAttribute("controls","controls")
  }
  }

  </script>
  <?php
}
    ?>
  </body>
</html>
