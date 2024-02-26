<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter dados gerais
$sqlGeral = "SELECT COUNT(*) AS total_alunos, 
                    SUM(CASE WHEN status = 'desistente' THEN 1 ELSE 0 END) AS alunos_desistentes,
                    SUM(CASE WHEN status = 'novato' THEN 1 ELSE 0 END) AS alunos_novatos,
                    AVG(idade) AS media_idade,
                    SUM(CASE WHEN aproveitamento = 'excepcional' THEN 1 ELSE 0 END) AS alunos_aproveitamento_excepcional,
                    SUM(CASE WHEN necessidades_especiais = 'sim' THEN 1 ELSE 0 END) AS alunos_necessidades_especiais
            FROM alunos";
$resultGeral = $conn->query($sqlGeral);

// Consulta SQL para obter dados da turma selecionada (se houver)
$turmaSelecionada = $_POST['turmaSelecionada']; // Supondo que você enviará o valor selecionado do dropdown via POST
if ($turmaSelecionada != 'all') {
    $sqlTurma = "SELECT COUNT(*) AS total_alunos_turma,
                        SUM(CASE WHEN status = 'desistente' THEN 1 ELSE 0 END) AS alunos_desistentes_turma,
                        SUM(CASE WHEN status = 'novato' THEN 1 ELSE 0 END) AS alunos_novatos_turma,
                        AVG(idade) AS media_idade_turma,
                        SUM(CASE WHEN aproveitamento = 'excepcional' THEN 1 ELSE 0 END) AS alunos_aproveitamento_excepcional_turma,
                        SUM(CASE WHEN necessidades_especiais = 'sim' THEN 1 ELSE 0 END) AS alunos_necessidades_especiais_turma
                FROM alunos
                WHERE turma = '$turmaSelecionada'";
    $resultTurma = $conn->query($sqlTurma);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
