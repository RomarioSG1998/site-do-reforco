<?php
include("conexao2.php");

if (isset($_GET['id_aluno']) && isset($_GET['mes'])) {
    $id_aluno = $_GET['id_aluno'];
    $mes = $_GET['mes'];

    $sql = "SELECT obs FROM meses WHERE id_aluno = ? AND $mes IS NOT NULL";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_aluno);
    $stmt->execute();
    $stmt->bind_result($observacao);
    $stmt->fetch();

    echo htmlspecialchars($observacao ?: ""); // Retorna a observação ou uma string vazia
    $stmt->close();
}
?>
