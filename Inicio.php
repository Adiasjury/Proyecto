<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Generador de codigos QR</title>
  <!-- CORE CSS-->

  <link rel="stylesheet" href="style4.css">

<style type="text/css">
html,
body {
    height: 100%;
}
html {
    display: table;
    margin: auto;
}
body {
    display: table-cell;
    vertical-align: middle;
}
.margin {
  margin: 0 !important;
}
</style>

</head>

<body class="red">


  <div id="login-page" class="row">
    <div class="col s12 z-depth-6 card-panel">
      <form class="login-form" action="" method="post">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="tu.jpg" alt="" width="200" height="100" class="responsive-img valign profile-image-login">
            <p class="center login-form-text">Generador de codigos QR</p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <p>Usuario</p>
            <input class="validate" id="usuario" name="usuario" type="text" value="<?php if(isset($_COOKIE['usuario'])){ echo $_COOKIE['usuario'];  }else { echo ''; } ?>">
            <label for="email" data-error="wrong" data-success="right" class="center-align"></label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <p>Contraseña</p>
            <input id="password" name="password" type="password" value="<?php  if(isset($_COOKIE['password'])){ echo $_COOKIE['password'];  }else { echo ''; }  ?>">
            <label for="password"></label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" name='recuerdame' id="remember-me" <?php if(isset($_POST['recuerdame'])){ echo checked; } ?> onclick="submit();"/>
              <label for="remember-me">Recordar</label>
          </div>
        </div>
        <div class="row">
          <div class="Inicio">
            <input type="submit" name='inicio' value="Iniciar sesión" class="button"/>
          </div>
        </div>
      </form>
    </div>
  </div>


  <center>
  <?php
  if (isset($_POST['inicio'])) {
    if (isset($_POST['usuario']) && isset($_POST['password']) ) {
      $usu=$_POST['usuario'];
      $pass=$_POST['password'];
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
      $sql2 = "select usuario from usuarios where password='$pass'";

      $stmt1 = sqlsrv_query( $conn, $sql1);
      $stmt2 = sqlsrv_query( $conn, $sql2);
      $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
      $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
      if ( $row1['usuario'] != null && $row2['usuario'] != null  ){
          session_start();
          $_SESSION['usu']=$usu;
          $_SESSION['pass']=$pass;
          header('Location: Generar_codigos_qr.php');
      }
      else{
        echo '<p class="hola">Algo has puesto mal</p>';
      }
      sqlsrv_close( $conn );
    }
  }
  if (isset($_POST['recuerdame'])){
    setcookie('usuario',$_POST['usuario']);
    setcookie('password',$_POST['password']);
  }
  ?>

<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-5104998679826243"
     data-ad-slot="3470684978"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</center>
</body>

</html>
