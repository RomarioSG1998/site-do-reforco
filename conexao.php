<?php
// Configurações do banco de dados
$host = "localhost"; // Host do banco de dados (geralmente localhost)
$usuario = "root"; // Nome de usuário do banco de dados
$senha = ""; // Senha do banco de dados
$banco = "sistemadoreforco"; // Nome do banco de dados

// Conexão com o banco de dados
$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $nome = $_POST["firstname"];
    $dataNascimento = $_POST["dataNascimento"];
    $celular = $_POST["number"];
    $responsavel = $_POST["responsavelAluno"];
    $genero = $_POST["genero"];
    $turma = $_POST["turma"];

    // Insere os dados no banco de dados
    $sql = "INSERT INTO alunos (nome, datanasc, celular, responsavel, genero, turma) VALUES ('$nome', '$dataNascimento', '$celular', '$responsavel', '$genero', '$turma')";

    if ($conexao->query($sql) === TRUE) {
        echo "Registro inserido com sucesso!";
    } else {
        echo "Erro ao inserir registro: " . $conexao->error;
    }

    // Fecha a conexão
    $conexao->close();
}
?>
