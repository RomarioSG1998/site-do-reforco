<?php

include('protect.php');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Cadastro2.css">
    <style>
        /* Adicione o CSS aqui */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500&family=Open+Sans:wght@300;400;500;600&display=swap');

        * {
            padding: 8;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ca1cb3de;
        }

        form {
            width: 90%;
            max-width: 400px; /* Limitar a largura do formulário */
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px; /* Ajuste do posicionamento */
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Adicione regras de mídia para telas menores */
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

        /* Adicione o estilo para a mensagem de confirmação */
        #confirmacao {
            margin-top: 10px;
            text-align: center;
        }

        /* Estilo para o botão do menu principal */
        #menu-principal {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px; /* Ajuste do posicionamento */
        }

        #menu-principal:hover {
            background-color: #0056b3;
        }
    </style>
    <title>Formulário</title>
</head>

<body>
    <form action="./conexao.php" method="POST">
        <label for="firstname">Nome</label>
        <input id="firstname" type="text" name="firstname" placeholder="Digite..." required>

        <label for="dataNascimento">Data de Nascimento</label>
        <input id="dataNascimento" type="date" name="dataNascimento" required>

        <label for="number">Celular</label>
        <input id="number" type="tel" name="number" placeholder="(xx) xxxx-xxxx" required>

        <label for="responsavelAluno">Responsável pelo aluno</label>
        <input id="responsavelAluno" type="text" name="responsavelAluno" placeholder="Digite o nome do pagador" required>

        <label for="genero">Gênero</label>
        <select id="genero" name="genero" required>
            <option value="" disabled selected>Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
        </select>

        <label for="turma">Turma</label>
        <select id="turma" name="turma" required>
            <option value="" disabled selected>Selecione a turma</option>
            <option value="Turma 1">1</option>
            <option value="Turma 2">2</option>
            <option value="Turma 3">3</option>
            <option value="Turma 4">4</option>
            <option value="Turma 5">5</option>
            <option value="Turma 6">6</option>
        </select>

        <input type="submit" value="Salvar">
        <button id="menu-principal" onclick="location.href='../paginasphp/painel.php';">Menu Principal</button>
    </form>


    <!-- Div para exibir a mensagem de confirmação -->
    <div id="confirmacao"></div>

    <script>
        // Verifica se o formulário foi submetido e exibe a mensagem de confirmação
        document.querySelector('form').addEventListener('submit', function () {
            document.getElementById('confirmacao').textContent = 'Cadastro salvo com sucesso!';
        });
    </script>
</body>

</html>
