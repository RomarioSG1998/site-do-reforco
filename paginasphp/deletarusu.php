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
        $sql_delete = "DELETE FROM usuarios WHERE id=?";
        $stmt = $conexao->prepare($sql_delete);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Usuário deletado com sucesso!";
            // Redirecionar para a página de cadastro de usuários após a exclusão
            header("Location: cadusuario.php");
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
</head>
<body>
    <h1>Deletar Usuário</h1>
    <p>Você está prestes a deletar o usuário: <strong><?php echo $row['nome']; ?></strong>. Tem certeza que deseja prosseguir?</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST">
        <input type="submit" name="confirmar" value="Confirmar">
        <input type="submit" name="cancelar" value="Cancelar">
    </form>
</body>
</html>
