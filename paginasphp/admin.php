<?php

session_start();

// Dados dos usuários autorizados
$ids_autorizados = array(1, 2); // IDs dos usuários autorizados
$nomes_autorizados = array("Sandra", "Romário  de Souza Galdino"); // Nomes dos usuários autorizados
$senhas_autorizadas = array("145869", "12345678"); // Senhas dos usuários autorizados
$emails_autorizados = array("rg16066@gmail.com", "rg1606694@gmail.com"); // Emails dos usuários autorizados
$data_criacao_autorizadas = array("2024-03-13 20:00:10", "2024-03-12 15:46:59"); // Datas de criação dos usuários autorizados

// Verifica se o usuário está logado
if(isset($_SESSION['id'])) {
    // Obtém os dados do usuário da sessão
    $id_sessao = $_SESSION['id'];
    
    // Consulta o banco de dados para obter os dados do usuário da sessão
    $sql = "SELECT * FROM usuarios WHERE id = $id_sessao";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        // O usuário está autorizado
        $row = mysqli_fetch_assoc($result);
        if (in_array($row['id'], $ids_autorizados) && in_array($row['nome'], $nomes_autorizados) && in_array($row['senha'], $senhas_autorizadas) && in_array($row['email'], $emails_autorizados) && in_array($row['data_criacao'], $data_criacao_autorizadas)) {
            // Exibe o ícone de cadeado com tamanho pequeno
            echo "<div style=\"text-align: center;\">";
            echo "<img src=\"https://th.bing.com/th/id/OIP.3pA-72mLjcZIn8k2FsnRrAHaHa?rs=1&pid=ImgDetMain\" alt=\"Ambiente protegido\" style=\"width: 50px; height: 50px;\">";
            echo "</div>";
        } else {
            echo "<div style=\"text-align: center;\">";
            die("Somente Sandra pode acessar essa página!!!. <p><a href=\"painel.php\">home</a></p><img src=\"https://cdn.pixabay.com/animation/2022/07/31/05/09/05-09-50-80_512.gif\" alt=\"Acesso negado\">");
            echo "</div>";
        }
    } else {
        die("Usuário não encontrado.");
    }
} else {
    // Define a variável de sessão 'id' como o ID autorizado
    $_SESSION['id'] = $id_autorizado;
}

// Fecha a conexão com o banco de dados
mysqli_close($conexao);
?>
