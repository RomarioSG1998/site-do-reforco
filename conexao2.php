<?php
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";

    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Define o conjunto de caracteres para utf8
     // $conexao->set_charset("utf8");
     $conexao->set_charset("utf8mb4");


    if($conexao->error) {
        die("Falha ao conectar ao banco de dados: " . $mysqli->error);
    }
?>

