<?php
$hostname = "localhost";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cria a conexão com o banco de dados
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verifica a conexão
    if ($conexao->connect_errno) {
        echo "Falha ao conectar: (" . $conexao->connect_errno . ")" . $conexao->connect_errno;
    } else {
        // Recupera os dados do formulário
        $nome = $_POST["firstname"];
        $dataNascimento = $_POST["dataNascimento"];
        $celular = $_POST["number"];
        $responsavel = $_POST["responsavelAluno"];
        $genero = $_POST["genero"];
        $turma = $_POST["turma"];

        // Insere os dados no banco de dados usando prepared statements
        $stmt = $conexao->prepare("INSERT INTO alunos (nome, datanasc, celular, responsavel, genero, turma) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $dataNascimento, $celular, $responsavel, $genero, $turma);

        // Executa o statement
        if ($stmt->execute()) {
            // Exibe uma mensagem de sucesso
            echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        } else {
            // Exibe uma mensagem de erro
            echo "<script>alert('Erro ao cadastrar!');</script>";
        }

        // Fecha o statement
        $stmt->close();
    }

    // Fecha a conexão
    $conexao->close();
}
?>
