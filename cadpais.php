<?php
include 'conexao2.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $nome = $_POST["firstname"];
    $dataNascimento = $_POST["dataNascimento"];
    $celular = $_POST["number"];
    $responsavel = $_POST["responsavelAluno"];
    $genero = $_POST["genero"];

    // Insere os dados no banco de dados usando prepared statements
    $stmt = $conexao->prepare("INSERT INTO alunos (nome, datanasc, celular, responsavel, genero) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $dataNascimento, $celular, $responsavel, $genero);

    // Executa o statement
    if ($stmt->execute()) {
        // Exibe uma mensagem de sucesso
        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        // Redireciona para a página específica
        header("Location: cadpais2.php");
        exit(); // Encerra o script para garantir que o redirecionamento seja feito corretamente
    } else {
        // Exibe uma mensagem de erro
        echo "<script>alert('Erro ao cadastrar!');</script>";
    }

    // Fecha o statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Cadastro2.css">
    <style>
       /* Estilos para a frase */
       .cadastro-frase {
            font-size: 35px;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            color: WHITE; /* Define a cor do texto */
        }

        /* Estilos para a imagem */
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 20%;
            margin-top: -25px;
            margin-bottom: -50px;
        }

        /* Adicione o CSS aqui */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500&family=Open+Sans:wght@300;400;500;600&display=swap');

        * {
            padding: 8;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Tahoma', sans-serif;
        }

        body {
            width: 100%;
            height: 100vh;
            background-image: url("./imagens/111.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Alteração para empilhar os elementos verticalmente */
        }

        .content {
            text-align: center;
            margin-bottom: -50px;
        }

        form {
            width: 40%;
            max-width: 330px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px; /* Reduzi a margem para ficar mais próximo do conteúdo acima */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: none;
            color: #44277D;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        select {
            width: calc(100% - 20px); /* Ajuste o tamanho conforme necessário */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #44277D;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 750px) {
            * {
                padding: 10;
                margin: 0;
                box-sizing: border-box;
                font-family: 'Inter', sans-serif;
            }

            form {
                width: 90%;
            }
        }

        #confirmacao {
            position: fixed;
            top: 50%;
            left: 50%;
            font-family: 'Tahoma', sans-serif; /* Alterado para uma fonte mais profissional */
            font-size: 40px; /* Reduzido o tamanho da fonte para uma aparência mais equilibrada */
            font-weight: normal; /* Removida a negrito */
            transform: translate(-50%, -50%);
            text-align: center;
            display: none; /* Oculta a mensagem de confirmação inicialmente */
            font-weight: bold;
            color: #D9D9D9; /* Define a cor do texto */
            text-shadow: 
                -2px -2px 0 #44277D,  
                2px -2px 0 #44277D,
                -2px 2px 0 #44277D,
                2px 2px 0 #44277D;
        }

        #menu-principal {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        #menu-principal:hover {
            background-color: #0056b3;
        }
    </style>
    <title>Formulário</title>
</head>

<body>
    <div class="content">
        <p class="cadastro-frase">Atualize o cadastro do seu filho</p>
        <a href="#">
            <img class="cadastro-imagem" src="./imagens/logo sem fundo1.png" alt="Descrição da imagem">
        </a>
    </div>

    <form id="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="firstname">Nome</label>
        <input id="firstname" type="text" name="firstname" placeholder="Digite..." required>

        <label for="dataNascimento">Data de Nascimento</label>
        <input id="dataNascimento" type="date" name="dataNascimento" required>

        <label for="number">Celular</label>
        <input id="number" type="tel" name="number" placeholder="(xx) xxxx-xxxx" required style="width: calc(100% - 20px);">

        <label for="responsavelAluno">Responsável pelo aluno</label>
        <input id="responsavelAluno" type="text" name="responsavelAluno" placeholder="Digite o nome do cliente" required>

        <label for="genero">Gênero</label>
        <select id="genero" name="genero" required>
            <option value="" disabled selected>Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
        </select>

        <input type="submit" value="Salvar">
    </form>

    <div id="confirmacao"></div>

    <script>
    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch(this.action, {
            method: this.method,
            body: formData
        })
        .then(response => {
            if (response.ok) {
                document.getElementById('confirmacao').textContent = 'Espere um momento!';
                document.getElementById('confirmacao').style.display = 'block'; // Exibe a mensagem de confirmação
                this.reset();
                // Redireciona para a página após 3 segundos
                setTimeout(function() {
                    window.location.href = 'cadpais2.php';
                }, 3000);
            } else {
                document.getElementById('confirmacao').textContent = 'Erro ao cadastrar!';
                document.getElementById('confirmacao').style.display = 'block'; // Exibe a mensagem de erro
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('confirmacao').textContent = 'Erro ao cadastrar!';
            document.getElementById('confirmacao').style.display = 'block'; // Exibe a mensagem de erro
        });
    });
</script>

</body>

</html>