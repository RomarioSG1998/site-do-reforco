let email = document.querySelector('#nome');
let labelEmail = document.querySelector('#labelNome');
let validEmail = false;
let msgError = document.querySelector('#msgError');
let msgSuccess = document.querySelector('#msgSuccess');

email.addEventListener('keyup', () => {
  if (!isValidEmail(email.value)) {
    labelEmail.setAttribute('style', 'color: red');
    labelEmail.innerHTML = 'Email de recuperação *Insira um email válido';
    validEmail = false;
  } else {
    labelEmail.setAttribute('style', 'color: green');
    labelEmail.innerHTML = 'Email de recuperação';
    validEmail = true;
  }
});

function isValidEmail(email) {
  // RegEx para validar o formato do email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function cadastrar() {
  if (validEmail) {
    // Aqui você pode adicionar lógica para enviar o email de recuperação
    // Por exemplo:
    // sendRecoveryEmail(email.value);
    
    // Após enviar o email, redirecione o usuário para a página de confirmação
    window.location.href = 'pagina_de_confirmacao.html';
  } else {
    msgError.setAttribute('style', 'display: block');
    msgError.innerHTML = '<strong>Preencha o email corretamente antes de enviar</strong>';
    msgSuccess.innerHTML = '';
    msgSuccess.setAttribute('style', 'display: none');
  }
}

window.addEventListener('load', () => {
  // Limpa o campo de email ao carregar a página
  email.value = '';
});
