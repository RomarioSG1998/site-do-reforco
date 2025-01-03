<?php
include('protect.php');
include('conexao2.php');

$id_usuario = $_SESSION['id'];

$sql = "SELECT usu_img FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imagem = htmlspecialchars($row['usu_img']);
} else {
    die("<div class='error-msg'>Usuário não encontrado.</div>");
}

$stmt->close();
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* Reset e configurações gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tahoma', sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: #dcdcdc;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Estilos do header */
        header {
            background-color: rgba(68, 39, 125, 0.8) !important; /* Fundo semi-transparente */
            padding: 20px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            z-index: 10 !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 70px;
            height: auto;
            margin-right: 10px;
        }

        header h1 {
            color: #fff;
            font-size: 25px;
        }

        nav {
            position: relative;
        }

        /* Menu padrão */
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 10px 15px;
            display: block;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #4caf50;
        }

        /* Dropdown */
        nav ul li {
            position: relative;
        }

        nav ul li .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #44277D;
            flex-direction: column;
            min-width: 150px;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        nav ul li .dropdown li {
            padding: 10px;
        }

        nav ul li .dropdown li a {
            color: #fff;
            text-decoration: none;
        }

        nav ul li:hover .dropdown {
            display: flex;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .user-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        /* Menu responsivo */
        #menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            nav ul {
                display: none;
                flex-direction: column;
                background-color: rgba(68, 39, 125, 0.9);
                position: absolute;
                top: 60px;
                right: 20px;
                width: 200px;
                border-radius: 8px;
                z-index: 10;
            }

            nav ul li a {
                text-align: center;
            }

            #menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <a href="painel.php">
            <img src="./imagens/logo sem fundo1.png" alt="Logo" class="logo">
        </a>
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
    </div>
    <nav>
        <button id="menu-toggle" aria-label="Menu">☰</button>
        <ul id="menu">
            <li><a href="?page=PB">PB</a></li>
            <li>
                <a href="#" class="dropdown-toggle">Admin</a>
                <ul class="dropdown">
                    <li><a href="?page=mensalidade">Pagamentos</a></li>
                    <li><a href="?page=paisava">Para os pais</a></li>
                    <li><a href="?page=cadusuario">Cadastro de usuário</a></li>
                    <li><a href="?page=modcadastro">Alterar Cadastro</a></li>
                    <li><a href="?page=Monitorar">Monitorar Acessos</a></li>
                </ul>
            </li>
            <li><a href="?page=cadastro2">Cadastrar</a></li>
            <li><a href="?page=modcadastro">Alterar</a></li>
            <li style="display: flex; align-items: center; gap: 10px;">
                <a href="logout.php">Sair</a>
                <img class="profile-image" src="<?php echo $imagem; ?>" alt="Imagem do Usuário">
            </li>
        </ul>
    </nav>
</header>
<main style="margin-top: 100px; padding: 20px;">
    <?php
    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Dashboard';
    $allowed_pages = ['Dashboard', 'pageadmin', 'cadastro2', 'modcadastro', 'PB', 'Monitorar', 'cadusuario', 'paisava', 'mensalidade'];

    if (in_array($page, $allowed_pages)) {
        include("$page.php");
    } else {
        echo "<p>Página não encontrada.</p>";
    }
    ?>
</main>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });
</script>
</body>
</html>
