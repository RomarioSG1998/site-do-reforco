<?php
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";

    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    $conexao->set_charset("utf8");

    if($conexao->error) {
        die("Falha ao conectar ao banco de dados: " . $mysqli->error);
    }
?>
