<?php
session_start();

include('conexao2.php');

// Configurar o fuso horário para o Brasil
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['email']) && isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $conexao->real_escape_string($_POST['email']);
        $senha = $conexao->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $conexao->query($sql_code) or die("Falha na execução do código SQL: " . $conexao->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            // Armazenar informações na tabela atividades
            $id_usuario = $usuario['id'];
            $data_hora = date('Y-m-d H:i:s'); // Data e hora atual no fuso horário configurado para o Brasil
            $sql_insert = "INSERT INTO atividades (id_usuario, data_hora) VALUES ('$id_usuario', '$data_hora')";
            $conexao->query($sql_insert) or die("Erro ao inserir dados na tabela: " . $conexao->error);

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: painel.php");
            exit();

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }

    }

}
?>
