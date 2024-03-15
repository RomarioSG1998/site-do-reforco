<?php

include('conexao2.php');
include('admin.php');
include('protect.php'); 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            animation: fadeInUp 1s ease; /* Animação de entrada */
        }
        label {
            color: #6a5acd; /* Lilás mais escuro para os rótulos */
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #6a5acd; /* Lilás mais escuro para a borda */
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #6a5acd; /* Lilás mais escuro para o botão de envio */
            color: #fff; /* Texto branco para o botão de envio */
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Espaço extra entre o formulário e a tabela */
            animation: fadeInUp 1s ease; /* Animação de entrada */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-icon, .delete-icon {
            color: #6a5acd; /* Lilás mais escuro para os ícones de edição e exclusão */
            text-decoration: none;
            margin-right: 5px;
        }
        .delete-icon {
            color: #ff6347; /* Vermelho para o ícone de exclusão */
        }
        @media only screen and (max-width: 600px) {
            form {
                width: 90%; /* Ajusta o formulário para telas menores */
            }
        }
    </style>
</head>
<body>
    <a href="./pageadm.php?nome=<?php echo urlencode($_SESSION['nome']); ?>"><i class="fas fa-home"></i></a>
    <h1>Cadastro de Usuários</h1>
    <?php
// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se todos os campos foram preenchidos
    if (isset($_POST['nome']) && isset($_POST['senha']) && isset($_POST['email'])) {
        // Sanitize os dados de entrada
        $nome = htmlspecialchars($_POST['nome']);
        $senha_usuario = htmlspecialchars($_POST['senha']);
        $email = htmlspecialchars($_POST['email']);

        // Conexão com o banco de dados
        $hostname = "localhost";
        $bancodedados = "id21964020_sistemadoreforco";
        $usuario = "root";
        $senha_bd = ""; // Renomeado para evitar conflito de nome
        $conexao = new mysqli($hostname, $usuario, $senha_bd, $bancodedados);

        // Verificar se a conexão foi estabelecida corretamente
        if ($conexao->connect_error) {
            die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
        }

        // Preparar e executar a declaração SQL para inserir o novo usuário
        $sql_insert = "INSERT INTO usuarios (nome, senha, email, data_criacao) VALUES (?, ?, ?, NOW())";
        $stmt = $conexao->prepare($sql_insert);
        $stmt->bind_param("sss", $nome, $senha_usuario, $email); // Alterado para $senha_usuario
        
        if ($stmt->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário.');</script>";
        }

        // Fechar a conexão com o banco de dados
        $conexao->close();
    } else {
        echo "<script>alert('Todos os campos devem ser preenchidos!');</script>";
    }
}
?>

    <form id="userForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Cadastrar">
    </form>

    <?php
    // Conexão com o banco de dados
    $hostname = "localhost";
    $bancodedados = "id21964020_sistemadoreforco";
    $usuario = "root";
    $senha_bd = ""; // Renomeado para evitar conflito de nome
    $conexao = new mysqli($hostname, $usuario, $senha_bd, $bancodedados);

    // Verificar se a conexão foi estabelecida corretamente
    if ($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Recuperar todos os dados da tabela de usuários
    $sql_select = "SELECT * FROM usuarios";
    $result = $conexao->query($sql_select);

    if ($result->num_rows > 0) {
        echo "<h2>Usuários cadastrados:</h2>";
        echo "<table id='userTable'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Data de Criação</th><th>Ações</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["data_criacao"] . "</td>";
            echo "<td><a class='edit-icon' href='editarusu.php?id=" . $row["id"] . "'><i class='fas fa-edit'></i></a><a class='delete-icon' href='deletarusu.php?id=" . $row["id"] . "'><i class='fas fa-trash-alt'></i></a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum usuário cadastrado.</p>";
    }

    // Fechar a conexão com o banco de dados
    $conexao->close();
    ?>
    <script>
        // Adicionando animações aos elementos
        const form = document.getElementById('userForm');
        form.style.animation = "fadeInUp 1s ease";

        const table = document.getElementById('userTable');
        table.style.animation = "fadeInUp 1s ease";
    </script>
</body>
</html>
