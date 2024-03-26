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
    <link rel="stylesheet" href="../css/signup.css" />
    <title>Singup</title>
  </head>
  <body>
    <div class="container">
      <div class="card">
        <h1>Sede do Saber</h1>
        <div class="justify-center">
          <a href="index.php" class="button-link">Voltar para o login</a>
      </div>
      
        <div id="msgError"></div>
        <div id="msgSuccess"></div>

        <div class="label-float">
          <input type="text" id="nome" placeholder=" " required />
          <label id="labelNome" for="nome">Email de recuperação</label>
        </div>

        <div class="justify-center">
          <button onclick="cadastrar()">Enviar</button>
        </div>
      </div>
    </div>
  
    <script src="signup.js"></script>
  </body>
</html>
