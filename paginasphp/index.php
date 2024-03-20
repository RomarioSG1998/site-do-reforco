<?php
include('conexao.php');
include('conexao2.php');
if(isset($_POST['email']) || isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $email = $conexao->real_escape_string($_POST['email']);
        $senha = $conexao->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $conexao->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: painel.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/signin.css">
  <title>Login</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh;
      background-image: url("https://media.giphy.com/media/AohQOlWnW9NIs/giphy.gif");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
    }

    .card {
      background-color:white;
      padding: 10px;
      margin-top:200px;
      border-radius: 4%;
      box-shadow: 3px 3px 1px 0px rgba(0, 0, 0, 0.4);
      max-width: 350px;
      width: 100%;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: black;
    }

    .label-float input {
      width: 100%;
      padding: 5px 5px;
      display: inline-block;
      border: 0;
      border-bottom: 2px solid #c505a5;
      background-color: transparent;
      outline: none;
      min-width: 180px;
      font-size: 16px;
      transition: all .3s ease-out;
      border-radius: 15px;
    }

    .label-float {
      position: relative;
      padding-top: 13px;
      margin-top: 5%;
      margin-bottom: 5%;
    }

    .label-float input:focus {
      border-bottom: 2px solid #4038a0;
    }

    .label-float label {
      color: #c505a5;
      pointer-event: none;
      position: absolute;
      top: 0;
      left: 0;
      margin-top: 13px;
      transition: all .3s ease-out;
    }

    .label-float input:focus+label,
    .label-float input:valid+label {
      font-size: 15px;
      margin-top: 0;
      color: #c505a5;
    }

    button {
      background-color: green;
      border-color: green;
      color: white;
      padding: 6px;
      font-weight: bold;
      font-size: 12pt;
      margin-top: 20px;
      border-radius: 15px;
      cursor: pointer;
      outline: none;
      transition: all .4s ease-out;
      width: 100%;
    }

    button:hover {
      background-color: #c505a5;
      color: #ffffff;
    }

    .justify-center {
      display: flex;
      justify-content: center;
    }

    hr {
      margin-top: 10%;
      margin-bottom: 10%;
      width: 60%;
    }

    p {
      color: gray;
      font-size: 13pt;
      text-align: center;
    }

    a {
      color: #630353;
      font-weight: bold;
      text-decoration: none;
      transition: all .3s ease-out;
    }

    a:hover {
      color: #c505a5;
    }

    .fa-eye {
      position: absolute;
      top: 15px;
      right: 10px;
      cursor: pointer;
      color: #ee7bdb;
    }

    #msgError {
      text-align: center;
      color: #ff0000;
      background-color: #ffbbbb;
      padding: 10px;
      border-radius: 4px;
      display: none;
    }

    .justify-right1 {
      text-align: center;
      font-family: 'Times New Roman', Times, serif;
      font-size: 20px;
      font-weight: bold;
      margin-top: 2%;
      color: #630353;
    }

    @media only screen and (max-width: 768px) {
      .card {
        width: 80%;
      }
    }
  </style>
</head>

<body>
<div class="container">
    <div class="justify-right1">
      <p1>LOGIN <br>DO REFORÇO</p1>
    </div>
    <div class="card">
      <div id="msgError"></div>
      <form id="loginForm" action="conect.php" method="post">
        <div class="label-float">
          <input type="text" id="usuario" name="email" placeholder="" required>
          <!-- <label id="userLabel" for="usuario">Usuário</label> -->
        </div>
        <div class="label-float">
          <input type="password" id="senha" name="senha" placeholder="" required>
          <!-- <label id="senhaLabel" for="senha">Senha</label> -->
          <i class="fa fa-eye" aria-hidden="true"></i>
        </div>
        <div class="justify-center">
          <button type="submit">Entrar</button>
        </div>
      </form>
      <div class="justify-center">
        <hr>
      </div>
      <p>
        <a href="https://wa.me/558281083015">Esqueceu sua senha?</a><br>
        <p1> Recupere a senha, entre em contato com o Admin.</p1>
      </p>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('loginForm');
      const usuario = document.getElementById('usuario');
      const senha = document.getElementById('senha');
      const msgError = document.getElementById('msgError');

      form.addEventListener('submit', function(event) {
        event.preventDefault();
        if (usuario.value.trim() === '') {
          msgError.textContent = 'Por favor, preencha seu e-mail';
          return;
        }
        if (senha.value.trim() === '') {
          msgError.textContent = 'Por favor, preencha sua senha';
          return;
        }
        // Limpar a mensagem de erro se todos os campos estiverem preenchidos
        msgError.textContent = '';

        // Envie o formulário se a validação passar
        this.submit();
      });

      // Adicione aqui códigos adicionais para interatividade e animações, se desejar
    });
  </script>
</body>
<footer>
  <!-- Adicione seu rodapé aqui -->
</footer>
</html>

