<?php
include('conexao2.php');

// Consulta SQL para buscar dados gerais
$sqlGeral = "SELECT COUNT(ra) as totalAlunos, AVG(YEAR(CURRENT_DATE) - YEAR(datanasc)) as mediaIdade FROM alunos";
$resultGeral = $conexao->query($sqlGeral);
$rowGeral = $resultGeral->fetch_assoc();

// Consulta SQL para buscar dados por turma
$sqlTurma = "SELECT turma, COUNT(ra) as totalAlunosTurma FROM alunos GROUP BY turma";
$resultTurma = $conexao->query($sqlTurma);

$conexao->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Escolar</title>
    <style>
        /* Estilo para o link */
        a.menu-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        /* Estilo para o link quando passa o mouse por cima */
        a.menu-link:hover {
            background-color: #45a049;
        }
    </style>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="../css/Dashboard.css" />
</head>
<body>
    <nav>
        <div class="container">
            <h1>Sede do Saber</h1>   <a href="./painel.php" class="menu-link">Menu</a>
            </button><select id="turmaSelect">
                <option value="all">Todas as Turmas</option>
                <option value="T1">T1</option>
                <option value="T2">T2</option>
                <option value="T3">T3</option>
                <option value="T4">T4</option>
                <option value="T5">T5</option>
                <option value="T6">T6</option>
                <!-- Opções de turmas seriam preenchidas dinamicamente com JavaScript -->
            </select>
        </div>

    </nav>
    <div class="container">
        <section id="geral">
            <h2>Visão Geral</h2>
            <div id="dadosGerais">
                <p>Total de Alunos: <?php echo $rowGeral['totalAlunos']; ?></p>
                <p>Média de Idade: <?php echo $rowGeral['mediaIdade']; ?></p>
            </div>
        </section>
        <section id="turma">
            <h2>Visão por Turma</h2>
            <div id="dadosTurma">
                <?php
                if ($resultTurma->num_rows > 0) {
                    while($rowTurma = $resultTurma->fetch_assoc()) {
                        echo "<p>Turma: " . $rowTurma['turma'] . "</p>";
                        echo "<p>Total de Alunos: " . $rowTurma['totalAlunosTurma'] . "</p>";
                        // Se quiser mais informações por turma, você pode adicionar consultas SQL adicionais aqui
                    }
                } else {
                    echo "Nenhum resultado encontrado.";
                }
                ?>
            </div>
        </section>
    </div>
    <!-- Seus scripts JavaScript aqui -->
</body>
</html>
