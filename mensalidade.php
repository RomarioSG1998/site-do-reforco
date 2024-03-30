<?php 

include('conexao2.php');
include('admin.php');
include('protect.php');

// Verificar se o formulário de cadastro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aluno']) && isset($_POST['pagador']) && !empty($_POST['id_aluno']) && !empty($_POST['pagador'])) {
    // Processar os dados do formulário
    $id_aluno = $_POST['id_aluno'];
    $pagador = $_POST['pagador'];

    // Estabelecer conexão
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verificar se houve erro na conexão
    if($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Preparar a consulta SQL para inserir dados
    $query_insercao = "INSERT INTO meses (id_aluno, pagador) VALUES (?, ?)";
    $stmt = $conexao->prepare($query_insercao);

    // Verificar se a preparação da consulta foi bem-sucedida
    if ($stmt) {
        // Vincular os parâmetros da consulta preparada
        $stmt->bind_param("ss", $id_aluno, $pagador);
        
        // Executar a consulta preparada
        $resultado_insercao = $stmt->execute();
        
        // Verificar se a execução da consulta foi bem-sucedida
        if ($resultado_insercao) {
            echo "<p>Dados cadastrados com sucesso!</p>";
            
            // Redirecionar para a página específica após 3 segundos
            header("refresh:3;url=mensalidade.php");
            exit(); // Encerra o script para garantir que o redirecionamento seja feito corretamente
        } else {
            echo "<p>Ocorreu um erro ao cadastrar os dados.</p>";
        }
    } else {
        // Se a preparação da consulta falhar, exibir mensagem de erro
        echo "<p>Ocorreu um erro ao preparar a consulta.</p>";
    }

    // Fechar conexão
    $conexao->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ra'])) {
    // Processamento da exclusão
    $ra = $_POST['ra'];

    // Estabelecer conexão
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verificar se houve erro na conexão
    if($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Excluir o registro
    $query_exclusao = "DELETE FROM meses WHERE ra = ?";
    $stmt = $conexao->prepare($query_exclusao);
    $stmt->bind_param("s", $ra);
    $resultado_exclusao = $stmt->execute();

    if ($resultado_exclusao) {
        echo "<p>Registro excluído com sucesso.</p>";
    } else {
        echo "<p>Ocorreu um erro ao excluir o registro.</p>";
    }

    // Fechar conexão
    $conexao->close();
}

// Consultar os alunos cadastrados na tabela alunos
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

$query_alunos = "SELECT ra, nome FROM alunos";
$resultado_alunos = $conexao->query($query_alunos);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Pagamentos</title>
    <style>
        body {
            font-family: "Tahoma", Times, serif;
            background-image: url("./imagens/111.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .cadastro-frase {
            font-size: 25px;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
            color: white;
        }

        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 200px;
            margin-top: -25px;
            margin-bottom: 20px;
        }

        h1 {
            text-align: center;
            color: #44277D;
            margin-top: 20px;
            text-shadow: -2px -2px 0 white, 2px -2px 0 white, -2px 2px 0 white, 2px 2px 0 white;
        }

        form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            border: 5px solid #BF7BE8;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        form h1 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #44277D;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #44277D;
            border-radius: 15px;
        }

        input[type="submit"] {
            background-color: #44277D;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 15px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #836fff;
        }

        .search2 {
            margin-top: 30px;
            text-align: center;
        }

        .search2 h1 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .search-btn {
            padding: 12px 0;
            background-color: #44277D;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 40%;
            font-size: 16px;
        }

        .search-btn:hover {
            background-color: #836fff;
        }

        .print-icon {
            display: inline-block;
            color: #44277D;
            text-decoration: none;
            margin-top: 20px;
        }

        .print-icon i {
            font-size: 20px;
        }

        .print-icon:hover {
            color: #836fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #44277D;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #44277D;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .delete-button {
            text-align: center;
            font-size: 14px;
            color: red;
            cursor: pointer;
            text-transform: uppercase;
        }

        .delete-button:hover {
            background-color: #ff6666;
        }

        @media only screen and (max-width: 750px) {
            .cadastro-frase {
                font-size: 20px;
                margin-top: 20px;
            }

            .cadastro-imagem {
                max-width: 100px;
                margin-top: -25px;
            }

            form {
                padding: 10px;
                width: 100%;
            max-width: 300px;
            }

            input[type="text"],
            select {
                padding: 8px;
                margin-bottom: 15px;
            }

            input[type="submit"] {
                padding: 10px 0;
                font-size: 14px;
            }

            .search2 h1 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .search-input {
                padding: 8px;
                width: 70%;
            }

            .search-btn {
                padding: 10px 0;
                font-size: 14px;
            }

            table {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <p class="cadastro-frase">CADASTRO DO RESPONSÁVEL:</p>
        <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
            <img class="cadastro-imagem" src="./imagens/logo sem fundo1.png" alt="Descrição da imagem">
        </a>
    </div>

    <!-- Formulário de Cadastro -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h1>CADASTRE</h1>
        <label for="id_aluno">ALUNO:</label>
        <select id="id_aluno" name="id_aluno">
            <?php
            while ($row = $resultado_alunos->fetch_assoc()) {
                echo "<option value='" . $row['ra'] . "'>" . $row['nome'] . "</option>";
            }
            ?>
        </select><br><br>
        <label for="pagador">PAI/RESPONSÁVEL:</label>
        <input type="text" id="pagador" name="pagador"><br><br>
        <input type="submit" value="Cadastrar">
    </form>

    <!-- Pesquisa e botões de impressão -->
    <div class="search2">
        <form method="GET" action="">
            <h1>PESQUISE</h1>
            <input type="hidden" name="ra" value="<?php echo isset($_GET['ra']) ? $_GET['ra'] : ''; ?>">
            <input type="text" id="search" name="search" class="search-input" placeholder="Buscar por RA ou nome do responsável">
            <button type="submit" class="search-btn">Buscar</button>
        </form>
        <a id="download-link" download="alunos.pdf"></a>
        <a href="#" onclick="generatePDF()" class="print-icon"><i class="fas fa-print"></i></a>
    </div>

    <!-- Tabela de Pagamentos -->
    <div class="container">
        <h1>Tabela de pagamentos mensais</h1>
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $query = "SELECT * FROM meses WHERE id_aluno LIKE '%$search%' OR pagador LIKE '%$search%'";
        $resultado = $conexao->query($query);

        if ($resultado->num_rows > 0) {
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
                    <th>Obs.</th>
                    <th>Ação</th>
                </tr>";

            while ($linha = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td><a href='alterarpag.php?ra=" . $linha['ra'] . "&id_aluno=" . $linha['id_aluno'] . "&janeiro=" . $linha['janeiro'] . "&fevereiro=" . $linha['fevereiro'] . "&marco=" . $linha['marco'] . "&abril=" . $linha['abril'] . "&maio=" . $linha['maio'] . "&junho=" . $linha['junho'] . "&julho=" . $linha['julho'] . "&agosto=" . $linha['agosto'] . "&setembro=" . $linha['setembro'] . "&outubro=" . $linha['outubro'] . "&novembro=" . $linha['novembro'] . "&dezembro=" . $linha['dezembro'] . "&obs=" . $linha['obs'] . "'>" . $linha['ra'] . "</a></td>
                    <td><a href='modcadastro.php?id_aluno=" . $linha['id_aluno'] . "&search=" . urlencode($linha['id_aluno']) . "'>" . $linha['id_aluno'] . "</a></td>
                    <td>" . $linha['pagador'] . "</td>";

                foreach ($linha as $campo => $valor) {
                    if ($campo != 'ra' && $campo != 'id_aluno' && $campo != 'pagador') {
                        if ($campo != 'obs') {
                            $cor = ($valor == "0001-01-01 00:00:01" || $valor == "0001-01-01 00:00:00") ? "#ff9999" : ($valor == "0001-11-30 00:00:00" ? "#ff0000" : "#99cc99");
                            echo "<td style='background-color: $cor;'>" . date('Y-m-d H:i:s', strtotime($valor)) . "</td>";
                        } else {
                            $cor = ($valor != "") ? "yellow" : "";
                            echo "<td style='background-color: $cor;'>" . $valor . "</td>";
                        }
                    }
                }
                echo "<td class='delete-button' onclick='deleteRow(this)'>Delete</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "Não foram encontrados resultados na tabela.";
        }

        $conexao->close();
        ?>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const ra = params.get('ra');
        if (ra) {
            document.getElementById('search').value = ra;
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            var ra = row.getElementsByTagName("td")[0].innerText;
            if (confirm("Tem certeza que deseja excluir o registro com RA " + ra + "?")) {
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>";
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "ra";
                input.value = ra;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>

</html>
