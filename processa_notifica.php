<?php
// Inicia a sessão para usar os dados do autor
session_start();

// Inclui o arquivo de conexão com o banco de dados
include('conexao2.php'); // Verifique se o caminho está correto

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos campos do formulário
    $autor = isset($_POST['autor']) ? trim($_POST['autor']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $destinatario = isset($_POST['destinatario']) ? trim($_POST['destinatario']) : '';
    $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
    $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : null;

    // Verifica se os campos obrigatórios foram preenchidos
    if (empty($autor) || empty($descricao) || empty($destinatario) || empty($date_start)) {
        echo "Por favor, preencha todos os campos obrigatórios.";
        exit;
    }

    // Tenta inserir os dados no banco de dados
    try {
        // Verifica se a conexão foi estabelecida corretamente
        if (!$conexao) {
            die("Erro: Conexão com o banco de dados não estabelecida.");
        }

        // Prepara a consulta SQL
        $stmt = $conexao->prepare("INSERT INTO notifica (autor, descricao, destinatario, date_start, date_end) 
                                   VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $autor, $descricao, $destinatario, $date_start, $date_end);

        // Executa a consulta
        if ($stmt->execute()) {
            echo "Registro salvo com sucesso!";
        } else {
            echo "Erro ao salvar o registro.";
        }

        // Fecha a declaração e a conexão
        $stmt->close();
        $conexao->close();
    } catch (Exception $e) {
        echo "Erro ao salvar os dados: " . $e->getMessage();
    }
} else {
    echo "Requisição inválida.";
}
?>
