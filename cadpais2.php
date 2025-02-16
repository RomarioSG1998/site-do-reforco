<?php 
header('Content-Type: text/html; charset=utf-8');

include('conexao2.php');

// Definir uma variável para verificar se o formulário foi enviado com sucesso
$formulario_enviado = false;

// Verificar se o formulário de cadastro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aluno']) && isset($_POST['pagador']) && !empty($_POST['id_aluno']) && !empty($_POST['pagador'])) {
    // Processar os dados do formulário
    $id_aluno = $_POST['id_aluno'];
    $pagador = $_POST['pagador'];

    // Estabelecer conexão
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);
    $conexao->set_charset("utf8"); // Definir charset para UTF-8

    // Verificar se houve erro na conexão
    if($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Verificar se já existe um registro com o mesmo aluno e responsável
    $query_verificacao = "SELECT * FROM meses WHERE id_aluno = ? AND pagador = ?";
    $stmt_verificacao = $conexao->prepare($query_verificacao);
    $stmt_verificacao->bind_param("ss", $id_aluno, $pagador);
    $stmt_verificacao->execute();
    $resultado_verificacao = $stmt_verificacao->get_result();

    if ($resultado_verificacao->num_rows > 0) {
        echo "<p style='color: red; text-align: center; font-weight: bold; display: flex; justify-content: center; align-items: center; height: 100vh;'>⚠️ Sua matrícula já foi efetuada, não precisa repetir o processo. Vocé já finalizou. obrigado! ⚠️</p>";
    } else {
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
                // Definir a variável para indicar que o formulário foi enviado com sucesso
                $formulario_enviado = true;
            } else {
                echo "<p>Ocorreu um erro ao cadastrar os dados.</p>";
            }
        } else {
            // Se a preparação da consulta falhar, exibir mensagem de erro
            echo "<p>Ocorreu um erro ao preparar a consulta.</p>";
        }
    }

    // Fechar a declaração de verificação
    $stmt_verificacao->close();

    // Fechar conexão
    $conexao->close();
} 

// Consultar os alunos cadastrados na tabela alunos
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);
$conexao->set_charset("utf8"); // Definir charset para UTF-8

$query_alunos = "SELECT ra, nome FROM alunos";
$resultado_alunos = $conexao->query($query_alunos);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visualização de Pagamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Tahoma", Times, serif;
            background-color: #D9D9D9;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            margin: 0; /* Adicionado para remover margens padrão */
            padding: 0; /* Adicionado para remover margens padrão */
        }

        .cadastro-frase {
            font-size: 15px;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            text-align: center;
            margin-top: 90px;
            margin-bottom: 30px;
            color: #44277D; /* Define a cor do texto */
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

        select {
            width: calc(100% - 5px); /* Definindo a largura com base no cálculo */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #44277D;
            border-radius: 15px;
        }

        input[type="text"] {
            width: calc(100% - 20px); /* Definindo a largura com base no cálculo */
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
        h3{
            text-align: center;
            font-size: 12px;
            color:rgb(241, 40, 14);
            margin-top: 20px;
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
        #animatedGif {
            width: 200px; /* Defina a largura desejada */
            height: auto; /* Mantenha a proporção original */
            display: block; /* Exibe a imagem como um bloco */
            margin: 0 auto; /* Centraliza a imagem horizontalmente */
            
        }

        /* Estilos para telas de celular */
        @media (max-width: 767px) {
            .cadastro-frase {
                font-size: 15px;
                margin-top: 50px;
                margin-bottom: 20px;
            }

            .cadastro-imagem {
                max-width: 30%;
                margin-bottom: 5px;
                margin-top: -20px;
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
                margin-top: -8px; /* Adicionado para mover o formulário um pouco mais para cima */
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
    <p class="cadastro-frase">Atenção:<br> Agora você vai confirmar as informações <br> que você acabou de cadastrar, o nome do <br> aluno e o responsável.</p>
    <h3>Observação: os nomes estão em ordem alfabética</h3>
    <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
        </a>
</div>

<!-- Formulário de Cadastro -->
<form id="cadastroForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h1>Selecione</h1>
    <label for="id_aluno">ALUNO:</label>
    <select id="id_aluno" name="id_aluno" onchange="atualizarPagador(this.value)">
        <?php
        // Definir charset para UTF-8
        $conexao->set_charset("utf8");

        // Consultar os alunos cadastrados na tabela alunos em ordem alfabética
        $query_alunos = "SELECT ra, nome, responsavel FROM alunos ORDER BY nome ASC";
        $resultado_alunos = $conexao->query($query_alunos);

        // Criar array de mapeamento aluno-responsavel para JavaScript
        $mapeamento = array();
        while($row = $resultado_alunos->fetch_assoc()) {
            echo "<option value='" . $row['ra'] . "'>" . $row['nome'] . "</option>";
            $mapeamento[$row['ra']] = $row['responsavel'];
        }
        ?>
    </select><br><br>
    
    <label for="pagador">PAI/RESPONSÁVEL:</label>
    <select id="pagador" name="pagador">
        <?php
        // Consultar responsáveis únicos
        $query_responsaveis = "SELECT DISTINCT responsavel FROM alunos WHERE responsavel IS NOT NULL ORDER BY responsavel ASC";
        $resultado_responsaveis = $conexao->query($query_responsaveis);
        
        while($row = $resultado_responsaveis->fetch_assoc()) {
            echo "<option value='" . $row['responsavel'] . "'>" . $row['responsavel'] . "</option>";
        }
        ?>
    </select>

    <br><br>
    <input type="submit" value="Confirmar" class="submit-button">
</form>

<script>
const mapeamentoResponsaveis = <?php echo json_encode($mapeamento); ?>;

function atualizarPagador(ra) {
    const pagadorSelect = document.getElementById('pagador');
    const responsavel = mapeamentoResponsaveis[ra];
    
    // Encontrar e selecionar a opção correta no dropdown
    for(let i = 0; i < pagadorSelect.options.length; i++) {
        if(pagadorSelect.options[i].value === responsavel) {
            pagadorSelect.selectedIndex = i;
            break;
        }
    }
}

// Preencher inicialmente com o primeiro aluno selecionado
window.onload = function() {
    const selectAluno = document.getElementById('id_aluno');
    if(selectAluno.value) {
        atualizarPagador(selectAluno.value);
    }
}
</script>

<!-- Container para a animação e mensagem de sucesso -->
<div id="animationContainer" style="display: none; text-align: center;">
    <p id="successMessage"></p>
    <img id="animatedGif" src="https://th.bing.com/th/id/R.da20257a26b27784753ab87817040061?rik=vjPnG%2fx9s77Bqg&pid=ImgRaw&r=0.gif" alt="Sua animação">
</div>

<script>
    // Função para simular a digitação do texto
    function typeWriter(messageElement, message, speed) {
        let i = 0;

        function type() {
            if (i < message.length) {
                messageElement.innerHTML += message.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }

        type();
    }

    // Função para mostrar a animação de sucesso
    function showSuccessAnimation() {
        const successMessageElement = document.getElementById("successMessage");
        const successMessage = "Obrigado! Dados atualizados com sucesso!";
        const speed = 80; // Velocidade da digitação em milissegundos

        typeWriter(successMessageElement, successMessage, speed);

        document.getElementById("animationContainer").style.display = "flex";
        document.getElementById("animationContainer").style.flexDirection = "column";
        document.getElementById("animationContainer").style.justifyContent = "center";
        document.getElementById("animationContainer").style.height = "100vh"; // Centraliza verticalmente
        document.getElementById("cadastroForm").style.display = "none"; // Oculta o formulário

        // Ocultar todos os outros elementos da página
        const contentElements = document.querySelectorAll("body > *:not(#animationContainer)");
        contentElements.forEach(element => {
            element.style.display = "none";
        });
    }

    // Chama a função para mostrar a animação após o envio do formulário
    <?php if ($formulario_enviado) { ?>
        showSuccessAnimation();
    <?php } ?>
</script>

</body>
</html>
