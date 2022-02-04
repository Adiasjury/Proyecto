<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <?php
        $suspenso=0;
        $aprobado=0;
        $notas=[4,8,7,2,9,7];
        //$notas=array(4,8,7,2,9,7);
        for ($i=0;$i<count($notas);$i++){
            if( $notas[$i] < 5 ) {
                echo $notas[$i]." Suspenso </br>";
                $suspenso++;
            }
            else {
                echo $notas[$i]." Aprobado </br>";
                $aprobado++;
            }
        }
        echo "He aprobado ".$aprobado." ,he suspendido ".$suspenso
    ?>
</body>
</html>