<?php 

include('conexao2.php');


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

    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capturar os valores dos campos do formulário
        $id_aluno = $_POST['id_aluno'];
        $pagador = $_POST['pagador'];
        
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body {
        font-family: "Tahoma", Times, serif;
        background-image: url("./imagens/111.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        margin: 0; /* Adicionado para remover margens padrão */
        padding: 0; /* Adicionado para remover margens padrão */
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

    .cadastro-imagem {
        display: block;
        margin: 0 auto;
        max-width: 10%;
        margin-top: -15px;
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
    form h1{
        font-size: 15px;
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
    .search2 {
        margin-top: 20px;
        text-align: center;
    }
    .search2 h1{
        font-size: 15px;
        text-align: center;
    }

    .search-input {
        padding: 8px;
        width: 60%;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 5px;
    }

    .search-btn {
        padding: 8px 15px;
        background-color: #44277D;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-btn:hover {
        background-color: #836fff;
    }

    .print-icon {
        margin-top: 10px;
        display: inline-block;
        color: #44277D;
        text-decoration: none;
    }

    .print-icon i {
        font-size: 20px;
    }

    .print-icon:hover {
        color: #836fff;
    }

    /* Estilos para telas de celular */
    @media (max-width: 767px) {
        .cadastro-frase {
            font-size: 25px;
            margin-top: 50px;
            margin-bottom: 20px;
        }

        .cadastro-imagem {
            max-width: 30%;
            margin-top: 100px;
            margin-bottom: 5px;
        }

        h1 {
            font-size: 18px;
            margin-top: 15px;
            text-shadow: none;
        }

        h2 {
            font-size: 18px;
            margin-top: 15px;
        }

        form {
            width: 70%;
            max-width: 90%;
            padding: 15px;
            border-radius: 10px;
            border: 3px solid #BF7BE8;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        form h1{
            font-size: 14px;
        }

        label {
            color: #44277D;
        }

        input[type="text"],
        select {
            padding: 6px;
            margin-bottom: 8px;
            border: 1px solid #44277D;
            border-radius: 10px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            border-radius: 10px;
        }

        .search-input {
            padding: 6px;
            width: 80%;
            border-radius: 5px;
            margin-right: 5px;
        }

        .search-btn {
            padding: 6px 12px;
            border-radius: 5px;
        }

        .print-icon {
            margin-top: 5px;
            font-size: 16px;
        }
    }
    </style>
</head>
<body>
<div class="content">
    <p class="cadastro-frase">CADASTRO DO RESPONSÁVEL:</p>
    <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
    </a>
</div>

<!-- Formulário de Cadastro -->

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h1>CADASTRE</h1>
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

</body>
</html>
