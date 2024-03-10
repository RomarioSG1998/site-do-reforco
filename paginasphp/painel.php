<?php

include('protect.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css"/>
    <title>Home</title>
    <style>
        /* Estilos para a saudação de boas-vindas */
        h1 {
            font-family: "Times New Roman", sans-serif; /* Definição da fonte */
    font-family: "Times New Roman", sans-serif; /* Definição da fonte */
    color: #680246; /* Cor do texto */
    background-color: #f0f0f0; /* Cor de fundo */
    padding: 0px; /* Adiciona um espaço interno ao elemento */
    border-radius: 0px; /* Adiciona cantos arredondados */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Adiciona uma sombra sutil */
    animation: pulse 1s infinite alternate; /* Adiciona uma animação chamada "pulse" */
    margin: 0%;
    background-color: transparent;
        }
        p > .logout-button {
            padding: 15px 30px;
            font-size: 18px;
            color: #fff;
            background-color: #dc3545; /* Cor de destaque para o botão "Sair" */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: "Times New Roman", sans-serif;
        }

        p a:hover {
            background-color: #d60586; /* Cor de destaque alterada ao passar o mouse */
        }
        .btn-novo a {
        text-decoration: none;
        color: white;
        }
    </style>
</head>
<body>
    <header> 
        <h1>Bem-vindo/a, <?php echo $_SESSION['nome']; ?>.</h1>
     <!-- Imagem à esquerda -->
     <img src="../imagens/logo sem fundo2.png" alt="Imagem Esquerda" class="image-left">
    <h1 id="logado"></h1>
    <div class="container">
        <button onclick="togglePopup('popup')" class="btn-novo">Cadastrar/Alterar Alunos</button>
        <div id="popup" class="popup">
            <div class="popup-content">
                <a href="cadastro2.php">Cadastrar</a>
                <a href="modcadastro.php">Alterar</a>
            </div>
        </div>

            <div class="btn-novo">
                <a href="./pageadm.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">Admin</a>
            </div>

            <div class="btn-novo">
                <a href="./Dashboard.php">Ver análises</a>
            </div>
        </div>
    </div>

    <!-- Imagem à direita -->
    <img src="../imagens/logo sem fundo2.png" alt="Imagem Direita" class="image-right">
    
     <script>

        function togglePopup(popupId) {
        var popup = document.getElementById(popupId);
        popup.style.display = popup.style.display === "block" ? "none" : "block";
    }
     </script>
    <p>
    <p>
            <a href="logout.php" class="logout-button">Sair</a>
    </p>
    </p>
</body>
</html>