<?php
// Incluindo o arquivo de conexão e proteção
include('conexao2.php');
include('protect2.php');

// Add this PHP code at the top of the file after database connection
if(isset($_POST['update_disciplina'])) {
    $id = $_POST['disciplina_id'];
    $professor = $_POST['professor'];
    $descricao = $_POST['descricao'];
    
    $update_query = "UPDATE disciplinas SET professor = ?, discricao = ? WHERE id = ?";
    $stmt = $conexao->prepare($update_query);
    $stmt->bind_param("ssi", $professor, $descricao, $id);
    $stmt->execute();
}
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
        echo "<div class=q'success-msg'>Dados atualizados com sucesso!</div>";
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
            background-color: #dcdcdc;
            margin: 0;
            padding: 0;
            color: #333;
            transition: background-color 0.5s ease;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        .container:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
            font-size: 2.5em;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .profileprof-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1s 0.5s forwards, float 3s infinite ease-in-out;
        }

        @keyframes float {
            0% {
            transform: translatey(0px);
            }
            50% {
            transform: translatey(-10px);
            }
            100% {
            transform: translatey(0px);
            }
        }
        .profile-details {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeIn 1s 1s forwards;
        }
        .profile-details strong {
            color: #555;
        }
        form {
            margin-top: 30px;
            opacity: 0;
            animation: fadeIn 1s 1.5s forwards;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, input[type="file"]:focus {
            border-color: #333;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }
        input[type="submit"] {
            padding: 15px 30px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #555;
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
            color: #aaa;
            transition: color 0.3s ease;
        }
        .eye-icon:hover {
            color: #333;
        }
        .success-msg, .error-msg {
            text-align: center;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
            opacity: 0;
            animation: fadeIn 1s 2s forwards;
        }
        .success-msg {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeIn {
            from {
            opacity: 0;
            }
            to {
            opacity: 1;
            }
        }
        .disciplinas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .disciplinas-table th, .disciplinas-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .disciplinas-table th {
            background-color: #f4f4f4;
        }
        .disciplinas-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .disciplinas-table tr:hover {
            background-color: #f5f5f5;
        }
        .table-container {
            margin-top: 30px;
            width: 100%;
            overflow-x: auto;
        }
        .table-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .disciplinas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .disciplinas-table th, 
        .disciplinas-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .disciplinas-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .disciplinas-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .disciplinas-table tr:hover {
            background-color: #f5f5f5;
        }
        .action-cell {
            width: 100px;
        }
        .edit-btn, .save-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
        }
        .edit-input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        .editable-cell {
            position: relative;
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

        function toggleEdit(id) {
            const row = document.getElementById('row-' + id);
            const displayTexts = row.querySelectorAll('.display-text');
            const editInputs = row.querySelectorAll('.edit-input');
            const editBtn = row.querySelector('.edit-btn');
            const saveBtn = row.querySelector('.save-btn');

            displayTexts.forEach(text => text.style.display = 'none');
            editInputs.forEach(input => input.style.display = 'block');
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        }

        function saveChanges(id) {
            const row = document.getElementById('row-' + id);
            const professor = row.querySelector('.professor').value;
            const descricao = row.querySelector('.descricao').value;

            const formData = new FormData();
            formData.append('update_disciplina', '1');
            formData.append('disciplina_id', id);
            formData.append('professor', professor);
            formData.append('descricao', descricao);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if(response.ok) {
                    const displayTexts = row.querySelectorAll('.display-text');
                    const editInputs = row.querySelectorAll('.edit-input');
                    const editBtn = row.querySelector('.edit-btn');
                    const saveBtn = row.querySelector('.save-btn');

                    displayTexts[0].textContent = professor;
                    displayTexts[1].textContent = descricao;

                    displayTexts.forEach(text => text.style.display = 'block');
                    editInputs.forEach(input => input.style.display = 'none');
                    editBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                }
            });
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
            <button type="button" onclick="openPopup(<?php echo $id_usuario; ?>)" style="padding: 15px 30px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; transition: background-color 0.3s ease;">Disciplinas</button>

            <div id="popup" style="display:none; position:fixed; top:60%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border:1px solid #ddd; box-shadow:0 4px 8px rgba(0,0,0,0.1); z-index:1000;">
                <iframe id="popup-iframe" src="" style="width:100%; height:400px; border:none;"></iframe>
                <button type="button" onclick="closePopup()" style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 10px;">Fechar</button>
            </div>

            <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;"></div>

            <script>
            function openPopup(userId) {
                document.getElementById('popup-iframe').src = 'disciplinaprof.php?user_id=' + userId;
                document.getElementById('popup').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }

            function closePopup() {
                document.getElementById('popup').style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
            }
            </script>
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