<?php
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "id21964020_root";
$senha = "J3anlak#1274";

// Estabelecer conexão
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verificar se houve erro na conexão
if($conexao->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Verificar se o formulário foi submetido
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $ra = $_POST['ra'];
    $janeiro = $_POST['janeiro'];
    $fevereiro = $_POST['fevereiro'];
    $marco = $_POST['marco'];
    $abril = $_POST['abril'];
    $maio = $_POST['maio'];
    $junho = $_POST['junho'];
    $julho = $_POST['julho'];
    $agosto = $_POST['agosto'];
    $setembro = $_POST['setembro'];
    $outubro = $_POST['outubro'];
    $novembro = $_POST['novembro'];
    $dezembro = $_POST['dezembro'];

    // Preparar e executar a consulta SQL
    $query = "UPDATE meses SET janeiro=?, fevereiro=?, marco=?, abril=?, maio=?, junho=?, julho=?, agosto=?, setembro=?, outubro=?, novembro=?, dezembro=? WHERE ra=?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("sssssssssssss", $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $ra);
    $resultado = $stmt->execute();

    if ($resultado) {
        // Redirecionar para a página inicial da tabela
        header("Location: mensalidade.php");
        exit(); // Certifique-se de sair após redirecionar para evitar que o código continue a ser executado
    } else {
        // Erro ao inserir/atualizar dados
        echo "Erro ao atualizar os dados: " . $conexao->error;
    }
}

// Fechar conexão
$conexao->close();
?>
