<?php
if (isset($_POST)){
    session_start();
    $jsser = serialize($_POST);
    $p=substr($jsser,31,33);
    echo $p;
    $_SESSION['nombre']=$p;

}
?>
