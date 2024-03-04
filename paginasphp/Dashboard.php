
<?php
include('protect.php');
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
    </style>
</head>
<body>
    <div class="container">
       <!-- Botão de menu -->
<a href="./painel.php" class="menu-link" style="display: block; width: fit-content; margin: 20px auto; padding: 10px 20px; background-color: purple; color: #fff; text-decoration: none; border-radius: 5px; transition: background-color 0.3s ease;">Menu</a>


        <h1>Informações mais detalhadas</h1> <!-- Botão de menu -->
        <div class="dashboard">
            <div class="widget">
                <h2>Dados Gerais</h2>
                <p>Total de Alunos: <?php echo $rowGeral['totalAlunos']; ?></p>
                <p>Média de Idade: <?php echo round($rowGeral['mediaIdade'], 2); ?> anos</p>
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


        <!-- Adicione o gráfico abaixo -->
        <div id="piechart"></div>
    </div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Monta os dados do gráfico a partir dos resultados da consulta SQL
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Turma');
            data.addColumn('number', 'Total de Alunos');
            <?php
            // Loop para adicionar as linhas dos dados do gráfico
            $resultTurma->data_seek(0); // Resetando o ponteiro do resultado da consulta
            while ($rowTurma = $resultTurma->fetch_assoc()) {
                echo "data.addRow(['" . $rowTurma['turma'] . "', " . $rowTurma['totalAlunosTurma'] . "]);";
            }
            ?>

            // Opções do gráfico
            var options = {
                title: 'Total de Alunos por Turma',
                pieHole: 0.4, // Opção para criar um donut chart (gráfico de rosca com buraco),
                width: '100%', // Largura do gráfico em porcentagem para ser responsivo
                height: '100%', // Altura do gráfico em porcentagem para ser responsivo
                chartArea: { width: '90%', height: '90%' }, // Área do gráfico em porcentagem
            };

            // Verificar o tamanho da tela
            var screenWidth = window.innerWidth;
            if (screenWidth <= 600) {
                options.pieHole = 0.2; // Diminui o tamanho do buraco do gráfico para telas menores
            }

            // Cria o gráfico de rosca
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    <!-- Botão de menu -->
</body>
</html>
