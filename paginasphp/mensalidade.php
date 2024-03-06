<!DOCTYPE html>
<html>
<head>
    <title>Visualização de Pagamentos</title>
    <style>
        #pagamentos-link {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    <?php
    $hostname = "localhost";
    $bancodedados = "sistemadoreforco";
    $usuario = "root";
    $senha = "";

    // Estabelecer conexão
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verificar se houve erro na conexão
    if($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Consultar os dados da tabela meses
    $query = "SELECT * FROM meses";
    $resultado = $conexao->query($query);

    // Verificar se a consulta retornou resultados
    if ($resultado->num_rows > 0) {
        // Início da tabela HTML
        echo "<table border='1'>
                <tr>
                    <th>RA</th>
                    <th>Pagador</th>
                    <th>Janeiro</th>
                    <th>Fevereiro</th>
                    <th>Março</th>
                    <th>Abril</th>
                    <th>Maio</th>
                    <th>Junho</th>
                    <th>Julho</th>
                    <th>Agosto</th>
                    <th>Setembro</th>
                    <th>Outubro</th>
                    <th>Novembro</th>
                    <th>Dezembro</th>
                </tr>";

        // Loop através dos resultados da consulta
        while($linha = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td><a href='alterarpag.php?ra=" . $linha['ra'] . "'>" . $linha['ra'] . "</a></td>
                    <td>" . $linha['pagador'] . "</td>";

            // Iterar através dos campos de mês
            foreach ($linha as $campo => $valor) {
                // Verificar se o valor é uma data válida
                if ($campo != 'ra' && $campo != 'pagador') {
                    $cor = ($valor == "0000-00-00" || $valor == "0001-01-01") ? "red" : "green";
                    echo "<td style='background-color: $cor;'>$valor</td>";
                }
            }

            echo "</tr>";
        }

        // Fim da tabela HTML
        echo "</table>";
    } else {
        echo "Não foram encontrados resultados na tabela.";
    }

    // Fechar conexão
    $conexao->close();
    ?>

    <!-- Link Pagamentos -->
    <a id="pagamentos-link" href="painel.php">Home</a>
</body>
</html>
