<?php

// Conecte-se ao banco de dados
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Verifique se os dados do formulário foram recebidos corretamente
if(isset($_GET['ra']) && !empty($_GET['ra'])) {
    // Receba o RA do formulário
    $ra = $_GET['ra'];

    // Desabilitar temporariamente a restrição de chave estrangeira
    $conexao->query("SET FOREIGN_KEY_CHECKS=0");

    // Apague o registro do aluno do banco de dados
    $sql = "DELETE FROM alunos WHERE ra=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $ra);

    if ($stmt->execute()) {
        // Registro apagado com sucesso. Redirecionar após 3 segundos
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'modcadastro.php?exclusao_sucesso=true';
                }, 3000); // 3 segundos
              </script>";
    } else {
        echo "Erro ao apagar registro: " . $conexao->error;
    }

    // Reativar a restrição de chave estrangeira
    $conexao->query("SET FOREIGN_KEY_CHECKS=1");
} else {
    echo "Por favor, forneça o RA do aluno a ser apagado.";
}

// Feche a conexão com o banco de dados
$conexao->close();
?>
