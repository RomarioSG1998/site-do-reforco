<?php
    include('protect.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tahoma', sans-serif; /* Adiciona a fonte Tahoma a todos os elementos */
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url("../imagens/bg-signin1.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para a saudação de boas-vindas */
        h1 {
            color: #680246;
            background-color: #f0f0f0;
            padding: 0px;
            border-radius: 0px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            animation: pulse 1s infinite alternate;
            margin: 0%;
            background-color: transparent;
            text-align: center;
            font-weight: bold; /* Adiciona negrito */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 10px;
            border-radius: 4%;
            box-shadow: 3px 3px 1px 0px rgba(0, 0, 0, 0.4);
            max-width: 350px;
            gap: 20px;
            margin: auto;
            margin-top: 400px;
        }

        .btn,
        .logout-button {
            padding: 10px 20px;
            background-color: #4caf50;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            width: 200px;
            border-radius: 15px;
            
        }

        .btn a,
        .logout-button a {
            color: white;
            text-decoration: none;
        }


        .logout-button {
            background-color: #dc3545;
            max-width: 100px;
        }

        img {
            max-width: 30%;
            height: 30%;
            display: block;
            margin: 10px auto 0;
            margin-bottom: -300px;
        }

        .popup {
    position: absolute;
    display: inline-block;
}

.popup-content {
    visibility: hidden;
    width: 160px;
    background-color: #44277D;
    color: white;
    text-align: center;
    border-radius: 16px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    top: 50%; /* Centraliza verticalmente */
    left: 80%; /* Centraliza horizontalmente */
    transform: translate(-50%, -50%); /* Move o popup para o centro */
    transition: visibility 0.3s ease;
}


        .popup:hover .popup-content {
            visibility: visible;
        }

        .popup-content a {
            display: block;
            padding: 8px 0;
            color: #fff;
            text-decoration: none;
        }

        .popup-content a:hover {
            color: purple;
        }

        @media screen and (max-width: 750px) {
            body {
                justify-content: flex-start;
                align-items: flex-start;
                padding: 20px;
            }

            .container {
                margin-top: 150px;
                max-width: 282px;
            }

            img {
                max-width: 50%;
                height: auto;
                margin-bottom: -100px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bem-vindo/a, <?php echo $_SESSION['nome']; ?>.</h1>
        <img src="../imagens/logo sem fundo2.png" alt="Descrição da imagem">
        <h1 id="logado"></h1>
        <div class="container">
            <div>
                <button onclick="togglePopup('popup')" class="btn">Cadastrar/Alterar</button>
                <div id="popup" class="popup">
                    <div class="popup-content">
                        <a href="cadastro2.php">Cadastrar</a>
                        <a href="modcadastro.php">Alterar</a>
                    </div>
                </div>
            </div>
            <div class="btn">
                <a href="./pageadm.php?nome=<?php echo urlencode($_SESSION['nome']); ?>" style="text-decoration: none;">Admin</a>
            </div>
            <div class="btn">
                <a href="./Dashboard.php" style="text-decoration: none;">Ver análises</a>
            </div>

            <div>
                <a href="logout.php" class="logout-button">SAIR</a>
            </div>
        </div>
    </header>

    <script>
        function togglePopup(popupId) {
            var popup = document.getElementById(popupId);
            var popupContent = popup.querySelector('.popup-content');
            popupContent.style.visibility = (popupContent.style.visibility === "visible") ? "hidden" : "visible";
        }

        document.addEventListener('click', function(event) {
            var popups = document.querySelectorAll('.popup');
            popups.forEach(function(popup) {
                if (!popup.contains(event.target)) {
                    popup.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
