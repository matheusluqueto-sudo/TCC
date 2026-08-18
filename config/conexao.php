<?php
function conectar(){
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $bd = "biblios";

    // 1ª tentativa: 3306
    $con = @mysqli_connect($host, $user, $password, $bd);

    if (!$con) {
        // 2ª tentativa: 3307
        $con = @mysqli_connect($host, $user, $password, $bd, 3307);
    }

    if (!$con) {
        die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
    }

    return $con;
}
?>
