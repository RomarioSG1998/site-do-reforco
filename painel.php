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
            background-image: url("./imagens/bg-signin1.png");
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
            box-shadow: 0 0 0px rgba(0, 0, 0, 0.0);
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
            padding: 5px 5px;
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
            border: 3px  solid #58216d;
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
            max-width: 35%;
            height: 30%;
            display: block;
            margin: 10px auto 0;
            margin-bottom: -300px;
        }

       


        .popup-content {
        visibility: hidden;
        width: 160px;
        background-color: rgba(68, 39, 125, 0.7);
        color: white;
        text-align: center;
        border-radius: 16px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        top: 50%; /* Centraliza verticalmente */
        left: 50%; /* Centraliza horizontalmente */
        transform: translate(-50%, -50%); /* Move o popup para o centro */
        transition: visibility 0.3s ease;
    }

    .popup:hover .popup-content {
        visibility: visible;
    }

    .popup-content a {
        font-family: 'Tahoma', sans-serif;
        font-size:20px;
        display: block; /* Ajustado para block */
        padding: 18px 0;
        color: #fff;
        text-decoration: none;
    }

    .popup-content a:hover {
        color: purple;
    }
    #watermark {
 font-family: 'Tahoma', sans-serif;
 text-align:center;
  position: fixed;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 10px;
  color: rgba(128, 128, 128, 0.3);
  pointer-events: none; /* Permite que o texto de marca d'água não seja clicável */
  z-index: 9999; /* Garante que o texto fique na frente de outros elementos */
}
#watermark2 {
 font-family: 'Tahoma', sans-serif;
 text-align:center;
  position: fixed;
  top: 650px;
  left: 44%;
  transform: none;
  font-size: 15px;
  color: rgba(128, 128, 128, 0.9);
  pointer-events: none; /* Permite que o texto de marca d'água não seja clicável */
  z-index: 9999; /* Garante que o texto fique na frente de outros elementos */
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
            .popup-content {
            width: 200px; 
            }
            #watermark {
 font-family: 'Tahoma', sans-serif;
  position: fixed;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 7px;
  color: rgba(128, 128, 128, 0.3);
  pointer-events: none; /* Permite que o texto de marca d'água não seja clicável */
  z-index: 9999; /* Garante que o texto fique na frente de outros elementos */
}
#watermark2 {
  font-family: 'Tahoma', sans-serif;
  text-align: center;
  position: fixed;
  top: 650px; /* Mantém a posição original */
  left: 25%; /* Mantém a posição original */
  transform: none;
  font-size: 15px;
  color: rgba(128, 128, 128, 0.9);
  pointer-events: none; /* Permite que o texto de marca d'água não seja clicável */
  z-index: 9999; /* Garante que o texto fique na frente de outros elementos */
}

        }
    </style>
</head>
<body>
    <header>
    <div id="watermark">Desenvolvido por Romário, sob o design cuidadoso de Álvaro, <br>e criado em colaboração com o Reforço Sede do Saber.</div>
    <h1 style="font-family: 'Tahoma', sans-serif; font-size: 40px; margin-top: 3%; font-weight: normal; color: #D9D9D9; text-shadow: -2px -2px 0 #44277D, 2px -2px 0 #44277D, -2px 2px 0 #44277D, 2px 2px 0 #44277D;">
    Bem-vindo/a, <span id="nome" style="color: #44277D; text-shadow: none;"></span>.
</h1>
        <img src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
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
                <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>" style="text-decoration: none;">Admin</a>
            </div>
            <div class="btn">
                <a href="./Dashboard.php" style="text-decoration: none;">Ver análises</a>
            </div>

            <div>
                <a href="logout.php" class="logout-button">SAIR</a>
            </div>
        </div>
        <div id="watermark2">Developed by Romário Galdino<br> versão 0.1</div>
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

        const nome = "<?php echo $_SESSION['nome']; ?>"; // Obtém o nome da sessão PHP
const spanNome = document.getElementById('nome');

function typeWriter(text, i, fnCallback) {
    if (i < (text.length)) {
        spanNome.innerHTML = text.substring(0, i+1) + '_';
        setTimeout(function() {
            typeWriter(text, i + 1, fnCallback)
        }, 100);
    } else if (typeof fnCallback == 'function') {
        setTimeout(fnCallback, 700);
    }
}

function startTextAnimation() {
    if (typeof nome !== 'undefined') {
        typeWriter(nome, 0, function() {
            // Remover o caractere de sublinhado no final da animação
            spanNome.innerHTML = spanNome.innerHTML.slice(0, -1);
        });
    }
}

// Iniciar a animação de digitação
startTextAnimation();

    </script>
</body>
</html>
