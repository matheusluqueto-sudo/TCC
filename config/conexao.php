<?php

function conectar()
{
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $bd = "tcc";

    // 1ª tentativa: porta 3306
    $con = @mysqli_connect($host, $user, $password, $bd, 3306);

    // 2ª tentativa: porta 3307
    if (!$con) {
        $con = @mysqli_connect($host, $user, $password, $bd, 3307);
    }

    if (!$con) {
        die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
    }

    mysqli_set_charset($con, "utf8mb4");

    return $con;
}
?>