<?php
// Verificar se uma sessão ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$hostname = "localhost";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "";

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
    $obs = $_POST['obs'];

    // Preparar e executar a consulta SQL
    $query = "UPDATE meses SET janeiro=?, fevereiro=?, marco=?, abril=?, maio=?, junho=?, julho=?, agosto=?, setembro=?, outubro=?, novembro=?, dezembro=?, obs=? WHERE ra=?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ssssssssssssss", $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $obs, $ra);
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
