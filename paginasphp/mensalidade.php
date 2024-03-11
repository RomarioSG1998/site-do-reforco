<?php 
    include('conexao2.php');
    include('admin.php');
    include('protect.php'); 
   

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visualização de Pagamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            position: relative;
        }

        h1 {
            text-align: center;
            color: #6a5acd;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin: 20px auto;
            overflow-x: auto;
        }

        th, td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: left;
            width: 8%; /* Alterado para porcentagem */
            font-size: 12px; /* Alterado para tamanho de fonte menor */
        }

        th {
            background-color: #d2cdf0; /* Tom suave de lilás */
            color: #000;
        }

        tr:nth-child(even) td {
            background-color: #e0e0e0; /* Tom suave de cinza */
        }

        a#pagamentos-link {
            display: block;
            width: fit-content;
            padding: 10px 20px;
            background-color: #6a5acd; /* Lilás mais intenso */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-align: center;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        a#pagamentos-link:hover {
            background-color: #836FFF; /* Lilás mais escuro */
        }

        @media screen and (max-width: 600px) {
            table, th, td {
                font-size: 10px; /* Alterado para tamanho de fonte menor */
                width: auto; /* Alterado para largura automática */
            }
            table {
                max-width: 100%; /* Adicionado tamanho máximo */
            }
        }
    </style>
</head>
<body>
<div class="btn-novo">
                <a href="./pageadm.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">home</a>
            </div>
    <h1>Pagamentos</h1>

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
        echo "<table>
                <tr>
                    <th>RA</th>
                    <th>ID Aluno</th>
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
                    <td><a href='alterarpag.php?ra=" . $linha['ra'] . "&id_aluno=" . $linha['id_aluno'] . "&janeiro=" . $linha['janeiro'] . "&fevereiro=" . $linha['fevereiro'] . "&marco=" . $linha['marco'] . "&abril=" . $linha['abril'] . "&maio=" . $linha['maio'] . "&junho=" . $linha['junho'] . "&julho=" . $linha['julho'] . "&agosto=" . $linha['agosto'] . "&setembro=" . $linha['setembro'] . "&outubro=" . $linha['outubro'] . "&novembro=" . $linha['novembro'] . "&dezembro=" . $linha['dezembro'] . "'>" . $linha['ra'] . "</a></td>
                    <td><a href='modcadastro.php?id_aluno=" . $linha['id_aluno'] . "'>" . $linha['id_aluno'] . "</a></td>
                    <td>" . $linha['pagador'] . "</td>";

            // Iterar através dos campos de mês
            foreach ($linha as $campo => $valor) {
                // Verificar se o valor é uma data válida
                if ($campo != 'ra' && $campo != 'id_aluno' && $campo != 'pagador') {
                    $cor = ($valor == "0001-01-01 00:00:01" || $valor == "0001-01-01 00:00:00") ? "#ff9999" : ($valor == "0001-11-30 00:00:00" ? "#ff0000" : "#99cc99"); /* Tons suaves de vermelho e verde */
                    echo "<td style='background-color: $cor;'>" . date('Y-m-d H:i:s', strtotime($valor)) . "</td>"; // Adicionado o horário
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

</body>
</html>
