<?php
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";

// Conectar ao banco de dados
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verificar se a conexão foi bem-sucedida
if ($conexao->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Consulta SQL para obter contagem de notas
$query = "SELECT ns, COUNT(*) AS total FROM avaliacao GROUP BY ns";
$resultado = $conexao->query($query);

// Array para armazenar os dados do gráfico
$dados_grafico = array();
// Adicionar cabeçalho
$dados_grafico[] = ['Nota', 'Total'];
// Preencher os dados do gráfico com os resultados da consulta
while ($linha = $resultado->fetch_assoc()) {
    $dados_grafico[] = [$linha['ns'], (int)$linha['total']];
}

// Consulta SQL para obter o total de pessoas que participaram da pesquisa
$query_total_pessoas = "SELECT COUNT(DISTINCT id) AS total_pessoas FROM avaliacao";
$resultado_total_pessoas = $conexao->query($query_total_pessoas);
$linha_total_pessoas = $resultado_total_pessoas->fetch_assoc();
$total_pessoas = $linha_total_pessoas['total_pessoas'] ?? 0; // Definir como 0 se não houver resultados

// Consulta SQL para obter os comentários e datas
$query_comentarios = "SELECT id, comente, data, ns FROM avaliacao";
$resultado_comentarios = $conexao->query($query_comentarios);

// Array para armazenar os comentários e datas
$comentarios = array();
while ($linha_comentario = $resultado_comentarios->fetch_assoc()) {
    // Adiciona quebra de linha a cada 50 caracteres para comentários longos
    $comentario_formatado = wordwrap($linha_comentario['comente'], 20 , "\n", true);
    $linha_comentario['comente'] = $comentario_formatado;
    $comentarios[] = $linha_comentario;
}

// Processar a exclusão do comentário, se houver solicitação POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["excluir_comentario"])) {
    $id_comentario_excluir = $_POST["excluir_comentario"];
    // Preparar e executar a consulta SQL para excluir o comentário do banco de dados
    $query_excluir_comentario = "DELETE FROM avaliacao WHERE id = ?";
    $stmt = $conexao->prepare($query_excluir_comentario);
    $stmt->bind_param("i", $id_comentario_excluir);
    if ($stmt->execute()) {
        echo "Comentário com ID $id_comentario_excluir excluído com sucesso!";
    } else {
        echo "Falha ao excluir o comentário com ID $id_comentario_excluir: " . $conexao->error;
    }
    $stmt->close();
}

// Fechar a conexão com o banco de dados
$conexao->close();
?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <style>
        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            max-width: 80%; /* Define a largura máxima do popup */
            max-height: 80%; /* Define a altura máxima do popup */
            overflow-y: auto; /* Adiciona uma barra de rolagem vertical caso o conteúdo exceda a altura máxima */
        }

        #popup h2 {
            margin-top: 20px; /* Adiciona espaço superior ao título */
            text-align: center; /* Centraliza o título */
        }

        #popup ul {
            padding: 0;
            margin-top: 10px;
            list-style-type: none;
        }

        #popup .red {
            color: red;
        }

        #popup li {
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        #popup li:last-child {
            border-bottom: none;
        }

        #fechar-popup {
            position: absolute;
            top: 10px; /* Posiciona o botão 10px abaixo do topo */
            right: 10px; /* Posiciona o botão 10px à direita */
            transform: translate(-50%, 0);
            cursor: pointer;
            background-color: green;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
        }

        #fechar-popup:hover {
            background-color: darkgreen;
        }

        /* Media query para telas de até 750 pixels de largura */
        @media only screen and (max-width: 750px) {
            #popup {
                width: 90%; /* Reduz a largura do popup */
                height: 90%; /* Reduz a altura do popup */
            }
        }
    </style>
    <script type="text/javascript">
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($dados_grafico); ?>);

            var options = {
                title: 'Distribuição das Notas na Avaliação',
                is3D: true,
                legend: {position: 'bottom'}, // Adicionando legenda na parte inferior
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }

        // Função para excluir um comentário
        function excluirComentario(id) {
            // Aqui você pode enviar uma solicitação AJAX para excluir o comentário do banco de dados
            // Neste exemplo, vamos apenas remover o comentário da lista
            $('#comentario_' + id).remove();
            
            // Enviar o ID do comentário a ser excluído para o PHP
            $.post(window.location.href, {excluir_comentario: id});
        }

        function exibirComentarios() {
    var comentarios = <?php echo json_encode($comentarios); ?>;
    var popupContent = '<div id="popup"><span id="fechar-popup" onclick="fecharPopup()">Fechar</span><h2>Comentários e Datas</h2><ul>';
    comentarios.forEach(function (item, index) {
        var corClasse = (item.ns <= 2) ? 'red' : '';
        popupContent += '<li id="comentario_' + item.id + '" class="' + corClasse + '"><strong>Data:</strong> ' + item.data + '<br><strong>Comentário:</strong> <br>' + item.comente;
        if (item.ns) { // Verifica se há um valor em ns
            popupContent += ' (Estrelas: ' + item.ns + ')'; // Adiciona a nota ao comentário
        }
        popupContent += ' <span class="delete-icon" onclick="excluirComentario(' + item.id + ')"><i class="fas fa-trash-alt" style="color: blue;"></i></span></li>';
    });
    popupContent += '</ul></div>';
    $('body').append(popupContent);
    $('#popup').fadeIn();
}


        // Função para fechar o popup
        function fecharPopup() {
            $('#popup').fadeOut();
            $('#popup').remove();
        }
    </script>
</head>
<body>
<div id="piechart_3d" style="width: 300px; height: 300px;"></div>
<p>O número inteiro representa a quantidade de estrelas (em uma escala de 1 a 5 estrelas), <br> a porcentagem representa a quantidade de pessoas que escolheram esse número.<br>Total de pessoas que participaram da pesquisa: <?php echo $total_pessoas; ?></p> <!-- Adicionando total de pessoas -->
<!-- Botão de popup -->
<button onclick="exibirComentarios()">Exibir Comentários</button>

</body>
</html>
