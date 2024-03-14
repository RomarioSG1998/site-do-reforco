<?php 
include('conexao2.php');
include('admin.php');
include('protect.php'); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administração</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .admin-header {
      background-color: #7e6e9e; /* Lilás Escuro */
      color: #fff;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .admin-header h1 {
      margin: 0;
      font-size: 24px;
    }
    .admin-header .home-link {
      position: absolute;
      top: 20px;
      left: 20px;
      text-decoration: none;
      color: #fff;
      display: flex;
      align-items: center;
    }
    .admin-profile {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      margin-top: 20px;
    }
    .admin-profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin-bottom: 20px;
      border: 4px solid #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .admin-profile h2 {
      margin: 0;
      font-size: 24px;
    }
    .admin-actions {
      text-align: center;
      margin-top: 30px;
      display: flex;
      flex-direction: column; /* Alteração aqui para colocar os botões um abaixo do outro */
    }
    .admin-actions a {
      text-decoration: none;
      color: #7e6e9e; /* Lilás Escuro */
      margin: 0 20px;
      font-size: 18px;
      padding: 10px 20px;
      border: 2px solid #7e6e9e; /* Lilás Escuro */
      border-radius: 4px;
      transition: background-color 0.3s, color 0.3s;
      max-width: 200px; /* Definindo largura máxima para os botões */
      margin: 10px auto; /* Centralizando os botões */
    }
    .admin-actions a:hover {
      background-color: #9381a9; /* Lilás Mais Escuro */
      color: #fff;
    }

    /* Media queries para responsividade */
    @media screen and (max-width: 768px) {
      .admin-profile img {
        margin-bottom: 10px;
      }
      .admin-profile form {
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>
  <div class="admin-header">
    <h1>Painel de Administração de <?php echo htmlspecialchars($_GET['nome']); ?></h1>
  </div>
  <div class="btn-novo">
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img src="../imagens/logo sem fundo.png" alt="Home"  width="70" height="70">
    </a>
  </div>

  <div class="admin-profile">
    <img src="https://i.pinimg.com/originals/81/2c/22/812c229c60047ee347f778135cd76b81.gif" alt="Foto do Admin">
    <h2><?php echo htmlspecialchars($_GET['nome']); ?></h2>
  </div>

  <div class="admin-actions">
    <a href="cadusuario.php">Cadastrar Novo Usuário</a>
    <a href="mensalidade.php">Tabela de Pagamentos Mensais</a>
    <a href="Monitorar.php"> Monitorar Atividades</a>
  </div>
</body>
</html>
