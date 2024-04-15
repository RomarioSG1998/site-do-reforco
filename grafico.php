<?php
$hostname = "localhost";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "A7E5zgIppyr";

// Conectar ao banco de dados
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verificar se a conexão foi bem-sucedida
if($conexao->connect_error) {
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
$total_pessoas = $linha_total_pessoas['total_pessoas'];

// Fechar a conexão com o banco de dados
$conexao->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($dados_grafico); ?>);

        var options = {
          title: 'Distribuição de Notas na Avaliação',
          is3D: true,
          legend: { position: 'bottom' }, // Adicionando legenda na parte inferior
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
    <p>Total de pessoas que participaram da pesquisa: <?php echo $total_pessoas; ?></p> <!-- Adicionando total de pessoas -->
  </body>
</html>
