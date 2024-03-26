<?php
include('conexao2.php');
session_start();

// IDs dos usuários autorizados
$ids_autorizados = array(1, 2, 17); // IDs dos usuários autorizados

// Verifica se o usuário está logado
if(isset($_SESSION['id'])) {
    // Obtém o ID do usuário da sessão
    $id_sessao = $_SESSION['id'];
    
    // Verifica se o ID da sessão está na lista de IDs autorizados
    if(in_array($id_sessao, $ids_autorizados)) {
        // O usuário está autorizado
        echo "<div style=\"text-align: center;\">";
        echo "<img id=\"cadeado\" src=\"https://th.bing.com/th/id/OIP.3pA-72mLjcZIn8k2FsnRrAHaHa?rs=1&pid=ImgDetMain\" alt=\"Ambiente protegido\" style=\"width: 50px; height: 50px;\">";
        echo "</div>";
    } else {
        echo "<div style=\"text-align: center;\">";
        echo "Acesso negado. <p><a href=\"painel.php\">Voltar</a></p><img src=\"https://cdn.pixabay.com/animation/2022/07/31/05/09/05-09-50-80_512.gif\" alt=\"Acesso negado\">";
        echo "</div>";
    }
} else {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>
<script>
    // Adiciona script JavaScript para remover o ícone do cadeado após 2 segundos
    setTimeout(function() {
        var elem = document.getElementById('cadeado');
        elem.parentNode.removeChild(elem);
    }, 500);
</script>



