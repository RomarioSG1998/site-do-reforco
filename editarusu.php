<?php
include('conexao2.php');
//include('admin.php');
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
    // Receber os dados do formulário
    $id = $_GET['id'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    
    // Upload da imagem
    $nome_imagem = $_FILES['imagem']['name'];
    $diretorio_imagem = 'imagens/';
    $caminho_imagem = $diretorio_imagem . $nome_imagem;
    move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem);

    // Preparar e executar a consulta SQL para atualizar os dados na tabela
    $sql = "UPDATE usuarios SET nome=?, senha=?, email=?, tipo=?, usu_img=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssi", $nome, $senha, $email, $tipo, $caminho_imagem, $id);
    if ($stmt->execute()) {
        // Redirecionar para cadusuario.php após a edição
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'http://localhost/sededosaber/painel.php?page=cadusuario';
                }, 2000); // 2 segundos
              </script>";
        exit;
    } else {
        echo "Erro ao atualizar usuário: " . $conexao->error;
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
    <title>Editar Usuário</title>
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
        input[type="file"],
        select { /* Adicionando estilo para o input de arquivo */
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
    </style>
</head>
<body>
    <h1>Editar Usuário</h1>
    <form id="editUserForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" required><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" value="<?php echo $row['senha']; ?>" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        <label for="tipo">Tipo de Usuário:</label><br>
        <select id="tipo" name="tipo" required>
            <option value="professor(a)" <?php if($row['tipo'] == 'Professor(a)') echo 'selected'; ?>>Professor(a)</option>
            <option value="admin" <?php if($row['tipo'] == 'Admin') echo 'selected'; ?>>Admin</option>
        </select><br>
        <label for="imagem">Nova Imagem: (ao atualizar qualquer informação, atualize também sua imagem)</label><br>
        <input type="file" id="imagem" name="imagem" accept="image/*"><br><br>
        <label for="imagem">Imagem Atual:</label><br>
        <img src="<?php echo $row['usu_img']; ?>" alt="Imagem atual do usuário" style="max-width:70px; border-radius: 50%;"><br><br>
        <input type="submit" value="Salvar">
    </form>
    <script>
        // Adicionando animações aos elementos
        const form = document.getElementById('editUserForm');
        form.style.animation = "fadeInUp 1s ease";
    </script>
</body>
</html>
