<?php
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "id21964020_root";
$senha = "J3anlak#1274";

    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);


    if($conexao->error) {
        die("Falha ao conectar ao banco de dados: " . $mysqli->error);
    }
?>