<?php

// Incluir o arquivo de conexão com o banco de dados
include('conexao2.php');

// Verificar se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se o parâmetro "id" foi enviado via POST
    if (isset($_POST['id'])) {
        // Limpar e validar o ID do comentário
        $comentario_id = mysqli_real_escape_string($conexao, $_POST['id']);

        // Query SQL para deletar o comentário pelo ID
        $sql = "DELETE FROM avaliacao WHERE id = '$comentario_id'";

        // Executar a query
        if ($conexao->query($sql) === TRUE) {
            // Retornar uma resposta de sucesso
            echo "Comentário deletado com sucesso!";
        } else {
            // Se houver erro, retornar a mensagem de erro
            echo "Erro ao deletar comentário: " . $conexao->error;
        }
    } else {
        // Se o parâmetro "id" não foi enviado, retornar mensagem de erro
        echo "ID do comentário não foi fornecido.";
    }
}

// Fechar a conexão com o banco de dados
$conexao->close();

?>
