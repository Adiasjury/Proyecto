<?php
$usu = 'cpa5794';
$pa1 = 'Hola';
$pa2 ='Hola';
$dni = '26258578F';
$cdp = '23700';
$ema = 'cpa5794@gmail.com';
$nom = 'Carlos';
$ape1= 'Palomo';
$ape2= 'Anguita';
$numa=mt_Rand(100, 999);
echo $usu;

$serverName = "localhost";
$connectionInfo = array( "Database"=>"Empresa", "UID"=>"sa", "PWD"=>"Contraseña" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$sql = "insert into usuarios (usuario,nombre,apellido1,apellido2,password,postal,email,num_azar) values (?,?,?,?,?,?,?,?)";
$params = array($usu,$nom,$ape1,$ape2,$pa1,$cdp,$ema,$numa);

$stmt = sqlsrv_query( $conn, $sql, $params);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}
 ?>
 $serverName = "localhost";
 $connectionInfo = array( "Database"=>"Empresa", "UID"=>"sa", "PWD"=>"Contraseña" );
 $conn = sqlsrv_connect( $serverName, $connectionInfo);
 if (sqlsrv_connect_errno())
 {
 echo "Failed to connect to SQlserver: " . sqlsrv_connect_error();
 }
 else{
 $result = sqlsrv_query($con,"SELECT * FROM usuarios");



 while($row = sqlsrv_fetch_array($result))
 {
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
 echo "</tr>";
 }
 echo "</table>";

 sqlsrv_close($con);
}
