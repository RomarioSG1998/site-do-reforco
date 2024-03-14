<?php

include('protect.php');
include('conexao2.php');
include ('registrarAtividade.php');

// Consulta SQL para buscar dados gerais
$sqlGeral = "SELECT COUNT(ra) as totalAlunos, AVG(YEAR(CURRENT_DATE) - YEAR(datanasc)) as mediaIdade FROM alunos";
$resultGeral = $conexao->query($sqlGeral);
$rowGeral = $resultGeral->fetch_assoc();

// Consulta SQL para buscar os aniversariantes do mês
$currentMonth = date('m');
$sqlAniversariantes = "SELECT * FROM alunos WHERE MONTH(datanasc) = $currentMonth";
$resultAniversariantes = $conexao->query($sqlAniversariantes);

// Consulta SQL para buscar dados por turma
$sqlTurma = "SELECT turma, COUNT(ra) as totalAlunosTurma FROM alunos GROUP BY turma";
$resultTurma = $conexao->query($sqlTurma);

// Consulta SQL para buscar dados por gênero
$sqlGenero = "SELECT genero, COUNT(ra) as totalAlunosGenero FROM alunos GROUP BY genero";
$resultGenero = $conexao->query($sqlGenero);

$conexao->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css"/>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Estatísticas de Alunos</title>
    <style>
        /* Estilos para o botão de menu */
        .menu-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px; /* Adicione margem inferior para espaçamento */
        }

        .menu-link:hover {
            background-color: #0056b3;
        }

       /* Animação de bolo de aniversário */
.birthday-cake {
 
    background-image: url('../imagens/aniversario.gif');
    width: 90%; /* Ajuste o tamanho conforme necessário */
    height: 100px;
    background-size: cover;
    background-size: contain;
    animation: bounce 2s infinite;
    margin-left: -10px; /* Ajuste a margem conforme necessário */
}



@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Botão de menu -->
        <a href="./painel.php" class="menu-link" style="display: block; width: fit-content; margin: 20px auto; padding: 10px 20px; background-color: purple; color: #fff; text-decoration: none; border-radius: 5px; transition: background-color 0.3s ease;">Menu</a>
        <h1>Informações mais detalhadas</h1>

        <div class="dashboard">
            <div class="widget">
                <h2>Dados Gerais</h2>
                <p>Total de Alunos: <?php echo $rowGeral['totalAlunos']; ?></p>
                <p>Média de Idade: <?php echo round($rowGeral['mediaIdade'], 2); ?> anos</p>
            </div>

            <div class="widget">
                <h2 class="animate__animated animate__bounce">Aniversariantes do Mês</h2>
                <!-- Adicionando bolo de aniversário animado -->
                <div class="birthday-cake"></div>
                <ul class="animate__animated animate__bounce">
                    <?php
                    while ($rowAniversariante = $resultAniversariantes->fetch_assoc()) {
                        echo "<li>" . $rowAniversariante['nome'] . " - " . date('d/m/Y', strtotime($rowAniversariante['datanasc'])) . "</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="widget">
                <h2>Dados por Turma</h2>
                <table>
                    <tr>
                        <th>Turma</th>
                        <th>Total de Alunos</th>
                    </tr>
                    <?php
                    while ($rowTurma = $resultTurma->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $rowTurma['turma'] . "</td>";
                        echo "<td>" . $rowTurma['totalAlunosTurma'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>

        <!-- Adicione o título para o gráfico de pizza de gênero -->
        <h2>Total de Alunos por Gênero</h2>
        <div id="piechartGenero"></div>

        <!-- Adicione o título para o gráfico de pizza de turmas -->
        <h2>Total de Alunos por Turma</h2>
        <div id="piechartTurma"></div>

    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartGenero);

        function drawChartGenero() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Gênero');
            data.addColumn('number', 'Total de Alunos');

            <?php
            $resultGenero->data_seek(0);
            while ($rowGenero = $resultGenero->fetch_assoc()) {
                echo "data.addRow(['" . $rowGenero['genero'] . "', " . $rowGenero['totalAlunosGenero'] . "]);";
            }
            ?>

            var options = {
                title: 'Total de Alunos por Gênero',
                pieHole: 0.4,
                width: '100%',
                height: '100%',
                chartArea: { width: '90%', height: '90%' },
            };

            var screenWidth = window.innerWidth;
            if (screenWidth <= 600) {
                options.pieHole = 0.2;
            }

            var chart = new google.visualization.PieChart(document.getElementById('piechartGenero'));
            chart.draw(data, options);
        }

        google.charts.setOnLoadCallback(drawChartTurma);

        function drawChartTurma() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Turma');
            data.addColumn('number', 'Total de Alunos');

            <?php
            $resultTurma->data_seek(0);
            while ($rowTurma = $resultTurma->fetch_assoc()) {
                echo "data.addRow(['" . $rowTurma['turma'] . "', " . $rowTurma['totalAlunosTurma'] . "]);";
            }
            ?>

            var options = {
                title: 'Total de Alunos por Turma',
                pieHole: 0.4,
                width: '100%',
                height: '100%',
                chartArea: { width: '90%', height: '90%' },
            };

            var screenWidth = window.innerWidth;
            if (screenWidth <= 600) {
                options.pieHole = 0.2;
            }

            var chart = new google.visualization.PieChart(document.getElementById('piechartTurma'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
