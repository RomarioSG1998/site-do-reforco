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
$sqlAvaliacao = "SELECT ns, comente, data, COUNT(id) as total_por_ns FROM avaliacao GROUP BY ns, comente, data";
$resultAvaliacao = $conexao->query($sqlAvaliacao);

// Array para armazenar os comentários por ns
$comentariosPorNS = array();
while ($rowAvaliacao = $resultAvaliacao->fetch_assoc()) {
    $comentariosPorNS[$rowAvaliacao['ns']][] = array('comentario' => $rowAvaliacao['comente'], 'data' => $rowAvaliacao['data']);
}

// Calcular a porcentagem de cada valor de "ns"
$totalAvaliacoes = 0;
$dadosGraficoBarra = array();
$resultAvaliacao->data_seek(0); // Retorna o ponteiro de resultado para o início
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
$sqlUltimaAtualizacao = "SELECT MAX(data) as ultima_atualizacao FROM avaliacao";
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
            color:WHITE;
            text-shadow: 
                -2px -2px 0 #44277D,  
                2px -2px 0 #44277D,
                -2px 2px 0 #44277D,
                2px 2px 0 #44277D;
        }
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 15%;
            margin-top: -25px;
            margin-bottom: 7px;
        }
        .menu-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }
        .menu-link:hover {
            background-color: #0056b3;
        }
        .birthday-cake {
            background-image: url('../imagens/aniversario.gif');
            background-repeat: no-repeat;
            width: 90%;
            height: 100px;
            background-size: cover;
            background-size: contain;
            animation: bounce 2s infinite;
            margin-left: -10px;
        }
        h2 {
            text-align: left;
            margin-top: 50px;
            margin-left:0px;
            color: black;
        }
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
        @media only screen and (max-width: 750px) {
            h2,
            #textoAoLadoDoGrafico {
                float: none;
                margin: 20px auto;
                text-align: center;
            }
        }

        /* Estilos para o popup */
        #comentariosPopup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup-content {
            max-width: 400px;
            margin: 0 auto;
            max-height: 400px;
            overflow-y: auto;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #999;
        }

        .close-btn:hover {
            color: #555;
        }

        #listaComentarios {
            padding: 0;
            list-style-type: none;
            margin-top: 10px;
        }

        #listaComentarios li {
            margin-bottom: 10px;
        }

        .comentario-1 {
            color: red;
            font-weight: bold;
        }

        .comentario-3 {
            color: green;
            font-weight: bold;
        }

        /* Estilo adicional para animação de flash */
        .animate__flash {
            animation-duration: 1s;
        }
    </style>
</head>
<body>
    <div class="container">
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
                <div class="birthday-cake"></div>
                <ul id="aniversariantes" class="animate__animated animate__bounce">
                    <?php
                    $count = 0;
                    while ($rowAniversariante = $resultAniversariantes->fetch_assoc()) {
                        if ($count < 3) {
                            echo "<li>" . $rowAniversariante['nome'] . " - " . date('d/m/Y', strtotime($rowAniversariante['datanasc'])) . "</li>";
                       
                        } else {
                            echo "<li class='hidden'>" . $rowAniversariante['nome'] . " - " . date('d/m/Y', strtotime($rowAniversariante['datanasc'])) . "</li>";
                        }
                        $count++;
                    }
                    ?>
                </ul>
                <?php
                if ($count > 3) {
                    echo '<button onclick="mostrarTodosAniversariantes()">Mostrar Todos</button>';
                }
                ?>
            </div>
        </div>

        <h2>Total de Alunos por Gênero</h2>
        <div id="piechartGenero"></div>

        <h2>Total de Alunos por Turma</h2>
        <div id="piechartTurma"></div>

        <h2>Distribuição das Avaliações</h2>
        <div class="container">
            <div id="textoAoLadoDoGrafico">
                <p>Este gráfico mostra a distribuição das <br> avaliações em relação ao número de<br> estrelas escolhidas pelos pais.</p>
                <p>Total de participantes: <span id="totalParticipantes"><?php echo $totalAvaliacoes; ?></span></p>
            </div>
            <div id="myChart" style="width:100%; height:400px;"></div>
            <button id="btnComentarios" class="<?php echo ($ultimaAtualizacao == date('Y-m-d')) ? 'novo-comentario' : ''; ?>">Ver Comentários</button>
        </div>

        <div id="comentariosPopup" class="hidden">
            <div class="popup-content">
                <span class="close-btn" onclick="fecharPopup()">&times;</span>
                <h3>Comentários das Avaliações</h3>
                <ul id="listaComentarios"></ul>
            </div>
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
                    is3D: true // Adicionando efeito 3D ao gráfico
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
                    is3D: true // Adicionando efeito 3D ao gráfico
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
                    ['Estrelas', 'Porcentagem'],
                    <?php
                    foreach ($dadosGraficoBarra as $ns => $porcentagem) {
                        echo "['$ns', $porcentagem],";
                    }
                    ?>
                ]);

                var options = {
                    title: 'Avaliações',
                    legend: { position: 'none' },
                    colors: ['purple'],
                    vAxis: {
                        title: 'Porcentagem',
                        textStyle: {
                            fontSize: 14
                        }
                    },
                    hAxis: {
                        title: 'Estrelas',
                        textStyle: {
                            fontSize: 14
                        }
                    },
                    width: '100%',
                    height: '100%',
                    bar: { groupWidth: '5%' } // Ajuste a largura das barras aqui
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('myChart'));
                chart.draw(data, options);
            }

            function mostrarTodosAniversariantes() {
                document.querySelector('button').style.display = 'none';
                var aniversariantesRestantes = document.querySelectorAll('#aniversariantes li.hidden');
                aniversariantesRestantes.forEach(function (item) {
                    item.classList.remove('hidden');
                });
            }

            function exibirComentarios() {
                var comentariosPopup = document.getElementById('comentariosPopup');
                comentariosPopup.classList.remove('hidden');

                var listaComentarios = document.getElementById('listaComentarios');
                listaComentarios.innerHTML = '';

                <?php
                foreach ($comentariosPorNS as $ns => $comentarios) {
                    echo "listaComentarios.innerHTML += '<li><strong>$ns estrela(s):</strong></li>';";
                    foreach ($comentarios as $comentario) {
                        $data = date('d/m/Y', strtotime($comentario['data']));
                        if ($ns <=2) {
                            echo "listaComentarios.innerHTML += '<li class=\"comentario-1\">{$comentario['comentario']} - $data</li>';"; // Adiciona a data aqui
                        } elseif ($ns > 3) {
                            echo "listaComentarios.innerHTML += '<li class=\"comentario-3\">{$comentario['comentario']} - $data</li>';"; // Adiciona a data aqui
                        } else {
                            echo "listaComentarios.innerHTML += '<li>{$comentario['comentario']} - $data</li>';"; // Adiciona a data aqui
                        }
                    }
                }
                ?>
            }

            // Função para piscar o botão "Ver Comentários" apenas quando houver novos comentários não visualizados
            function piscarBotao() {
                var botaoComentarios = document.getElementById('btnComentarios');
                if (botaoComentarios.classList.contains('novo-comentario')) {
                    botaoComentarios.classList.add('animate__animated', 'animate__flash');
                    setTimeout(function() {
                        botaoComentarios.classList.remove('animate__animated', 'animate__flash');
                    }, 1000); // Tempo em milissegundos para remover a classe de animação
                }
            }

            // Adicionando o evento de clique ao botão
            document.getElementById('btnComentarios').addEventListener('click', exibirComentarios);

            // Inicia a piscagem do botão apenas quando há novos comentários não visualizados
            document.addEventListener('DOMContentLoaded', function() {
                piscarBotao();
            });

            // Função para fechar o popup de comentários
            function fecharPopup() {
                document.getElementById('comentariosPopup').classList.add('hidden');
            }
        </script>
    </body>
</html>
