<html>
  <head>
    <meta charset="utf-8" http-equiv="refresh" content="30"/>
  </head>
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

if( $conn ) {
     echo "<p> Conexión establecida.</p>";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
echo $_POST['nombre'];
?>
</html>
