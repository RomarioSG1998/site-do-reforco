// recovery.js

const form = document.getElementById('recovery-form');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const email = form.email.value;

    // Aqui você pode adicionar a lógica para enviar um e-mail de recuperação de senha
    // Isso pode ser feito usando uma API de back-end ou serviço de e-mail

    // Por enquanto, vamos apenas exibir o email na console
    console.log('Email enviado para:', email);

    // Redirecionar para a página de login após o envio do e-mail de recuperação
    window.location.href = 'C:\Users\Josiane\Desktop\Signup-singnin\assets\html\recuperars.html';
});
