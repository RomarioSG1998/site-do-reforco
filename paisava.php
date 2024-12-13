<?php include('protect.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links para os Pais</title>
    <style>
        /* Reset e estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f9;
            padding: 20px;
        }

        header {
            background-color: #44277D;
            color: #fff;
            width: 100%;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        main {
            max-width: 600px;
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            color: #44277D;
            margin-bottom: 20px;
            font-size: 28px;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .link-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .link-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .link-container button:hover {
            background-color: #0056b3;
        }

        .link-container a {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
    <script>
        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                alert("Link copiado: " + texto);
            }).catch(err => {
                alert("Erro ao copiar o link: " + err);
            });
        }
    </script>
</head>
<body>
    <header>
    </header>
    <main>
        <h1>Bem-vindo!</h1>
        <p>Aqui você pode compartilhar esses links com os pais para que eles avaliem o nosso trabalho ou atualizem o cadastro do estudante.</p>
        <div class="links">
            <div class="link-container">
                <a href="avaliacao.php" target="_blank">Avaliar nosso trabalho</a>
                <button onclick="copiarTexto('http://localhost/sededosaber/avaliacao.php')">Copiar Link</button>
            </div>
            <div class="link-container">
                <a href="cadpais.php" target="_blank">Atualizar cadastro do filho</a>
                <button onclick="copiarTexto('http://localhost/sededosaber/cadpais.php')">Copiar Link</button>
            </div>
        </div>
    </main>
</body>
</html>