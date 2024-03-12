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
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .admin-header h1 {
      margin: 0;
      font-size: 24px;
    }
    .admin-profile {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 20px;
    }
    .admin-profile input[type="file"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }
    .admin-profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin-right: 20px;
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
    }
    .admin-actions a {
      text-decoration: none;
      color: #333;
      margin: 0 20px;
      font-size: 18px;
      padding: 10px 20px;
      border: 2px solid #333;
      border-radius: 4px;
      transition: background-color 0.3s, color 0.3s;
    }
    .admin-actions a:hover {
      background-color: #333;
      color: #fff;
    }

    /* Media queries para responsividade */
    @media screen and (max-width: 768px) {
      .admin-profile {
        flex-direction: column;
      }
      .admin-profile img {
        margin-right: 0;
        margin-bottom: 10px;
      }
      .admin-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
      }
      .admin-actions a {
        margin: 10px 0;
      }
    }
  </style>
</head>
<a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">home</i><</a>
<body>
  <div class="admin-header">
    <h1>Painel de Administração de <?php echo htmlspecialchars($_GET['nome']); ?></h1>
  </div>
  <div class="admin-profile">
    <?php
    // Conexão com o banco de dados
    $hostname = "localhost";
    $bancodedados = "id21964020_sistemadoreforco";
    $usuario = "root";
    $senha = "";
    
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verifica a conexão
    if ($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Obtém o ID do usuário (você deve ter uma maneira de identificar o usuário atual)
    $id_usuario = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Prepara e executa a query SQL para obter a imagem do usuário
    $query = "SELECT imagem FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se a query retornou resultados
    if ($resultado && $resultado->num_rows > 0) {
        // Exibe a imagem do usuário se existir
        $row = $resultado->fetch_assoc();
        $imagem_usuario = $row['imagem'];
        echo '<img src="' . htmlspecialchars($imagem_usuario) . '" alt="Foto do Admin">';
    } else {
        // Exibe uma imagem padrão caso o usuário não tenha uma imagem definida
        echo '<img src="https://w7.pngwing.com/pngs/429/434/png-transparent-computer-icons-icon-design-business-administration-admin-icon-hand-monochrome-silhouette.png">';
    }

    // Fecha a conexão
    $conexao->close();
    ?>
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
