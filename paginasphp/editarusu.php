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
$bancodedados = "sistemadoreforco";
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

    // Preparar e executar a consulta SQL para atualizar os dados na tabela
    $sql = "UPDATE usuarios SET nome=?, senha=?, email=? WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssi", $nome, $senha, $email, $id);
    if ($stmt->execute()) {
        // Redirecionar de volta para cadusuario.php após a edição
        header("Location: cadusuario.php");
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
</head>
<body>
    <h1>Editar Usuário</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" required><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" value="<?php echo $row['senha']; ?>" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required><br><br>
        <input type="submit" value="Salvar">
    </form>
</body>
</html>
