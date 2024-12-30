<?php
// atualizar_status.php

// Verifica se o método da requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se as variáveis ra e novoStatus foram recebidas
    if (isset($_POST['ra']) && isset($_POST['novoStatus'])) {
        $ra = $_POST['ra'];
        $novoStatus = $_POST['novoStatus'];

        // Aqui você deve adicionar o código para atualizar o status do aluno no banco de dados
        // Substitua este exemplo pelo seu código real
        include('conexao2.php');

        // Prepara e executa a declaração SQL para atualizar o status do aluno
        $stmt = $conexao->prepare("UPDATE alunos SET situacao=? WHERE ra=?");
        $stmt->bind_param("ss", $novoStatus, $ra);

        if ($stmt->execute()) {
            echo "Status atualizado com sucesso.";
        } else {
            echo "Erro ao atualizar o status: " . $stmt->error;
        }

        $stmt->close();
        $conexao->close();
    } else {
        echo "Parâmetros inválidos.";
    }
} else {
    echo "Método não permitido.";
}
?>
