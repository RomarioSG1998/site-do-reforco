<?php
// Verifica se foi enviado o parâmetro ID na URL
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Obtém o ID do aluno da URL e realiza a sanitização
    $aluno_id = htmlspecialchars($_GET['id']);

    // Aqui você pode adicionar o código para editar o registro com o ID fornecido

    // Após editar o registro, você pode redirecionar de volta para a página da tabela com uma mensagem de sucesso
    header("Location: tabela.php?edicao_sucesso=true");
    exit();
} else {
    // Caso o parâmetro ID não tenha sido enviado na URL
    echo "ID do aluno não fornecido na URL.";
}
?>
