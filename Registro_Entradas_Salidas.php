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
    <title>Registro</title>
    <link rel="stylesheet" href="style6.css">
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
    <div class="inicio">
      <h1>Registro de Entradas y Salidas </h1>
    </div>
    <div class='form1'>
    <form action="" method="post">
      <button class='submit' type='submit' name="toma">Generar PDF</button>
      <p>.</p>
      <p>ID_U<input class="nom" type='text' name='nombre' max="5" pattern="[i][d][0-9]{3}"/>
         Fecha_entrada<input class="nom" type='date' name='entrada' />
         Fecha_salida<input class="nom" type='date' name='salida' />
      <input class="submit1" type='submit' name='nom_en' />
      </p>
    </div>
    </form>
    <div class="tabla">
    <?php
    $array_tiempo=array();
    echo '<table border = "1">';
    $html.='<table border = "1">';
    echo  '<tr>';
    $html.='<tr>';
    echo    '<th>Nombre</th>';
    $html.='<th>Nombre</th>';
    echo    '<th>Apellido1</th>';
    $html.='<th>Apellido1</th>';
    echo    '<th>Apellido2</th>';
    $html.='<th>Apellido2</th>';
    echo    '<th>ID_U</th>';
    $html.=  '<th>ID_U</th>';
    echo    '<th>Fecha</th>';
    $html.=  '<th>Fecha</th>';
    echo    '<th>Hora entrada</th>';
    $html.=  '<th>Hora entrada</th>';
    echo   ' <th>Hora salida</th>';
    $html.=  '<th>Hora salida</th>';
    echo   ' <th>Tiempo de trabajo</th>';
    $html.=  '<th>Tiempo de trabajo</th>';
    echo  '</tr>';
    $html.=  '</tr>';
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



    if( $stmt === false)
    {
     echo "Error in query preparation/execution.\n";
     die( print_r( sqlsrv_errors(), true));
    }


    ?>

    <form method="post" action="" >


    <?php
    if ((isset($_POST['nom_en']) || isset($_POST['toma']) ) && $_POST['nombre'] == 'id000') {
      $sql5="select *, DATEDIFF(minute,hora.hora_entrada,hora_salida) as tiempo from hora,usuarios where usuarios.id = hora.id_u order by fecha,contador";
    }
    elseif(isset($_POST['nom_en']) && $_POST['nombre'] != '' && isset($_POST['entrada']) && isset($_POST['salida']) ){
      $sql5="select *, DATEDIFF(minute,hora.hora_entrada,hora_salida) as tiempo from hora,usuarios where usuarios.id = hora.id_u and usuarios.id_u='".$_POST['nombre']."' and hora.fecha >= '".$_POST['entrada']."' and hora.fecha <= '".$_POST['salida']."'  order by fecha,contador";
      unset($_POST['nom_en']);
    }
    elseif(isset($_POST['nom_en']) || ( isset($_POST['nombre'])  && isset($_POST['entrada'])  && isset($_POST['salida']) ) ){
      $sql5="select *, DATEDIFF(minute,hora.hora_entrada,hora_salida) as tiempo from hora,usuarios where usuarios.id = hora.id_u and usuarios.id_u='".$_POST['nombre']."' and hora.fecha >= '".$_POST['entrada']."' and hora.fecha <= '".$_POST['salida']."'  order by fecha,contador";
    }
    else
    {
    $sql5="select *, DATEDIFF(minute,hora.hora_entrada,hora_salida) as tiempo from hora,usuarios where usuarios.id = hora.id_u order by fecha,contador";
    }
    $stmt5 = sqlsrv_query( $conn, $sql5);

    while($row = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC)){
      echo "<tr>";
      $html.='<tr>';
      echo "<td>".$row['nombre']."</td>";
      $html.="<td>".$row['nombre']."</td>";
      echo "<td>".$row['apellido1']."</td>";
      $html.="<td>".$row['apellido1']."</td>";
      echo "<td>".$row['apellido2']."</td>";
      $html.="<td>".$row['apellido2']."</td>";
      echo "<td>".$row['id_u']."</td>";
      $html.="<td>".$row['id_u']."</td>";
      $comp4=$row['fecha'];
      if ($comp4 != null){
      $hora4= $comp4->format('Y-m-d');
      echo "<td>".$hora4."</td>";
      $html.="<td>".$hora4."</td>";
      }
      else{
        echo "<td>Null</td>";
        $html.="<td>Null</td>";
      }

    $comp2=$row['hora_entrada'];
    if ($comp2 != null){
    $hora2= $comp2->format('H:i:s');

    echo "<td>".$hora2."</td>";
    $html.="<td>".$hora2."</td>";
    }
    else{
      echo "<td><button class='button2'  type='submit' id='nulo1' name='nulo1' value='".$row['id'].",".$hora4.",".$row['contador']."'  />NULL </button></td>";
      $html.="<td>Null</td>";
    }

    $comp3=$row['hora_salida'];
    if ($comp3 != null){
    $hora3= $comp3->format('H:i:s');

    echo "<td>".$hora3."</td>";
    $html.="<td>".$hora3."</td>";
    }
    else{

      echo "<td><button class='button2'  type='submit' id='nulo' name='nulo' value='".$row['id'].",".$hora4.",".$row['contador'].",".$hora2."'  />NULL </button></td>";
      $html.="<td>Null</td>";
    }
    if($row['tiempo'] != null){
    echo "<td>".$row['tiempo']." min</td>";
    $html.="<td>".$row['tiempo']." min</td>";
    array_push($array_tiempo,$row['tiempo']);
    }
    else{
      echo "<td> 0 min</td>";
      $html.="<td> 0 min</td>";
    }

    echo "</tr>";
    $html.="</tr>";
    }
    echo '</table>';
    $html.="</table>";
    echo '</div>';
    echo '<div class="tiempo1">';
    echo "<p class='total'> Tiempo en el puesto de trabajo ".array_sum($array_tiempo)." min</p>";
    $html.="<p> Tiempo en el puesto de trabajo ".array_sum($array_tiempo)." min</p>";
    echo '</div>';
    sqlsrv_close( $conn );
    if(isset($_POST['nulo1'])){
      echo "<div class='tiempo2'>";
      $r = $_POST['nulo1'];
      $result = explode(",", $r);

      echo "<form method='post' action=''>";
            echo "<p>Añade la HORA DE ENTRADA <input type='time' class='anade' name='cam_sal'/><button class='button3'  type='submit' id='aplicar1' name='aplicar1' value='".$result[0].",".$result[1].",".$result[2].",".$result[3]."'  />Aplica </button></p>";
      echo "</form>";
    }
    if(isset($_POST['nulo'])){
      echo "<div class='tiempo2'>";
      $r=$_POST['nulo'];
      $result = explode(",", $r);
      echo "<form method='post' action=''>";
            echo "<p>Añade la HORA DE SALIDA <input type='time' class='anade' name='cam_sal'/><button class='button3'  type='submit' id='aplicar' name='aplicar' value='".$result[0].",".$result[1].",".$result[2].",".$result[3]."'  />Aplica </button></p>";
      echo "</form>";

    }
    if(isset($_POST['aplicar'])){
      $hora_salida=$_POST['cam_sal'];
      $result1=explode(",", $_POST['aplicar']);
      $hora_entrada1=strtotime($result1[3]);
      $hora_salida1=strtotime($hora_salida);
      if ($hora_salida1 > $hora_entrada1){
      $sql4="UPDATE hora SET hora_salida = '".$hora_salida.":00' WHERE id_u='$result1[0]' and fecha = '$result1[1]' and contador='$result1[2]' ;";
      $stmt4 = sqlsrv_query( $conn, $sql4);
      }
      else{
        echo "<div class='tiempo2'>";
        echo "<p>Cuidado la hora de salida no puede ser menor que la hora de entrada</p>";
      }
echo '</div>';
    }
    if(isset($_POST['aplicar1'])){
      $hora_entrada=$_POST['cam_sal'];
      $result1=explode(",", $_POST['aplicar1']);
      $hora_entrada1=strtotime($result[3]);
      $hora_salida1=strtotime($hora_salida);

      $sql4="UPDATE hora SET hora_entrada = '".$hora_entrada.":00' WHERE id_u='$result1[0]' and fecha = '$result1[1]' and contador='$result1[2]' ;";
      $stmt4 = sqlsrv_query( $conn, $sql4);


    }
    unset($_POST['nom_en']);
    if(isset($_POST['toma']) ){
      if (file_exists('Documento.txt')) {
        $arch = fopen ("Documento.txt", "w+");
        fwrite($arch, "");
        fclose($arch);
        $arch1 = fopen ("Documento.txt", "w+");
        fwrite($arch1, "<h1>Documentación de entradas y salidas</h1>".$html);
        fclose($arch1);
      } else {
        file_put_contents('Documento.txt', $html);
      }
      header('Location: phpinfo.php');

    }
    ?>
    </div>
  </body>
</html>
<?php
}
else{
  echo $usus;
  echo $pass;
}
}
else {
  header('Location: Inicio.php');
}
?>
