<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao2.php';

// Verifica se a sessão já foi iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Você não pode acessar esta página, pois não está logado.<p><a href=\"logaluno.php\">Entrar</a></p>");
}

// Verifica se a conexão está ativa
if (!$conexao || $conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Verifica se o usuário é administrador
$id_usuario = $_SESSION['id'];
$sql = "SELECT tipo FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($tipo);
$stmt->fetch();
$stmt->close();

if ($tipo !== 'aluno(a)') {
    die("Você não tem permissão para acessar esta página, pois não é aluno(a).<p><a href=\"logaluno.php\">Entrar</a></p>");
}

// Continua com a execução do restante do código
?>
