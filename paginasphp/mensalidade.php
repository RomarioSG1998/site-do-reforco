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
    $stmt->bind_param("ss", $id_aluno, $pagador);
    
    // Executar a consulta preparada
    $resultado_insercao = $stmt->execute();

    if ($resultado_insercao) {
        echo "<p>Dados cadastrados com sucesso!</p>";
    } else {
        echo "<p>Ocorreu um erro ao cadastrar os dados.</p>";
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

    // Consultar se o registro existe antes de excluir
    $consulta_existencia = "SELECT * FROM meses WHERE ra = ?";
    $stmt = $conexao->prepare($consulta_existencia);
    $stmt->bind_param("s", $ra);
    $stmt->execute();
    $resultado_existencia = $stmt->get_result();

    if ($resultado_existencia->num_rows > 0) {
        // Excluir o registro
        $query_exclusao = "DELETE FROM meses WHERE ra = ?";
        $stmt = $conexao->prepare($query_exclusao);
        $stmt->bind_param("s", $ra);
        $resultado_exclusao = $stmt->execute();

        if ($resultado_exclusao) {
            // Defina a variável de sessão para indicar que o registro foi excluído com sucesso
            $excluido_com_sucesso = true;
        } else {
            echo "<p>Ocorreu um erro ao excluir o registro.</p>";
        }
    } else {
        echo "<p>O registro não existe.</p>";
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
<html>
<head>
    <title>Visualização de Pagamentos</title>
    <style>
    body {
        font-family: "Times New Roman", Times, serif;
        background-image: url("../imagens/111.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }

    .cadastro-frase {
        font-size: 35px;
        font-family: 'Tahoma', sans-serif;
        font-weight: bold;
        text-align: center;
        margin-top: 90px;
        margin-bottom: 30px;
        color: white; /* Define a cor do texto */
    }

    /* Estilos para a imagem */
    .cadastro-imagem {
        display: block;
        margin: 0 auto;
        max-width: 10%;
        margin-top: -25px;
        margin-bottom: 7px;
    }

    h1 {
        text-align: center;
        color: #44277D;
        margin-top: 20px;
        text-shadow:
            -2px -2px 0 white,
            2px -2px 0 white,
            -2px 2px 0 white,
            2px 2px 0 white;
    }

    h2 {
        text-align: center;
        color: #6a5acd;
        margin-top: 20px;
    }

    form {
        width: 90%; /* Reduzido o máximo de largura para 90% da tela */
        margin: 0 auto;
        max-width: 300px;
        background-color: #fff;
        padding: 20px;
        border-radius: 15px;
        border: 5px solid #BF7BE8;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #44277D;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #44277D;
        border-radius: 15px;
    }

    input[type="submit"] {
        background-color: #44277D;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 15px;
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

    /* Estilos específicos para telas de celular */
    @media only screen and (max-width: 750px) {
        .content {
            padding: 10px;
        }

        .cadastro-frase {
            font-size: 100px;
        }

        .cadastro-imagem {
            max-width: 100%;
        }

        form {
            max-width: none;
        }

        table {
            font-size: 10px;
        }

        th,
        td {
            padding: 5px;
        }
    }
</style>

</head>
<body>
<div class="content">
    <p class="cadastro-frase">CADASTRO DO RESPONSÁVEL:</p>
    <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="../imagens/logo sem fundo1.png" alt="Descrição da imagem">
    </a>
</div>

<!-- Formulário de Cadastro -->

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_aluno">ALUNO:</label>
        <select id="id_aluno" name="id_aluno">
            <?php
            // Iterar através dos resultados da consulta de alunos
            while($row = $resultado_alunos->fetch_assoc()) {
                echo "<option value='" . $row['ra'] . "'>" . $row['nome'] . "</option>"; // Corrigido para 'ra' e 'nome'
            }
            ?>
        </select><br><br>
        <label for="pagador">PAI/RESPONSÁVEL:</label>
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
                    <th>Obs.</th>
                    <th>Ação</th> <!-- Nova coluna para o botão de delete -->
                </tr>";

        // Loop através dos resultados da consulta
        while($linha = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td><a href='alterarpag.php?ra=" . $linha['ra'] . "&id_aluno=" . $linha['id_aluno'] . "&janeiro=" . $linha['janeiro'] . "&fevereiro=" . $linha['fevereiro'] . "&marco=" . $linha['marco'] . "&abril=" . $linha['abril'] . "&maio=" . $linha['maio'] . "&junho=" . $linha['junho'] . "&julho=" . $linha['julho'] . "&agosto=" . $linha['agosto'] . "&setembro=" . $linha['setembro'] . "&outubro=" . $linha['outubro'] . "&novembro=" . $linha['novembro'] . "&dezembro=" . $linha['dezembro'] . "&obs=" . $linha['obs'] . "'>" . $linha['ra'] . "</a></td>
                    <td><a href='modcadastro.php?id_aluno=" . $linha['id_aluno'] . "&search=" . urlencode($linha['id_aluno']) . "'>" . $linha['id_aluno'] . "</a></td>
                    <td>" . $linha['pagador'] . "</td>";

            // Iterar através dos campos de mês
            foreach ($linha as $campo => $valor) {
                // Verificar se o valor é uma data válida
                if ($campo != 'ra' && $campo != 'id_aluno' && $campo != 'pagador') {
                    if ($campo != 'obs') { // Verifica se o campo não é 'obs'
                        $cor = ($valor == "0001-01-01 00:00:01" || $valor == "0001-01-01 00:00:00") ? "#ff9999" : ($valor == "0001-11-30 00:00:00" ? "#ff0000" : "#99cc99"); /* Tons suaves de vermelho e verde */
                        echo "<td style='background-color: $cor;'>" . date('Y-m-d H:i:s', strtotime($valor)) . "</td>"; // Adicionado o horário
                    } else { // Se for 'obs', apenas exibe o valor
                        echo "<td>" . $valor . "</td>";
                    }
                }
            }

            // Botão de delete
            echo "<td><form method='POST' action=''>
                    <input type='hidden' name='ra' value='" . $linha['ra'] . "'>
                    <input type='submit' value='Delete'>
                </form></td>";

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

</body>
</html>
