<?php

$serverName = "localhost";
$connectionInfo = array( "Database"=>"Empresa", "UID"=>"sa", "PWD"=>"Contaseña");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$sql1 = "select * from usuarios where usuario ='".$usus."' and password ='".$pass."'";
$stmt1 = sqlsrv_query( $conn, $sql1);
$row0 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC);
