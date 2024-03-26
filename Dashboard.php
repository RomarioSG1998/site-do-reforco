<?php

include('protect.php');
include('conexao2.php');

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

// Consulta SQL para buscar dados da tabela de avaliação
$sqlAvaliacao = "SELECT ns, COUNT(id) as total_por_ns FROM avalicao GROUP BY ns";
$resultAvaliacao = $conexao->query($sqlAvaliacao);

// Calcular a porcentagem de cada valor de "ns"
$totalAvaliacoes = 0;
$dadosGraficoBarra = array();
while ($rowAvaliacao = $resultAvaliacao->fetch_assoc()) {
    $totalAvaliacoes += $rowAvaliacao['total_por_ns'];
    $dadosGraficoBarra[$rowAvaliacao['ns']] = $rowAvaliacao['total_por_ns'];
}

// Calcular a porcentagem
foreach ($dadosGraficoBarra as $ns => $total_por_ns) {
    $porcentagem = ($total_por_ns / $totalAvaliacoes) * 100;
    $dadosGraficoBarra[$ns] = $porcentagem;
}

// Obter a última data de atualização das informações
$sqlUltimaAtualizacao = "SELECT MAX(data) as ultima_atualizacao FROM avalicao";
$resultUltimaAtualizacao = $conexao->query($sqlUltimaAtualizacao);
$rowUltimaAtualizacao = $resultUltimaAtualizacao->fetch_assoc();
$ultimaAtualizacao = $rowUltimaAtualizacao['ultima_atualizacao'];

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
        body {
            background-color: #44277D;
            background-size: cover;
            background-position: center;
        }
        .cadastro-frase {
            font-size: 35px;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            text-align:center;
            margin-bottom: 20px;
            color:WHITE; /* Define a cor do texto */
            text-shadow: 
                -2px -2px 0 #44277D,  
                2px -2px 0 #44277D,
                -2px 2px 0 #44277D,
                2px 2px 0 #44277D;
        }
        /* Estilos para a imagem */
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 15%;
            margin-top: -25px;
            margin-bottom: 7px;
        }
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
            background-repeat: no-repeat;
            width: 90%; /* Ajuste o tamanho conforme necessário */
            height: 100px;
            background-size: cover;
            background-size: contain;
            animation: bounce 2s infinite;
            margin-left: -10px; /* Ajuste a margem conforme necessário */
        }
        h2 {
            text-align: left;
            margin-top: 50px;
            margin-left:0px;
            color: black;
        }
        /* Estilos para o texto ao lado do gráfico */
        #textoAoLadoDoGrafico {
            float: right;
            margin-top: 50px;
            margin-left:0px%;
            margin-right: 50px;
            font-size: 14px;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        .hidden {
            display: none;
        }
        /* Media query para telas com largura de 750 pixels ou menos */
@media only screen and (max-width: 750px) {
    h2,
    #textoAoLadoDoGrafico {
        float: none; /* Remover a flutuação */
        margin: 20px auto; /* Definir margens para centralizar os elementos */
        text-align: center; /* Centralizar o texto */
    }
}
    </style>
</head>
<body>
    <div class="container">
        <!-- Botão de menu -->
        <div class="content">
            <p class="cadastro-frase">INFORMAÇÕES</p>
            <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
                <img class="cadastro-imagem" src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
            </a>
        </div>

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
                <ul id="aniversariantes" class="animate__animated animate__bounce">
                    <?php
                    $count = 0;
                    while ($rowAniversariante = $resultAniversariantes->fetch_assoc()) {
                        // Mostrar apenas os primeiros 3 aniversariantes
                        if ($count < 3) {
                            echo "<li>" . $rowAniversariante['nome'] . " - " . date('d/m/Y', strtotime($rowAniversariante['datanasc'])) . "</li>";
                       
                        } else {
                            // Se houver mais aniversariantes, oculte-os
                            echo "<li class='hidden'>" . $rowAniversariante['nome'] . " - " . date('d/m/Y', strtotime($rowAniversariante['datanasc'])) . "</li>";
                        }
                        $count++;
                    }
                    ?>
                </ul>
                <?php
                // Se houver mais de 3 aniversariantes, mostre o botão para ver todos
                if ($count > 3) {
                    echo '<button onclick="mostrarTodosAniversariantes()">Mostrar Todos</button>';
                }
                ?>
            </div>
        </div>

        <!-- Adicione o título para o gráfico de pizza de gênero -->
        <h2>Total de Alunos por Gênero</h2>
        <div id="piechartGenero"></div>

        <!-- Adicione o título para o gráfico de pizza de turmas -->
        <h2>Total de Alunos por Turma</h2>
        <div id="piechartTurma"></div>

        <!-- Adicione o título para o gráfico de barras de avaliação -->
        <h2>Distribuição das Avaliações</h2>
        <div class="container">
        <div id="textoAoLadoDoGrafico">
            <p>Este gráfico mostra a distribuição das <br> avaliações em relação ao número de<br> estrelas escolhidas pelos pais.</p>
            <p>Total de participantes: <span id="totalParticipantes"><?php echo $totalAvaliacoes; ?></span></p>
        </div>
        <div id="myChart" style="width:50%; height:400px;"></div>
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

        google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChartAvaliacao);

function drawChartAvaliacao() {
    var data = google.visualization.arrayToDataTable([
        ['Estrelas', 'Porcentagem'], // Definindo as colunas corretamente
        <?php
        foreach ($dadosGraficoBarra as $ns => $porcentagem) {
            echo "['$ns', $porcentagem],";
        }
        ?>
    ]);

    var options = {
        title: 'Avaliações',
        legend: { position: 'none' },
        colors: ['purple'], // Verde
        hAxis: {
            title: 'Porcentagem',
            textStyle: {
                fontSize: 14
            }
        },
        vAxis: {
            title: 'Estrelas',
            textStyle: {
                fontSize: 14
            }
        },
        // Remover a largura fixa do chartArea
        // chartArea: { width: '70%', height: '70%' },
        bars: 'horizontal', // Definindo a orientação das barras para horizontal
        // Adicionar a opção responsive
        width: '100%',
        height: '100%'
    };

    var chart = new google.visualization.BarChart(document.getElementById('myChart'));
    chart.draw(data, options);
}
        function mostrarTodosAniversariantes() {
            // Ocultar o botão
            document.querySelector('button').style.display = 'none';

            // Remover a classe de ocultar dos aniversariantes restantes
            var aniversariantesRestantes = document.querySelectorAll('#aniversariantes li.hidden');
            aniversariantesRestantes.forEach(function (item) {
                item.classList.remove('hidden');
            });
        }
    </script>
</body>
</html>
