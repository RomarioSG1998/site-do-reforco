<?php
date_default_timezone_set('America/Sao_Paulo');
include 'conexao2.php';
include 'protect2.php'; // Certifique-se de que a sessão está iniciada e o usuário está logado

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Inicia a sessão apenas se não estiver ativa
    }

    // Verifique se a sessão está iniciada corretamente
    if (!isset($_SESSION)) {
        throw new Exception("Sessão não iniciada.");
    }

    // Adicione mensagens de depuração para verificar o conteúdo da sessão
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';

    // Certifique-se de que a variável $_SESSION contém os dados do usuário
    if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
        $id_remetente = $_SESSION['id'];
    } else {
        // Redireciona ou exibe uma mensagem de erro se o usuário não estiver logado corretamente
        throw new Exception("Usuário não está logado corretamente. Verifique se a sessão está configurada corretamente.");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

try {
    // Função para enviar mensagem
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_destinatario1']) && isset($_POST['mensagem'])) {
        $id_destinatario1 = $_POST['id_destinatario1'];
        $mensagem = $_POST['mensagem'];
        $data_envio = date("Y-m-d H:i:s");
        $status = 'enviado';

        $sql = "INSERT INTO mensagens (id_remetente, id_destinatario1, mensagem, data_envio, status) 
                VALUES ('$id_remetente', '$id_destinatario1', '$mensagem', '$data_envio', '$status')";

        if ($conexao->query($sql) === TRUE) {
            echo "<script>alert('Mensagem enviada com sucesso!'); window.location.href = 'msm.php';</script>";
        } else {
            throw new Exception("Erro: " . $sql . "<br>" . $conexao->error);
        }
    }
} catch (Exception $e) {
    die("Erro ao enviar mensagem: " . $e->getMessage());
}

// Consulta para obter a lista de usuários, excluindo o usuário logado
$query_usuarios = "SELECT id, nome, usu_img FROM usuarios WHERE id != '$id_remetente'";
$resultado_usuarios = $conexao->query($query_usuarios);

// Consulta para obter a lista de destinatários para os quais o usuário logado já enviou mensagens
$query_destinatarios = "SELECT DISTINCT usuarios.id, usuarios.nome, usuarios.usu_img 
                        FROM mensagens 
                        INNER JOIN usuarios ON mensagens.id_destinatario1 = usuarios.id 
                        WHERE mensagens.id_remetente = '$id_remetente'";
$resultado_destinatarios = $conexao->query($query_destinatarios);

if (isset($_GET['id_destinatario'])) {
    $id_destinatario = $_GET['id_destinatario'];
    $query_mensagens = "SELECT data_envio, mensagem, id_remetente 
                        FROM mensagens 
                        WHERE (id_remetente = '$id_remetente' AND id_destinatario1 = '$id_destinatario') 
                           OR (id_remetente = '$id_destinatario' AND id_destinatario1 = '$id_remetente')
                        ORDER BY data_envio ASC";
    $resultado_mensagens = $conexao->query($query_mensagens);

    $mensagens = array();
    if ($resultado_mensagens->num_rows > 0) {
        while ($mensagem = $resultado_mensagens->fetch_assoc()) {
            $mensagens[] = $mensagem;
        }
    }
    echo json_encode($mensagens);
    exit;
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio de Mensagens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dcdcdc;
            margin: 0;
            padding: 0;
        }
        .dialog-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            z-index: 1000;
            width: 80%;
            max-height: 80%;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .dialog-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .message-timeline {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        .message-timeline li {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            max-width: 60%;
            word-wrap: break-word;
            position: relative;
        }
        .message-timeline .sent {
            background-color: #dcf8c6;
            align-self: flex-end;
            margin-left: auto;
            padding: 20px;
        }
        .message-timeline .received {
            background-color: #fff;
            border: 1px solid #ccc;
            align-self: flex-start;
            margin-right: auto;
        }
        .message-timeline .timestamp {
            font-size: 0.8em;
            color: #888;
            position: absolute;
            bottom: 0px;
            right: 10px;
        }
        .fixed-input {
            position: sticky;
            bottom: 0;
            background-color: #fff;
            padding: 10px;
            border-top: 1px solid #ccc;
            width: 100%;
        }
        .user-list img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        /* Adicione estilos para garantir que a caixa de diálogo e a sobreposição estejam ocultas inicialmente */
        #dialog-box, #dialog-overlay {
            display: none;
        }
        /* Adicione este estilo CSS */
        .message {
            margin-bottom: 30px; /* Ajuste o valor conforme necessário */
        }
        /* Estilização do título */
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Estilização do label e input do destinatário */
        label[for="destinatario"], #destinatario {
            display: block;
            margin-bottom: 10px;
        }

        #destinatario {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Estilização do label e textarea da mensagem */
        label[for="mensagem"], #mensagem {
            display: block;
            margin-bottom: 10px;
        }

        #mensagem {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Estilização da lista de destinatários */
        #destinatarios-enviados {
            margin-top: 20px;
        }

        #destinatarios-enviados h3 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #destinatarios-enviados ul {
            list-style: none;
            padding: 0;
        }

        #destinatarios-enviados li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        #destinatarios-enviados img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        #destinatarios-enviados span {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-top: 20px;">Envie mensagens para outros colegas ou estudantes:</h2>
    <form method="post" action="" style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <label for="id_destinatario1" style="display: block; margin-bottom: 10px;">Selecione a pessoa:</label>
        <select id="id_destinatario1" name="id_destinatario1" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px;" onchange="document.getElementById('mensagem-form').style.display = 'block';">
            <?php if ($resultado_usuarios->num_rows > 0): ?>
                <?php while($usuario = $resultado_usuarios->fetch_assoc()): ?>
                    <option value="<?php echo $usuario['id']; ?>">
                        <?php echo $usuario['nome']; ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">Nenhum usuário encontrado</option>
            <?php endif; ?>
        </select>

        <div id="mensagem-form" style="display: none;">
            <textarea id="mensagem" name="mensagem" rows="4" cols="50" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px;"></textarea>
            <input type="submit" value="Enviar Mensagem" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.querySelector("form");
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                var formData = new FormData(form);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "msm.php", true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Mensagem enviada com sucesso!");
                        form.reset();
                        document.getElementById('mensagem-form').style.display = 'none';
                    } else {
                        alert("Erro ao enviar mensagem.");
                    }
                };
                xhr.send(formData);
            });
        });
    </script>

    <h2 style="text-align: center; margin-top: 40px;">Continue suas interações:</h2>
    <div style="max-width: 600px; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <?php if ($resultado_destinatarios->num_rows > 0): ?>
            <ul class="user-list" style="list-style: none; padding: 0;">
                <?php while($destinatario = $resultado_destinatarios->fetch_assoc()): ?>
                    <li style="display: flex; align-items: center; margin-bottom: 10px;">
                        <?php
                        try {
                            $imagePath = './' . $destinatario['usu_img'];
                            if (!file_exists($imagePath)) {
                                throw new Exception('Image file does not exist: ' . $imagePath);
                            }
                            echo '<img src="' . $imagePath . '" alt="User Image" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">';
                        } catch (Exception $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
                        ?>
                        <a href="#" class="destinatario-link" data-id="<?php echo $destinatario['id']; ?>" style="text-decoration: none; color: #333; font-size: 16px;"><?php echo $destinatario['nome']; ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p style="text-align: center;">Nenhum destinatário encontrado.</p>
        <?php endif; ?>
    </div>

        <script>
            document.querySelectorAll('.destinatario-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    document.getElementById('mensagem-form').style.display = 'block';
                });
            });
        </script>

    <div class="dialog-overlay" id="dialog-overlay"></div>
    <div class="dialog-box" id="dialog-box">
        <h2>Continuar Conversa</h2>
        <ul class="message-timeline" id="message-timeline"></ul>
        <form id="dialog-form" method="post" action="">
            <input type="hidden" id="dialog-id-destinatario" name="id_destinatario1">
            <div class="fixed-input">
                <label for="dialog-mensagem">Mensagem:</label><br>
                <textarea id="dialog-mensagem" name="mensagem" rows="2" cols="50" required></textarea><br><br>
                <input type="submit" value="Enviar Mensagem">
            </div>
            <button type="button" id="dialog-close">Fechar</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var destinatarioLinks = document.querySelectorAll('.destinatario-link');
            var dialogBox = document.getElementById('dialog-box');
            var dialogOverlay = document.getElementById('dialog-overlay');
            var dialogClose = document.getElementById('dialog-close');
            var dialogIdDestinatario = document.getElementById('dialog-id-destinatario');
            var messageTimeline = document.getElementById('message-timeline');
            var dialogForm = document.getElementById('dialog-form');
            var dialogMensagem = document.getElementById('dialog-mensagem');

            // Certifique-se de que a caixa de diálogo e a sobreposição estejam ocultas ao carregar a página
            dialogBox.style.display = 'none';
            dialogOverlay.style.display = 'none';

            destinatarioLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var destinatarioId = this.getAttribute('data-id');
                    dialogIdDestinatario.value = destinatarioId;

                    // Limpar a timeline de mensagens
                    messageTimeline.innerHTML = '';

                    // Fazer uma requisição AJAX para obter as mensagens trocadas
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'msm.php?id_destinatario=' + destinatarioId, true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var mensagens = JSON.parse(xhr.responseText);
                            mensagens.forEach(function(mensagem) {
                                var li = document.createElement('li');
                                li.classList.add('message'); // Adicione esta linha
                                li.innerHTML = mensagem.mensagem + '<div class="timestamp">' + mensagem.data_envio + '</div>';
                                li.classList.add(mensagem.id_remetente == <?php echo $id_remetente; ?> ? 'sent' : 'received');
                                messageTimeline.appendChild(li);
                            });
                        }
                    };
                    xhr.send();

                    dialogBox.style.display = 'flex';
                    dialogOverlay.style.display = 'block';
                });
            });

            dialogForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(dialogForm);

                // Enviar a mensagem via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'msm.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Atualizar a timeline de mensagens
                        var destinatarioId = dialogIdDestinatario.value;
                        var xhrMessages = new XMLHttpRequest();
                        xhrMessages.open('GET', 'msm.php?id_destinatario=' + destinatarioId, true);
                        xhrMessages.onload = function() {
                            if (xhrMessages.status === 200) {
                                var mensagens = JSON.parse(xhrMessages.responseText);
                                messageTimeline.innerHTML = '';
                                mensagens.forEach(function(mensagem) {
                                    var li = document.createElement('li');
                                    li.classList.add('message'); // Adicione esta linha
                                    li.innerHTML = mensagem.mensagem + '<div class="timestamp">' + mensagem.data_envio + '</div>';
                                    li.classList.add(mensagem.id_remetente == <?php echo $id_remetente; ?> ? 'sent' : 'received');
                                    messageTimeline.appendChild(li);
                                });
                                // Limpar o campo de mensagem
                                dialogMensagem.value = '';
                                // Rolar para a última mensagem
                                messageTimeline.scrollTop = messageTimeline.scrollHeight;
                            }
                        };
                        xhrMessages.send();
                    }
                };
                xhr.send(formData);
            });

            dialogClose.addEventListener('click', function() {
                dialogBox.style.display = 'none';
                dialogOverlay.style.display = 'none';
            });

            dialogOverlay.addEventListener('click', function() {
                dialogBox.style.display = 'none';
                dialogOverlay.style.display = 'none';
            });
        });
    </script>
</body>
</html>