<?php
$hostname = "localhost";
$bancodedados = "sistemadoreforco";
$usuario = "root";
$senha = "";

    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);


    if($conexao->error) {
        die("Falha ao conectar ao banco de dados: " . $mysqli->error);
    }
?>