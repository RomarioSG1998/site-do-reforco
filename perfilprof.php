<?php
// Incluindo o arquivo de conexão e proteção
include('conexao2.php');
include('protect1.php');

// Verificando se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Acesso não autorizado!");
}

// Obtendo o ID do usuário logado
$id_usuario = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletando os dados do formulário
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $imagem_destino = ''; // Caminho para salvar a imagem

    // Verificando se a imagem foi enviada
    if (isset($_FILES['usu_img']) && $_FILES['usu_img']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = basename($_FILES['usu_img']['name']);
        $imagem_tmp = $_FILES['usu_img']['tmp_name'];
        $imagem_destino = 'imagens/' . uniqid() . '_' . $imagem_nome; // Nome único para evitar conflitos

        // Movendo o arquivo para a pasta de destino
        if (!move_uploaded_file($imagem_tmp, $imagem_destino)) {
            die("<div class='error-msg'>Erro ao salvar a imagem no servidor.</div>");
        }
    } else {
        // Mantém a imagem atual se nenhuma nova for enviada
        $sql_get_image = "SELECT usu_img FROM usuarios WHERE id = ?";
        $stmt_get_image = $conexao->prepare($sql_get_image);
        $stmt_get_image->bind_param("i", $id_usuario);
        $stmt_get_image->execute();
        $result = $stmt_get_image->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imagem_destino = $row['usu_img'];
        }
        $stmt_get_image->close();
    }

    // Atualizando os dados do usuário no banco de dados
    $sql_update = "UPDATE usuarios SET nome = ?, senha = ?, email = ?, usu_img = ? WHERE id = ?";
    $stmt_update = $conexao->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nome, $senha, $email, $imagem_destino, $id_usuario);

    if ($stmt_update->execute()) {
        echo "<div class='success-msg'>Dados atualizados com sucesso!</div>";
    } else {
        echo "<div class='error-msg'>Erro ao atualizar dados: " . $stmt_update->error . "</div>";
    }

    $stmt_update->close();
}

// Consulta para pegar os dados do usuário logado
$sql = "SELECT id, nome, senha, email, data_criacao, tipo, usu_img FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .profileprof-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .profile-details {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }
        .profile-details strong {
            color: #333;
        }
        form {
            margin-top: 30px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

    <script>
        function togglePassword() {
            var senhaField = document.getElementById('senha');
            var eyeIcon = document.getElementById('eye-icon');
            if (senhaField.type === 'password') {
                senhaField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                senhaField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Perfil do Usuário</h1>

        <?php
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
        <!-- Seção de perfil -->
        <div class="profile-section">
            <img class="profileprof-image" src="<?php echo htmlspecialchars($row['usu_img']); ?>" alt="Imagem do Usuário">
            <div class="profile-details"><strong>Nome:</strong> <?php echo htmlspecialchars($row['nome']); ?></div>
            <div class="profile-details"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></div>
            <div class="profile-details"><strong>Data de Criação:</strong> <?php echo htmlspecialchars($row['data_criacao']); ?></div>
            <div class="profile-details"><strong>Tipo:</strong> <?php echo htmlspecialchars($row['tipo']); ?></div>
        </div>

        <!-- Formulário de edição -->
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>

            <label for="senha">Senha:</label>
            <div class="password-wrapper">
                <input type="password" id="senha" name="senha" value="<?php echo htmlspecialchars($row['senha']); ?>" required>
                <i id="eye-icon" class="fas fa-eye eye-icon" onclick="togglePassword()"></i>
            </div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

            <label for="usu_img">Imagem:</label>
            <input type="file" id="usu_img" name="usu_img">

            <input type="submit" value="Atualizar">
        </form>
        <?php
        } else {
            echo "<div class='error-msg'>Usuário não encontrado.</div>";
        }

        $stmt->close();
        $conexao->close();
        ?>
    </div>
</body>
</html>