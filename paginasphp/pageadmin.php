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
      font-family: 'Times New Roman', Times, serif; /* Definindo a fonte para Times New Roman */
      margin: 0;
      padding: 0;
      background-color: #fff;
    }
    .admin-header {
      background-color:  #58216d; /* Lilás Escuro */
      color: #fff;
      padding: 25px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative; /* Adicionando posição relativa para .admin-header */
    }
    .admin-header h1 {
      margin-left: -50px;
      margin-top: 20px; /* Ajustando a margem superior */
      margin-bottom: 0px;
      font-size: 50px; /* Aumentando o tamanho da fonte */
      text-transform: uppercase; /* Transformando o texto em maiúsculas */
    }
    .admin-header .home-link {
      position: absolute;
      top: 10px;
      right: 10px; /* Alterado para o lado direito */
      text-decoration: none;
      color: #fff;
    }
    .admin-header .logo {
      width: 60px;
      height: auto;
      margin-top: -5px;
      margin-right: -10px;
    }
    .admin-profile {
      text-align: center;
      margin-top: 20px;
    }
    .admin-profile img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      margin-bottom: 10px;
      border: 4px solid #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .admin-profile h2 {
      margin: 0;
      font-size: 36px;
    }
    .admin-actions {
      text-align: center;
      margin-top: 30px;
    }
    .admin-actions a {
      display: block;
      text-decoration: none;
      color: #58216d; /* Lilás Escuro */
      background-color: #f9f4fc;
      margin: 10px auto;
      font-size: 16px;
      padding: 8px 16px;
      border: 4px solid #58216d; /* Lilás Escuro */
      border-radius: 20px;
      max-width: 200px; /* Definindo largura máxima para os botões */
    }
    .admin-actions a:hover {
      background-color: #9381a9; /* Lilás Mais Escuro */
      color: #fff;
    }

    /* Media queries para responsividade */
    @media screen and (max-width: 750px) {
      .admin-header {
        padding: 50px;
      }
      .admin-header h1 {
        font-size: 20px;
        padding-top: 20px;
      }
      .admin-header .logo {
        width: 80px;
      }
      .admin-profile img {
        width: 120px;
        height: 120px;
      }
      .admin-profile h2 {
        font-size: 30px;
      }
      .admin-actions a {
        font-size: 14px;
        background-color: #f9f4fc;
      }
    }
  </style>
</head>
<body>
  <div class="admin-header">
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>" class="home-link">
      <img src="../imagens/logo sem fundo1.png" alt="Home" class="logo">
    </a>
    <h1><?php echo strtoupper("Central Administrativa de " . htmlspecialchars($_SESSION['nome'])); ?></h1>
  </div>

  <div class="admin-profile">
    <img src="https://i.pinimg.com/originals/81/2c/22/812c229c60047ee347f778135cd76b81.gif" alt="Foto do Admin">
    <h2><?php echo htmlspecialchars($_SESSION['nome']); ?></h2>
  </div>

  <div class="admin-actions">
    <a href="cadusuario.php">Cadastrar Novo Usuário</a>
    <a href="mensalidade.php">Tabela de Pagamentos</a>
    <a href="Monitorar.php"> Monitorar Atividades</a>
  </div>
</body>
</html>
