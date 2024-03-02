<?php
include('conexao.php');

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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="../css/signin.css" />
    <title>Singin</title>
  </head>
  <body>
    <div class="justify-right1">
      <p1>Compromisso com uma <br>educação de qualidade</p1>
    </divi>
    <div class="container">
      <div class="card">
        <h1>Sede do Saber</h1>

        <div id="msgError"></div>

        <form action="conect.php" method="post">
          <div class="label-float">
            <input type="text" id="usuario" name="email" placeholder="" required />
            <label id="userLabel" for="usuario">Usuario</label>
          </div>

          <div class="label-float">
            <input type="password" id="senha" name="senha" placeholder="" required />
            <label id="senhaLabel" for="senha">Senha</label>
            <i class="fa fa-eye" aria-hidden="true"></i>
          </div>

          <div class="justify-center">
            <button type="submit">Entrar</button>
          </div>
        </form>

        <div class="justify-center">
          <hr />
        </div>

        <p>
          <a href="signup.php">
            Recuperar senha
          </a>
        </p>
        <p>
          <a href="https://wa.me/558281083015">
            Contatar o Admin
          </a>
        </p>
      </div>
    </div>

    <script src="signin.js"></script>
  </body>
  <footer>
  </footer>
</html>
