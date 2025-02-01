<?php
session_start();

$palavra_chave = "saber";
$tempo_expiracao = 1800; // 1 minuto em segundos

$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];

        // Verifica a palavra-chave
        if ($senha === $palavra_chave) {
            $_SESSION['autenticado'] = true;
            $_SESSION['tempo_autenticacao'] = time();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $mensagem_erro = "Palavra-chave incorreta.";
        }
    }
}

// Verifica se o usuário está autenticado e se o tempo de autenticação não expirou
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true || !isset($_SESSION['tempo_autenticacao']) || (time() - $_SESSION['tempo_autenticacao']) > $tempo_expiracao) {
    session_unset();
    session_destroy();
    echo '<style>
            body {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #dcdcdc;
            }
            form {
                display: flex;
                flex-direction: column;
                align-items: center;
                background-color: white;
                padding: 50px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                max-width: 100%;
                width: 50%;
            }
            input[type="password"], button {
                width: 100%;
                margin-bottom: 10px;
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 18px;
            }
            button {
                background-color: rgba(68, 39, 125, 0.8) !important;
                color: white;
                border: none;
                cursor: pointer;
            }
            button:hover {
                background-color: #45a049;
            }
            img {
                max-width: 220px;
                height: auto;
                margin-bottom: 40px;
            }
            #floating-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                background-color: #007bff;
                color: white;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 50px;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                animation: floating 2s infinite;
                left: 48%;
                    transform: translateX(-50%);
            }
            @keyframes floating {
                0% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
                100% {
                    transform: translateY(0);
                }
            }
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.4);
                justify-content: center;
                align-items: center;
            }
            .modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 30px;
                border: 1px solid #888;
                width: 200%;
                max-width: 300px;
                border-radius: 10px;
                text-align: center;
                font-size: 38px; /* Aumenta o tamanho da letra */
                 max-width: 700px; /* Aumenta a largura máxima do modal */
            }
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            .error {
                color: red;
                font-size: 14px;
                margin-bottom: 10px;
            }

            /* Responsividade */
            @media (max-width: 768px) {
                form {
                    padding: 15px;
                    width: 70%;
                    margin-bottom: 110px;
                }
                input[type="password"], button {
                    padding: 12px;
                    font-size: 16px;
                }
                img {
                    max-width: 50%;
                }
                #floating-button {
                    width: 80px;
                    height: 80px;
                    font-size: 50px;
                    left: 45%;
                    transform: translateX(-50%);
                }
            }

            @media (max-width: 480px) {
                 form {
                    padding: 15px;
                    width: 70%;
                    margin-bottom: 110px;
                }
                input[type="password"], button {
                    padding: 12px;
                    font-size: 16px;
                }
                img {
                    max-width: 100%;
                }
                #floating-button {
                    width: 80px;
                    height: 80px;
                    font-size: 50px;
                    left: 45%;
                    transform: translateX(-50%);
                }
            }
        </style>
        <div style="text-align: center;">
            <img src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
        </div>
        <form method="post">
            <div class="error">' . $mensagem_erro . '</div>
            <input type="password" name="senha" placeholder="Digite a palavra-chave" required>
            <button type="submit">ENTRAR</button>
        </form>
        <div id="floating-button" onclick="showModal()">?</div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>Após acessar, você terá 30 minutos. Depois, precisará logar novamente. Isso garante segurança.</p>
            </div>
        </div>
        <script>
            function showModal() {
                document.getElementById("myModal").style.display = "flex";
            }
            function closeModal() {
                document.getElementById("myModal").style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == document.getElementById("myModal")) {
                    closeModal();
                }
            }
        </script>';
    exit();
}
?>
