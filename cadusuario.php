<?php
include('conexao2.php');
include('protect.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
       body {
        font-family: 'Tahoma', sans-serif;
            margin: 20px;
            background-color: #dcdcdc;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            padding: 0;
           
        }
        .cadastro-frase {
            font-size: 35px;
            text-align: center;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            color:black; /* Define a cor do texto */
            
        }

        /* Estilos para a imagem */
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 10%;
            margin-top: 1px;
            margin-bottom: -10px;
        }
        h1 {
            text-align: center;
            color: #6a5acd; /* Lilás mais escuro para o título */
        }
        form {
            margin: 0 auto; /* Centraliza o formulário horizontalmente */
            width: 60%; /* 80% da largura da tela */
            font-family: 'Tahoma', sans-serif;
            max-width: 300px; /* Largura máxima para evitar que o formulário fique muito largo em telas grandes */
            background-color: #D9D9D9; /* Fundo branco para o formulário */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave para o formulário */
            animation: fadeInUp 1s ease; /* Animação de entrada */
        }
        label {
            color: #6a5acd; /* Lilás mais escuro para os rótulos */
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="submit"],
        input[type="file"] { /* Adicionando estilo para o input de arquivo */
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 3px solid #44277D; /* Lilás mais escuro para a borda */
            border-radius: 15px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #6a5acd; /* Lilás mais escuro para o botão de envio */
            color: #fff; /* Texto branco para o botão de envio */
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Espaço extra entre o formulário e a tabela */
            animation: fadeInUp 1s ease; /* Animação de entrada */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-icon, .delete-icon {
            color: #6a5acd; /* Lilás mais escuro para os ícones de edição e exclusão */
            text-decoration: none;
            margin-right: 5px;
        }
        .delete-icon {
            color: #ff6347; /* Vermelho para o ícone de exclusão */
        }
        /* Nova regra de mídia para telas de até 750px de largura */
    @media only screen and (max-width: 750px) {
        .cadastro-frase {
            font-size: 25px; /* Reduzindo o tamanho da fonte para telas menores */
        }
        .cadastro-imagem {
            max-width: 30%; /* Ajustando o tamanho da imagem para telas menores */
        }
        form {
            width: 90%; /* Ajustando a largura do formulário para telas menores */
        }
    }
        </style>
</head>
<body>
<div class="content">
    <p class="cadastro-frase">CADASTRO DO USUARIO</p>
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="./imagens/logo sem fundo2.png" alt="Descrição da imagem">
    </a>
</div>

    <?php
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar se todos os campos foram preenchidos
        if (!empty($_POST['nome']) && !empty($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['tipo'])) {
            // Sanitize os dados de entrada
            $nome = htmlspecialchars($_POST['nome']);
            $senha_usuario = htmlspecialchars($_POST['senha']);
            $email = htmlspecialchars($_POST['email']);
            $tipo = htmlspecialchars($_POST['tipo']);

            // Verificar se um arquivo de imagem foi enviado
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Defina o caminho de destino para a imagem
                $caminho_arquivo = 'imagens/' . $_FILES['imagem']['name'];

                // Salvar a imagem no servidor
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                    // Conexão com o banco de dados
                    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

                    // Verificar se a conexão foi estabelecida corretamente
                    if ($conexao->connect_error) {
                        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
                    }

                    // Preparar e executar a declaração SQL para inserir o novo usuário com o caminho da imagem
                    $sql_insert = "INSERT INTO usuarios (nome, senha, email, data_criacao, tipo, usu_img) VALUES (?, ?, ?, NOW(), ?, ?)";
                    $stmt = $conexao->prepare($sql_insert);
                    $stmt->bind_param("sssss", $nome, $senha_usuario, $email, $tipo, $caminho_arquivo);
                    
                    if ($stmt->execute()) {
                        echo "<script>
                                alert('Usuário cadastrado com sucesso!');
                                setTimeout(function() {
                                    window.location.href = 'cadusuario.php';
                                }, 2000); // 2 segundos
                              </script>";
                        exit;
                    } else {
                        echo "<script>alert('Erro ao cadastrar usuário.');</script>";
                    }

                    // Fechar a conexão com o banco de dados
                    $conexao->close();
                } else {
                    echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
                }
            } else {
                // Se nenhum arquivo de imagem foi enviado, insira apenas os dados do usuário sem o caminho da imagem
                // Conexão com o banco de dados
                $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

                // Verificar se a conexão foi estabelecida corretamente
                if ($conexao->connect_error) {
                    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
                }

                // Preparar e executar a declaração SQL para inserir o novo usuário sem o caminho da imagem
                $sql_insert = "INSERT INTO usuarios (nome, senha, email, data_criacao, tipo) VALUES (?, ?, ?, NOW(), ?)";
                $stmt = $conexao->prepare($sql_insert);
                $stmt->bind_param("ssss", $nome, $senha_usuario, $email, $tipo);
                
                if ($stmt->execute()) {
                    echo "<script>
                            alert('Usuário cadastrado com sucesso!');
                            setTimeout(function() {
                                window.location.href = 'cadusuario.php';
                            }, 2000); // 2 segundos
                          </script>";
                    exit;
                } else {
                    echo "<script>alert('Erro ao cadastrar usuário.');</script>";
                }

                // Fechar a conexão com o banco de dados
                $conexao->close();
            }
        } else {
            echo "<script>alert('Todos os campos devem ser preenchidos!');</script>";
        }
    }
    ?>

    <button id="toggleFormButton" style="
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #6a5acd;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        font-family: 'Tahoma', sans-serif;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    ">Cadastrar Novo Usuário</button>

    <style>
        #toggleFormButton:hover {
            background-color: #5a4bbd;
        }
    </style>
    <div id="formPopup" style="display: none;">
        <form id="userForm" action="javascript:void(0);" method="POST" enctype="multipart/form-data">
            <label for="nome">NOME:</label><br>
            <input type="text" id="nome" name="nome" required><br>
            <label for="senha">SENHA:</label><br>
            <input type="password" id="senha" name="senha" required><br>
            <label for="email">E-MAIL:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="tipo">TIPO DE USUARIO:</label><br>
            <select id="tipo" name="tipo" required>
                <option value="Professor(a)">Professor(a)</option>
                <option value="Admin">Admin</option>
            </select><br><br>
            <label for="imagem">IMAGEM:</label><br>
            <input type="file" id="imagem" name="imagem" accept="image/*"><br><br>
            <input type="submit" value="Cadastrar">
        </form>
    </div>

    <script>
        document.getElementById('toggleFormButton').addEventListener('click', function() {
            const formPopup = document.getElementById('formPopup');
            formPopup.style.display = formPopup.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('userForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('cadusuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Usuário cadastrado com sucesso!');
                document.getElementById('formPopup').style.display = 'none';
                location.reload(); // Reload the page to update the user list
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao cadastrar usuário.');
            });
        });
    </script>

    <?php
    // Conexão com o banco de dados
    $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verificar se a conexão foi estabelecida corretamente
    if ($conexao->connect_error) {
        die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    // Recuperar todos os dados da tabela de usuários
    $sql_select = "SELECT * FROM usuarios";
    $result = $conexao->query($sql_select);

    if ($result->num_rows > 0) {
        echo "<h2>Usuários cadastrados:</h2>";
        echo "<table id='userTable'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Última atualização</th><th>Tipo</th><th>Imagem</th><th>Ações</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["data_criacao"] . "</td>";
            echo "<td>" . $row["tipo"] . "</td>";
            echo "<td><img src='" . $row["usu_img"] . "' alt='Imagem do usuário' style='max-width:70px; border-radius: 50%;'></td>";
            echo "<td><a class='edit-icon' href='editarusu.php?id=" . $row["id"] . "'><i class='fas fa-edit'></i></a><a class='delete-icon' href='deletarusu.php?id=" . $row["id"] . "'><i class='fas fa-trash-alt'></i></a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum usuário cadastrado.</p>";
    }

    // Fechar a conexão com o banco de dados
    $conexao->close();
    ?>
    <script>
        // Adicionando animações aos elementos
        const form = document.getElementById('userForm');
        form.style.animation = "fadeInUp 1s ease";

        const table = document.getElementById('userTable');
        table.style.animation = "fadeInUp 1s ease";
    </script>
</body>
</html>
