<?php
include('conexao2.php');
session_start();

// Função para verificar se o usuário é pleno
function usuarioPleno($id_usuario) {
    // Conexão com o banco de dados
    include('conexao2.php');
    
    // Consulta ao banco de dados para obter o tipo de usuário do ID fornecido
    $query = "SELECT tipo FROM usuarios WHERE id = $id_usuario";
    $resultado = mysqli_query($conexao, $query);
    
    if ($resultado) {
        $row = mysqli_fetch_assoc($resultado);
        $tipo_usuario = $row['tipo'];
        
        // Verifica se o tipo de usuário é "pleno"
        if ($tipo_usuario === 'Admin') {
            return true;
        }
    }
    
    // Caso contrário, retorna falso
    return false;
}

// Verifica se o usuário está logado
if(isset($_SESSION['id'])) {
    // Obtém o ID do usuário da sessão
    $id_sessao = $_SESSION['id'];
    
    // Verifica se o usuário é pleno
    if(usuarioPleno($id_sessao)) {
        // O usuário é pleno, exibe o conteúdo autorizado
        echo "<div style=\"text-align: center;\">";
        echo "<img id=\"cadeado\" src=\"https://th.bing.com/th/id/OIP.3pA-72mLjcZIn8k2FsnRrAHaHa?rs=1&pid=ImgDetMain\" alt=\"Ambiente protegido\" style=\"width: 50px; height: 50px;\">";
        echo "</div>";
    } else {
        // O usuário não é pleno, exibe mensagem de acesso negado
        echo "<div style=\"text-align: center;\">";
        echo "Acesso negado, pois não é um usuário Admin. <p><a href=\"painel.php\">Voltar</a></p><img src=\"https://cdn.pixabay.com/animation/2022/07/31/05/09/05-09-50-80_512.gif\" alt=\"Acesso negado\">";
        echo "</div>";
        // Impede o script de continuar a execução
        exit();
    }
} else {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>
<script>
    // Adiciona script JavaScript para remover o ícone do cadeado após 500 milissegundos
    setTimeout(function() {
        var elem = document.getElementById('cadeado');
        elem.parentNode.removeChild(elem);
    }, 500);
</script>
