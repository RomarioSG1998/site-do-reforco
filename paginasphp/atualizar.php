<?php
// Conecte-se ao banco de dados
$hostname = "localhost";
$bancodedados = "sistemadoreforco";
$usuario = "root";
$senha = "";
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Verifique se os dados do formulário foram recebidos
if(isset($_POST['ra']) && isset($_POST['nome']) && isset($_POST['datanasc']) && isset($_POST['celular']) && isset($_POST['responsavel']) && isset($_POST['genero']) && isset($_POST['turma'])) {
    // Receba os dados do formulário
    $ra = $_POST['ra'];
    $nome = $_POST['nome'];
    $datanasc = $_POST['datanasc'];
    $celular = $_POST['celular'];
    $responsavel = $_POST['responsavel'];
    $genero = $_POST['genero'];
    $turma = $_POST['turma'];

    // Atualize os dados do aluno no banco de dados
    $sql = "UPDATE alunos SET nome='$nome', datanasc='$datanasc', celular='$celular', responsavel='$responsavel', genero='$genero', turma='$turma' WHERE ra='$ra'";
    if ($conexao->query($sql) === TRUE) {
        // Registro atualizado com sucesso. Redirecionar após 3 segundos
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'modcadastro.php';
                }, 3000); // 3 segundos
              </script>";
    } else {
        echo "Erro ao atualizar registro: " . $conexao->error;
    }
} else {
    echo "Por favor, preencha todos os campos.";
}

// Feche a conexão com o banco de dados
$conexao->close();
?>
