<?php
// Incluir o arquivo de conexão com o banco de dados
include('conexao.php');

// Consultar o banco de dados para obter as atividades registradas
$query = "SELECT * FROM atividades ORDER BY data_hora DESC";
$resultado = $conexao->query($query);

if ($resultado->num_rows > 0) {
    echo "<h2>Atividades Registradas</h2>";
    echo "<ul>";
    while ($row = $resultado->fetch_assoc()) {
        echo "<li>{$row['data_hora']}: {$row['alteracoes']}</li>";
    }
    echo "</ul>";
} else {
    echo "Nenhuma atividade registrada.";
}

$conexao->close();
?>
