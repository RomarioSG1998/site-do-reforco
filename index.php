<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Comercial - Escola</title>
    <!-- <script src="./js/index.js" defer></script> -->
    <style>
        /* Corpo */
body {
    margin: 0;
    padding: 0;
    height: 100vh; /* Garante que o corpo ocupe toda a altura da tela */
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    overflow: hidden;
    
      
    

    /* Propriedades do fundo */
    background-image: url("../imagens/fundo2.png"); /* Verifique se o caminho está correto */
    background-size: 105% auto; /* Ajusta a largura para ser maior que a tela */
    background-repeat: no-repeat; /* Evita repetição */
    background-attachment: scroll; /* Permite o movimento do fundo */
    background-position: 0% 0%; /* Posição inicial */
    
    /* Animação */
    animation: moveBackground 10s infinite alternate; 
}

/* Definição da animação */
@keyframes moveBackground {
    0% {
        background-position: 0% 0%; /* Posição inicial (horizontal apenas) */
    }
    100% {
        background-position: 100% 0%; /* Movimento final (horizontal apenas) */
    }
}

.background-overlay {
    position: fixed; /* Garante que a sobreposição cobre toda a tela */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url("../imagens/index.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    z-index: 0; /* Atrás de todos os outros elementos, mas na frente do body */
}

/* Para garantir que os outros elementos fiquem na frente */
main, footer {
    position: relative;
    z-index: 2; /* Esses elementos ficam na frente da sobreposição */
}

/* Animação de entrada de cima para baixo */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-50px); /* Move o elemento para cima antes da entrada */
    }
    to {
        opacity: 1;
        transform: translateY(50); /* Retorna à posição original */
    }
}

/* Animação de entrada da direita para esquerda */
@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(50px); /* Move o elemento para a direita antes da entrada */
    }
    to {
        opacity: 1;
        transform: translateX(0); /* Retorna à posição original */
    }
}

/* Animação de entrada de baixo para cima */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px); /* Move o elemento para baixo antes da entrada */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* Retorna à posição original */
    }
}

/* Animação de flutuação contínua */
@keyframes floating {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px); /* Sobe ligeiramente */
    }
    100% {
        transform: translateY(0); /* Retorna à posição original */
    }
}

/* Botões no topo esquerdo */
.top-left-buttons {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1;
    animation: fadeInDown 1s ease-in-out;
    animation-delay: 0.2s; /* Atraso da animação */
}

/* Vídeo no canto superior direito */
.video-container {
    position: absolute;
    top: 120px;
    right: 100px;
    z-index: 0;
    animation: fadeInLeft 1.5s ease-in-out;
    animation-delay: 0.4s; /* Atraso da animação */
    width: 505px; /* Definido conforme o tamanho desejado */
    height: 285px; /* Definido conforme o tamanho desejado */
    overflow: hidden; /* Esconde qualquer parte do vídeo que ultrapasse o contêiner */
}

.video-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}
#content-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            z-index: 1000; /* Fica acima do overlay */
            display: none; /* Inicialmente escondido */
        }
/* Botões no canto inferior esquerdo */
.bottom-left-buttons {
    position: absolute;
    bottom: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1;
    animation: fadeInDown 1s ease-in-out;
    animation-delay: 0.6s; /* Atraso da animação */
}

/* Logo no canto inferior direito */
.bottom-right-logo {
    position: absolute;
    bottom: 5px;
    right: 260px;
    width: 190px;
    animation: fadeInUp 2s ease-in-out, floating 3s ease-in-out infinite; /* Animação de entrada seguida de flutuação contínua */
    animation-delay: 0.8s; /* Atraso da animação de entrada */
    z-index: 1;
}

.bottom-right-logo img {
    width: 240px;
    height: auto;
    max-width: 100%;
}

/* Botões do menu */
.btn {
    padding: 10px 20px;
    background-color: #007BFF;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    animation: fadeInDown 1s ease-in-out;
    animation-delay: 1s; /* Atraso da animação */
    transition: background-color 0.3s ease; /* Transição suave para a mudança de cor */
}

/* Cores de fundo diferentes ao passar o mouse para cada botão */
.header-menu .btn0:hover {
    background-color: #ff33e4; /* Cor para o botão 1 */
}
.header-menu .btn1:hover {
    background-color: #FF5733; /* Cor para o botão 1 */
}

.header-menu .btn2:hover {
    background-color: #33FF57; /* Cor para o botão 2 */
}

.header-menu .btn3:hover {
    background-color: #3357FF; /* Cor para o botão 3 */

}
.header-menu .btn4:hover {
    background-color: #ff33cc; /* Cor para o botão 3 */

}
.header-menu .btn5:hover {
    background-color: #e0ff33; /* Cor para o botão 3 */

}

/* Estilos do menu no cabeçalho */
.header-menu {
    display: flex;
    border-radius: 2%;
    background-color: rgba(249, 249, 249, 0.9); /* 0.7 significa 70% opaco */
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    z-index: 2;
    animation: fadeInDown 1s ease-in-out;
    animation-delay: 1.2s; /* Atraso da animação */
}

/* Dropdown do menu */
.dropdown-content {
    display: flex;
}

.dropdown-button {
    display: none !important;
}

.dropdown-content button {
    margin: 5px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 14px;
}

#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
    z-index: 999; /* Fica abaixo do content-container */
    display: none; /* Inicialmente escondido */
}
#countdown {
    font-weight: bold;
    font-size: 1.5em;
    color: #d9534f; /* Vermelho para dar destaque */
    animation: pulse 1s infinite; /* Animação de pulsação */
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}
footer {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9rem;
    color: #555;
}

footer {
    margin-top: auto; /* Move o rodapé para o final da página */
    background-color: none;
    text-align: center;
    padding: 15px;
}

footer p {
    margin: 0;
    font-size: 14px;
    color: #333;

}

footer a {
    color: white;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

/* Media query para dispositivos móveis */
@media (max-width: 768px) {
    body {
        background-image: url("../imagens/fundo2.png");
        background-size: cover;
    }

    .video-container {
        width: 80%;
        height: 28%;
        top: 200px;
        right: 0;
        left: 0;
        margin: 0 auto;
    }

    .bottom-right-logo {
        bottom: 40px;
        left: 30%;
        transform: translateX(-50%);
        width: 150px;
    }

    .header-menu {
        flex-direction: column;
        top: 10px;
        left: 10px;
        margin: 0 auto; /* Center the menu */
        display: none;
        position: fixed;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        width: 70%;
        background: white;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .header-menu.active {
        display: flex;
    }

    .btn {
        width: 100%;
        margin: 5px 0;
        text-align: center;
    }

    .dropdown-content {
        flex-direction: column;
        display: none; /* Inicialmente escondido */
        position: center;
        width: 80%;
        box-shadow: none;
        display: flex;
        text-align: center;
    }

    .top-left-buttons, .bottom-left-buttons {
        left: 10px;
        right: 10px;
        width: calc(100% - 20px);
    }

    #content-container {
        width: 90%;
        height: 90%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Hamburger Menu Button */
    .menu-toggle {
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
    }

    .menu-toggle span {
        display: block;
        width: 25px;
        height: 3px;
        background-color: #333;
        margin: 5px 0;
        transition: 0.4s;
    }

    /* Hamburger Menu */
    .menu-btn {
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        cursor: pointer;
    }

    .menu-btn__burger {
        width: 30px;
        height: 3px;
        background: #333;
        margin: 5px 0;
    }

    .menu-btn {
        display: block;
    }

    .btn-container {
        display: none;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(255,255,255,0.95);
        padding: 20px;
    }

    .btn-container.show {
        display: block;
    }

    /* Hamburger Button */
    .hamburger {
        display: block;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: none;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    .bottom-right-logo img {
        transform: translateY(-40px);
   
    }

    .hamburger span {
        display: block;
        width: 25px;
        height: 3px;
        background: #333;
        margin: 5px 0;
    }
    footer p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        bottom: 0px;
        transform: translateY(-40px); /* Move o texto para cima */
    }
}
    </style>
</head>
<body>
    <div class="background-overlay"></div>
    <!-- Single menu toggle button -->
    <button class="menu-toggle" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <!-- Add before button container -->
    <div class="menu-btn">
        
        
    </div>
    <!-- Cabeçalho com menu -->
    <header>
        <button class="hamburger" title="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="header-menu">
            <button class="dropdown-button">Menu</button>
            <div class="dropdown-content">
                <button class="btn btn0">Admin</button>
                <button class="btn btn1">Aluno</button>
                <button class="btn btn2">Professor</button>
                <button class="btn btn3">Quem Somos</button>
                <button class="btn btn4">Por que Estudar Conosco?</button>
                <button class="btn btn5">Quem Pode Estudar no Reforço?</button>
            </div>
        </div>
    </header>

    <div id="overlay"></div>
    
    <div id="content-container"></div>
    
    <!-- Vídeo no canto superior direito -->
    <div class="video-container">
        <iframe width="500" height="275" src="https://www.youtube.com/embed/uj6XVOFjSNQ" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Logo no canto inferior direito -->
    <div class="bottom-right-logo">
        <img src="./imagens/logo sem fundo1.png" alt="Logo da Escola">
    </div>

    <footer>
        <p>&copy; 2024 Reforço Sede do Saber. Todos os direitos reservados. <a href="#">Política de Privacidade</a></p>
    </footer>
</body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.querySelector('.dropdown-button');
    const headerMenu = document.querySelector('.header-menu');

    if (dropdownButton) {
        dropdownButton.addEventListener('click', () => {
            headerMenu.classList.toggle('active');
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.btn');
    const contentContainer = document.getElementById('content-container');
    const overlay = document.getElementById('overlay');

    buttons.forEach((button, index) => {
        button.addEventListener('click', () => {
            let content = '';
            let redirectUrl = '';
            let isRedirect = false;

            switch (index) {
                case 0: // Botão Admin
                content = '<h1>Portal do Admin</h1><p>Redirecionando em <span id="countdown">5</span> segundos...</p>';
                redirectUrl = 'http://localhost/sededosaber/logadmin.php';
                isRedirect = true;
                break;
                case 1: // Botão Aluno
                    content = '<h1>Portal do Aluno</h1><p>Redirecionando em <span id="countdown">5</span> segundos...</p>';
                    redirectUrl = 'http://localhost/sededosaber/logaluno.php';
                    isRedirect = true;
                    break;
                case 2: // Botão Professor
                    content = '<h1>Portal do Professor</h1><p>Redirecionando em <span id="countdown">5</span> segundos...</p>';
                    redirectUrl = 'http://localhost/sededosaber/logprof.php';
                    isRedirect = true;
                    break;
                case 3: // Quem Somos
                    content = '<iframe src="quem-somos.html" style="width: 100%; height: 100%; border: none;"></iframe>';
                    break;
                case 4: // Por que Estudar Conosco?
                    content = '<iframe src="por-que-estudar.html" style="width: 100%; height: 100%; border: none;"></iframe>';
                    break;
                case 5: // Quem Pode Estudar no Reforço?
                    content = '<iframe src="quem-pode-estudar.html" style="width: 100%; height: 100%; border: none;"></iframe>';
                    break;
                default:
                    content = '<h1>Conteúdo não encontrado</h1>';
            }

            contentContainer.innerHTML = content;
            contentContainer.style.display = 'block';
            overlay.style.display = 'block';

            if (isRedirect) {
                const countdownElement = document.getElementById('countdown');
                let countdown = 5;

                const interval = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(interval);
                        window.location.href = redirectUrl; // Redireciona para o portal
                    }
                }, 1000); // Atualiza a cada 1 segundo
            }
        });
    });

    // Fecha o modal ao clicar no overlay
    overlay.addEventListener('click', () => {
        contentContainer.style.display = 'none';
        overlay.style.display = 'none';
    });

    
});

        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('nav');

        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
        });

        const menuBtn = document.querySelector('.menu-btn');
        const btnContainer = document.querySelector('.btn-container');
        
        menuBtn.addEventListener('click', () => {
            btnContainer.classList.toggle('show');
        });
        document.addEventListener('DOMContentLoaded', () => {
        const hamburger = document.querySelector('.hamburger');
        const headerMenu = document.querySelector('.header-menu');

        hamburger.addEventListener('click', () => {
            headerMenu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!headerMenu.contains(e.target) && !hamburger.contains(e.target)) {
            headerMenu.classList.remove('active');
            }
        });

        // Add close button inside the menu
        const closeButton = document.createElement('button');
        closeButton.textContent = 'X';
        closeButton.style.position = 'absolute';
        closeButton.style.top = '10px';
        closeButton.style.right = '10px';
        closeButton.style.background = 'none';
        closeButton.style.border = 'none';
        closeButton.style.fontSize = '1.5rem';
        closeButton.style.cursor = 'pointer';
        closeButton.style.display = 'none'; // Initially hidden
        headerMenu.appendChild(closeButton);

        closeButton.addEventListener('click', () => {
            headerMenu.classList.remove('active');
        });

        // Show close button only on mobile screens
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        function handleScreenChange(e) {
            if (e.matches) {
            closeButton.style.display = 'block';
            } else {
            closeButton.style.display = 'none';
            }
        }
        mediaQuery.addListener(handleScreenChange);
        handleScreenChange(mediaQuery);
        });
    </script>
</html>