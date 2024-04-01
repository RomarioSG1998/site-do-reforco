<?php
include('conexao2.php');
include('admin.php');
include('protect.php');

$hostname = "localhost";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "A7E5zgIppyr";

$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($conexao->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Selecionar os cinco últimos registros com o nome do usuário e a imagem
$query_select = "SELECT atividades.*, usuarios.nome as nome_usuario, usuarios.usu_img 
                 FROM atividades 
                 INNER JOIN usuarios ON atividades.id_usuario = usuarios.id 
                 ORDER BY atividades.data_hora DESC 
                 LIMIT 5";
$resultado_select = $conexao->query($query_select);

if (!$resultado_select) {
    echo "Erro na consulta: " . $conexao->error;
}

$atividades = array(); // Inicializa a variável atividades

if ($resultado_select && $resultado_select->num_rows > 0) {
    while ($row = $resultado_select->fetch_assoc()) {
        $atividades[] = $row;
    }
}

// Deleta todos os registros da tabela atividades
$query_delete = "DELETE FROM atividades";
$conexao->query($query_delete);

// Insere os 5 últimos registros de volta na tabela atividades
foreach ($atividades as $atividade) {
    $id_usuario = $atividade['id_usuario'];
    $data_hora = $atividade['data_hora'];
    // Insira os dados de $atividade de volta na tabela atividades
    $query_insert = "INSERT INTO atividades (id_usuario, data_hora) VALUES ('$id_usuario', '$data_hora')";
    $conexao->query($query_insert);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline de Atividades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline {
            list-style: none;
            padding: 0;
            margin: 40px 0;
        }

        .timeline-item {
            margin-bottom: 40px;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s, transform 0.5s;
        }

        .timeline-item.active {
            opacity: 1;
            transform: translateY(0);
        }

        .timeline-item::before {
            content: '';
            display: block;
            width: 20px;
            height: 20px;
            background-color: #9b59b6; /* tom de lilás */
            border-radius: 50%;
            position: absolute;
            left: -11px; /* ajuste para centralizar */
            top: 10px;
            transition: transform 0.5s;
        }

        .timeline-item.active::before {
            transform: rotate(360deg);
        }

        .timeline-item-content {
            padding-left: 30px; /* ajuste para alinhar com a bolinha */
            padding: 20px;
            background-color: #fff;
            border: 2px solid #9b59b6; /* tom de lilás */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.5s;
        }

        .timeline-item.active .timeline-item-content {
            opacity: 1;
        }

        .timeline-item-content h2 {
            margin-top: 0;
            color: #9b59b6; /* tom de lilás */
        }

        .timeline-item-content p {
            margin: 0;
            color: #555;
        }

        .btn-novo {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-novo img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out;
        }

        .btn-novo img:hover {
            transform: scale(1.1);
        }
        .timeline-item::before {
    /* Seu código CSS existente */

    /* Adicionando animação de piscar */
    animation: blink 1s infinite alternate;
}

@keyframes blink {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}
 /* Estilos CSS existentes omitidos para brevidade */
    </style>
</head>

<body>
    <div class="container">
    <div class="btn-novo">
            <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
                <img src="./imagens/logo sem fundo2.png" alt="Home">
            </a>
        </div>
        <!-- Código HTML existente omitido para brevidade -->
        <h1 style=" text-align: center; font-family: 'Tahoma', sans-serif; font-size: 40px; margin-top: 3%; font-weight: normal; color: #D9D9D9; text-shadow: -2px -2px 0 #44277D, 2px -2px 0 #44277D, -2px 2px 0 #44277D, 2px 2px 0 #44277D;">Timeline de Atividades</h1>
        <ul class="timeline">
            <?php foreach ($atividades as $index => $atividade) : ?>
                <li class="timeline-item" id="item-<?php echo $index; ?>">
                    <div class="timeline-item-content">
                        <!-- Exibe a imagem do usuário -->
                        <img src="<?php echo $atividade['usu_img']; ?>" alt="Imagem do Usuário" style="width: 50px; height: 50px; border-radius: 50%;">
                        <p>Nome do Usuário: <?php echo $atividade['nome_usuario']; ?></p>
                        <p>Data e Hora: <?php echo $atividade['data_hora']; ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
       document.addEventListener("DOMContentLoaded", function() {
            var timelineItems = document.querySelectorAll('.timeline-item');

            timelineItems.forEach(function(item, index) {
                setTimeout(function() {
                    item.classList.add('active');
                }, index * 300); // Ajuste o valor de delay conforme necessário
            });
        });
    </script>
</body>

</html>
