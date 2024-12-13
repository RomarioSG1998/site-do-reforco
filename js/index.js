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
                    redirectUrl = 'https://portal-aluno.exemplo.com';
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
