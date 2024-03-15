<?php 
include('conexao2.php');
include('admin.php');
include('protect.php'); 
include ('registrarAtividade.php');

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se os campos estão definidos e não vazios
    if (isset($_POST['id_aluno']) && isset($_POST['pagador']) && !empty($_POST['id_aluno']) && !empty($_POST['pagador'])) {
        // Processar os dados do formulário
        $id_aluno = $_POST['id_aluno'];
        $pagador = $_POST['pagador'];

        // Aqui você pode inserir os dados na tabela meses
        // Certifique-se de sanitizar os dados para evitar SQL Injection
        // Exemplo: $id_aluno_sanitized = $conexao->real_escape_string($id_aluno);
        // Exemplo: $pagador_sanitized = $conexao->real_escape_string($pagador);

        // Estabelecer conexão
        $hostname = "localhost";
        $bancodedados = "id21964020_sistemadoreforco";
        $usuario = "id21964020_root";
        $senha = "J3anlak#1274";
        $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

        // Verificar se houve erro na conexão
        if($conexao->connect_error) {
            die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
        }

        // Exemplo de inserção na tabela meses (substitua pelos seus dados reais)
        $query_insercao = "INSERT INTO meses (id_aluno, pagador) VALUES ('$id_aluno', '$pagador')";
        $resultado_insercao = $conexao->query($query_insercao);

        if ($resultado_insercao) {
            echo "<p>Dados cadastrados com sucesso!</p>";
        } else {
            echo "<p>Ocorreu um erro ao cadastrar os dados.</p>";
        }

        // Fechar conexão
        $conexao->close();
    } else {
        echo "<p>Por favor, preencha todos os campos do formulário.</p>";
    }
}

// Consultar os alunos cadastrados na tabela alunos
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "id21964020_root";
$senha = "J3anlak#1274";

$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

$query_alunos = "SELECT ra, nome FROM alunos";
$resultado_alunos = $conexao->query($query_alunos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualização de Pagamentos</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #6a5acd;
            margin-top: 20px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #6a5acd;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #836fff;
        }

        /* Estilos para tornar o texto dentro da tabela mais pequeno */
        table {
            font-size: 13px;
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>
<body>
<div class="btn-novo">
    <a href="./pageadm.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img src="../imagens/logo sem fundo.png" alt="Home"  width="50" height="50">
    </a>
</div>

<!-- Formulário de Cadastro -->
<div>
    <h2>Cadastrar Cliente/Responsavel</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_aluno">ID Aluno:</label>
        <select id="id_aluno" name="id_aluno">
            <?php
            // Iterar através dos resultados da consulta de alunos
            while($row = $resultado_alunos->fetch_assoc()) {
                echo "<option value='" . $row['ra'] . "'>" . $row['nome'] . "</option>"; // Corrigido para 'ra' e 'nome'
            }
            ?>
        </select><br><br>
        <label for="pagador">Pai/Responsavel:</label>
        <input type="text" id="pagador" name="pagador"><br><br>
        <input type="submit" value="Cadastrar">
    </form>
</div>


<!-- Tabela de Pagamentos -->
<h1>Tabela de pagamentos mensais</h1>

<?php
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
                    <td><a href='modcadastro.php?id_aluno=" . $linha['id_aluno'] . "&search=" . urlencode($linha['id_aluno']) . "'>" . $linha['id_aluno'] . "</a></td>
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
