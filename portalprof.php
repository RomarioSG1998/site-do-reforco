<?php 

include('protect1.php');
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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        .user-photo, .profile-image {
            width: 50px;
            height: 50px;
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

        nav ul {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            nav ul {
                display: none; /* Esconde o menu por padrão */
                flex-direction: column;
                background-color: rgba(68, 39, 125, 0.8) !important; /* Fundo do menu dropdown */
                position: absolute;
                top: 60px;
                right: 20px;
                width: 200px;
                border-radius: 8px;
                z-index: 10;
            }

            nav ul li {
                margin: 0.5rem 0;
            }

            nav ul li a {
                color: #fff;
                padding: 10px;
                text-align: center;
            }

            #menu-toggle {
                display: block; /* Mostra o botão de hambúrguer */
            }

            .profile-container {
                flex-direction: column;
                text-align: center;
            }

            .profile-image {
                margin-top: 1rem;
            }
        }

        /* Media Query para dispositivos móveis */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin: 0.5rem 0;
                display: block;
                width: 100%;
                text-align: center;
            }

            .profile-container {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .profile-image {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <a href="portalprof.php">
            <img src="./imagens/logo sem fundo1.png" alt="Logo" class="logo">
        </a>
        <h1>Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
    </div>
    <nav>
    <button id="menu-toggle" aria-label="Menu">☰</button> <!-- Botão de menu -->
    <ul id="menu">
        <li><a href="?page=alunosprof">Alunos</a></li>
        <li><a href="?page=tarefasprof">Tarefas</a></li>
        <li><a href="?page=msm">Mensagens</a></li>
        <li class="profile-container">
            <a href="logout1.php">Sair</a>
            <!-- A imagem agora redireciona para a página do perfil -->
            <a href="?page=perfilprof">
                <img class="profile-image" src="<?php echo $imagem; ?>" alt="Imagem do Usuário">
            </a>
        </li>
    </ul>
</nav>

</header>

<main style="margin-top: 100px; padding: 20px;">
    <?php
    // Obtém o nome da página a ser incluída
    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Dashboardprof';

    // Lista de páginas permitidas
    $allowed_pages = ['Dashboardprof', 'perfilprof', 'alunosprof', 'tarefasprof', 'msm',];

    if (in_array($page, $allowed_pages)) {
        // Inclui o arquivo apenas se ele estiver na lista permitida
        include("$page.php");
    } else {
        echo "<p>Página não encontrada.</p>";
    }
    ?>
</main>

<script>
    // Alternar visibilidade do menu
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        if (menu.style.display === 'block') {
            menu.style.display = 'none'; // Esconde o menu
        } else {
            menu.style.display = 'block'; // Mostra o menu
        }
    });
</script>
</body>
</html>