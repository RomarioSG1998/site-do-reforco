<?php
$hostname = "sql209.infinityfree.com";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "A7E5zgIppyr";

    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);


    if($conexao->error) {
        die("Falha ao conectar ao banco de dados: " . $mysqli->error);
    }
?>