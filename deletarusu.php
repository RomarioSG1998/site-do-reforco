<?php

include('conexao2.php');
include('admin.php');
include('protect.php');

// Verificar se o ID do usuário foi fornecido na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID do usuário não fornecido.";
    exit;
}

// Conexão com o banco de dados
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verificar se a conexão foi estabelecida corretamente
if ($conexao->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirmar'])) {
        // Se o usuário confirmar a exclusão, deletar o usuário do banco de dados
        $id = $_GET['id'];
        
        // Excluir os registros relacionados na tabela "atividades"
        $sql_delete_atividades = "DELETE FROM atividades WHERE id_usuario=?";
        $stmt_atividades = $conexao->prepare($sql_delete_atividades);
        $stmt_atividades->bind_param("i", $id);
        if (!$stmt_atividades->execute()) {
            echo "Erro ao deletar atividades relacionadas: " . $conexao->error;
            exit;
        }
        
        // Excluir o usuário da tabela "usuarios"
        $sql_delete = "DELETE FROM usuarios WHERE id=?";
        $stmt = $conexao->prepare($sql_delete);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Usuário deletado com sucesso!";
            echo "<script>window.location.href = 'cadusuario.php';</script>";
            exit;
        } else {
            echo "Erro ao deletar usuário: " . $conexao->error;
        }
    } elseif (isset($_POST['cancelar'])) {
        // Se o usuário cancelar a exclusão, redirecionar para a página de cadastro de usuários
        header("Location: cadusuario.php");
        exit;
    }
}

// Recuperar os detalhes do usuário com base no ID fornecido
$id = $_GET['id'];
$sql_select = "SELECT * FROM usuarios WHERE id=?";
$stmt = $conexao->prepare($sql_select);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Usuário não encontrado.";
    exit;
}

// Fechar a conexão com o banco de dados
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f5f5fd; /* Lilás claro para o fundo */
        }
        h1 {
            text-align: center;
            color: #6a5acd; /* Lilás mais escuro para o título */
        }
        form {
            margin: 0 auto; /* Centraliza o formulário horizontalmente */
            width: 80%; /* 80% da largura da tela */
            max-width: 400px; /* Largura máxima para evitar que o formulário fique muito largo em telas grandes */
            background-color: #fff; /* Fundo branco para o formulário */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave para o formulário */
        }
        strong {
            color: #6a5acd; /* Lilás mais escuro para o texto em negrito */
        }
        input[type="submit"] {
            width: 45%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #6a5acd; /* Lilás mais escuro para a borda */
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #6a5acd; /* Lilás mais escuro para o botão de envio */
            color: #fff; /* Texto branco para o botão de envio */
            cursor: pointer;
            transition: background-color 0.3s ease; /* Transição suave para a cor de fundo */
        }
        input[type="submit"]:hover {
            background-color: #563d7c; /* Lilás mais escuro no hover */
        }
    </style>
</head>
<body>
    <h1>Deletar Usuário</h1>
    <p>Você está prestes a deletar o usuário: <strong><?php echo $row['nome']; ?></strong>. Tem certeza que deseja prosseguir?</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST" id="deleteForm">
        <input type="submit" name="confirmar" value="Confirmar">
        <input type="submit" name="cancelar" value="Cancelar">
    </form>
    <script>
        // Adicionando animação de entrada ao formulário de exclusão usando JavaScript
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('deleteForm');
            form.style.animation = "fadeInUp 1s ease";
        });
    </script>
</body>
</html>
