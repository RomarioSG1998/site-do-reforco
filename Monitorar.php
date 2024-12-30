<?php
include('conexao2.php');
include('protect.php');

// Selecionar os cinco últimos registros com o nome do usuário, a imagem e o tipo
$query_select = "SELECT atividades.*, usuarios.nome as nome_usuario, usuarios.usu_img, usuarios.tipo 
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
    $tipo_usuario = $atividade['tipo']; // Adiciona o tipo de usuário
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
            background-color: #dcdcdc;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .btn-novo {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-novo img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out;
        }

        .btn-novo img:hover {
            transform: scale(1.1);
        }

        h1 {
            text-align: center;
            font-family: 'Tahoma', sans-serif;
            font-size: 36px;
            margin-top: 0;
            color: #44277D;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .timeline {
            list-style: none;
            padding: 0;
            margin: 0;
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
            background-color: #9b59b6; /* Cor lilás */
            border-radius: 50%;
            position: absolute;
            left: -11px;
            top: 10px;
            transition: transform 0.5s;
        }

        .timeline-item.active::before {
            transform: rotate(360deg);
        }

        .timeline-item-content {
            padding-left: 35px;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #9b59b6;
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
            color: #9b59b6;
            font-size: 22px;
        }

        .timeline-item-content p {
            margin: 0;
            color: #555;
            font-size: 16px;
        }

        .timeline-item-content img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .timeline-item-content .info {
            display: flex;
            align-items: center;
        }

        .timeline-item-content .info p {
            margin-left: 10px;
        }

        @media screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Quem acessou o sistema?</h1>
        <ul class="timeline">
            <?php foreach ($atividades as $index => $atividade) : ?>
                <li class="timeline-item" id="item-<?php echo $index; ?>">
                    <div class="timeline-item-content">
                        <div class="info">
                            <!-- Exibe a imagem do usuário -->
                            <img src="<?php echo $atividade['usu_img']; ?>" alt="Imagem do Usuário">
                            <div>
                                <h2><?php echo $atividade['nome_usuario']; ?></h2>
                                <p><strong>Data e Hora:</strong> <?php echo $atividade['data_hora']; ?></p>
                                <p><strong>Tipo de Usuário:</strong> <?php echo $atividade['tipo']; ?></p> <!-- Adiciona o tipo de usuário -->
                            </div>
                        </div>
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
