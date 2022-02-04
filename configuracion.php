<!DOCTYPE html>
<?php
session_start();

if (isset($_SESSION['usu']) ){
?>
<html>
  <head>
    <title>Instascan</title>
    <script type="text/javascript" src="https://rawcdn.githack.com/tobiasmuehl/instascan/4224451c49a701c04de7d0de5ef356dc1f701a93/bin/instascan.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <nav class="skew-menu">
      <ul>
        <li><a href="configuracion.php">Conexion</a></li>
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
    </div>
    <?php
    if(isset($_SESSION['nombre'])){
    //$frase = "cpa_id000_2021-10-12_11:58:10_999";

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
    		$serverName = "localhost";
     		$connectionInfo = array( "Database"=>"Empresa", "UID"=>"sa", "PWD"=>"Omolap(89)0");
     		$conn = sqlsrv_connect( $serverName, $connectionInfo);
        $sql31= "select nombre, apellido1 , apellido2 from  usuarios where id_u='$id_u'";
        $stmt31 = sqlsrv_query( $conn, $sql31);
        $row2 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
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

            $sql2="select id_u from hora where fecha ='$fecha_de_hoy' and id_u='$id'";
            $stmt2 = sqlsrv_query( $conn, $sql2);
                $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
                $comp=$row1['id_u'];
            if ($comp != null ) {
                $sql4="select hora_salida from hora where id_u='$id' and fecha = '$fecha_de_hoy'";
                $stmt4 = sqlsrv_query( $conn, $sql4);
                $row2 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
                $comp1=$row2['hora_salida'];
                $sql5="select hora_entrada from hora where id_u='$id' and fecha = '$fecha_de_hoy'";
                $stmt5 = sqlsrv_query( $conn, $sql5);
                $row = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
                $comp2=$row['hora_entrada'];
               $hora2= number_format($comp2->format('H'));
               $minuto2=number_format($comp2->format('i'));
               $segundo2=number_format($comp2->format('s'));
               $segundos_comp=($hora2 * 3600 ) + ($minuto2 * 60 ) + $segundo2;
               if ($comp1 == null && ($segundos - $segundos_comp) > 60 ) {
                  $sql6="UPDATE hora SET hora_salida = '$hora' WHERE id_u='$id' and fecha='$fecha_de_hoy' ";
                  $stmt6 = sqlsrv_query( $conn, $sql6);

                  echo "<p class='Respuesta'>Registrada Salida</p>";
                  echo "<p class='Respuesta'>".$row2['nombre']." ".$row2['apellido1']." ".$row2['apellido2']." que pases una Buena Tarde</p>";
                }
                else{
                  echo "<p class='Respuesta'>Datos introducidos, espera para insertar los siguientes</p>";
                }
            }
            else{
                $sql3 = "INSERT INTO hora (id_u, fecha, hora_entrada) values (? , ?, ?);";
                $params = array($id, $fecha_de_hoy, $hora);
                $stmt3 = sqlsrv_query( $conn, $sql3, $params);
                echo "<p class='Respuesta'>Registrada Entrada  </p>";
                echo "<p class='Respuesta'>".$row2['nombre']." ".$row2['apellido1']." ".$row2['apellido2']." que pases un buen dia</p>";
                if( $stmt3 === false ) {
                  die( print_r( sqlsrv_errors(), true));
                }
            }
        }
        else{
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
         <p class='Error'>Error, Genere de nuevo su codigo QR</p>
         <?php
        }
      }
    	 else {
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
         <p class='Error'>Error, Genere de nuevo su codigo QR</p>
    		 <?php
    	 }

    }
    else{
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
      <p class='Error'>Error, Genere de nuevo su codigo QR</p>
      <?php
    }
    unset ($_SESSION['nombre']);
    }
}
else{
  header('Location: Inicio.php');
}
    ?>
  </body>
</html>
