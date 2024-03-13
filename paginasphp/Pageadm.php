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
    .admin-profile form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .admin-profile input[type="file"] {
      display: none;
    }
    .admin-profile label {
      background-color: #9381a9; /* Lilás Claro */
      color: #fff;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s, color 0.3s;
    }
    .admin-profile label:hover {
      background-color: #7e6e9e; /* Lilás Mais Escuro */
    }
    .admin-actions {
      text-align: center;
      margin-top: 30px;
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
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>" class="home-link">
      <img src="../imagens/logo sem fundo.png" alt="Home" width="50" height="50">
    </a>
  </div>

  <div class="admin-profile">
    <?php
    // Código PHP para exibir a imagem do usuário
    // ...

    // Formulário para upload de imagem
    ?>
    <img src="<?php echo htmlspecialchars($imagem_usuario); ?>" alt="Foto do Admin">
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="imagem" id="admin-image" accept="image/*">
      <label for="admin-image" id="image-label">Selecione uma imagem</label>
      <h2><?php echo htmlspecialchars($_GET['nome']); ?></h2>
      <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
      <input type="submit" value="Enviar Imagem">
    </form>
  </div>

  <div class="admin-actions">
    <a href="cadusuario.php">Cadastrar Novo Usuário</a>
    <a href="mensalidade.php">Tabela de Pagamentos Mensais</a>
  </div>

  <script>
    // JavaScript para exibir a imagem selecionada pelo usuário
    const input = document.getElementById('admin-image');
    const label = document.getElementById('image-label');

    input.addEventListener('change', () => {
      const file = input.files[0];
      if (file) {
        label.style.display = 'none'; // Oculta a etiqueta após o upload
      }
    });
  </script>
</body>
</html>
