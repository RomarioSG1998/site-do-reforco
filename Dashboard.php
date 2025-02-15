<?php
include('protect.php');
include('conexao2.php');

// Consulta SQL para buscar dados gerais
$sqlGeral = "SELECT COUNT(ra) as totalAlunos, AVG(TIMESTAMPDIFF(YEAR, datanasc, CURRENT_DATE())) as mediaIdade FROM alunos";
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
        body {
            background-color: #dcdcdc;
            background-size: cover;
            background-position: center;
        }
        .cadastro-frase {
            font-size: 35px;
            font-family: 'arial', sans-serif;
            font-weight: bold;
            text-align:center;
            margin-bottom: 20px;
            color:#44277D;
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
            .cadastro-frase {
            font-size: 20px;
        }
        }

        /* Estilos para o popup */
        #popupComentarios {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none; /* inicialmente oculto */
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
            animation-duration: 2s;
            animation-iteration-count: infinite;
        }

        .aniversario-passado {
            color: red;
        }
        .aniversario-futuro {
            color: green;
        }
        .aniversario-hoje {
            color: orange;
        }

        .legenda {
            font-size: 10px;
        }

        .legenda p {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <p class="cadastro-frase">INFORMAÇÕES IMPORTANTES</p>
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
                <!-- Legenda para aniversariantes -->
                <div class="legenda">
                    <p><span class="aniversario-passado">Vermelho:</span> O dia do aniversário já passou</p>
                    <p><span class="aniversario-futuro">Verde:</span> O dia ainda não chegou</p>
                    <p><span class="aniversario-hoje">Laranja Piscando:</span> O aniversário é exatamente hoje</p>
                </div>
                <ul id="aniversariantes" class="animate__animated animate__bounce">
                    <?php
                    $count = 0;
                    $today = date('Y-m-d');
                    while ($rowAniversariante = $resultAniversariantes->fetch_assoc()) {
                        $aniversario = date('Y') . '-' . date('m-d', strtotime($rowAniversariante['datanasc']));
                        $nome = $rowAniversariante['nome'];
                        $dataFormatada = date('d/m/Y', strtotime($rowAniversariante['datanasc']));

                        if ($aniversario < $today) {
                            $class = 'aniversario-passado';
                        } elseif ($aniversario > $today) {
                            $class = 'aniversario-futuro';
                        } else {
                            $class = 'aniversario-hoje animate__animated animate__flash';
                        }

                        if ($count < 3) {
                            echo "<li class='$class'>$nome - $dataFormatada</li>";
                        } else {
                            echo "<li class='hidden $class'>$nome - $dataFormatada</li>";
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

        <!-- Botão para abrir o popup -->
        <button onclick="exibirPopup()">Abrir Gráfico de Avaliação</button>
        <!-- Div que conterá o conteúdo do popup -->
        <div id="popupContainer"></div>

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

            function mostrarTodosAniversariantes() {
                var aniversariantes = document.querySelectorAll('#aniversariantes li.hidden');
                for (var i = 0; i < aniversariantes.length; i++) {
                    aniversariantes[i].classList.remove('hidden');
                }
            }

            function exibirPopup() {
                // Carregar a página do popup dentro da div popupContainer
                document.getElementById('popupContainer').innerHTML = '<iframe src="pagina_popup.php" style="width: 80%; height: 80vh; border: none;"></iframe>';
            }

            // Função para fechar o popup
            function fecharPopup() {
                // Limpar o conteúdo da div popupContainer para fechar o popup
                document.getElementById('popupContainer').innerHTML = '';
            }
        </script>
    </div>
</body>
</html>