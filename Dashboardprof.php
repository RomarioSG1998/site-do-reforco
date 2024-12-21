<?php include('protect1.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Professor</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo Geral */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Cabeçalho */
        .header {
            background-color: #4CAF50;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .logout {
            color: #ffcccc;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex: 1;
        }

        /* Barra Lateral */
        .sidebar {
            width: 20%;
            background-color: #f4f4f4;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li a {
            display: block;
            color: #333;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #ddd;
        }

        /* Conteúdo Principal */
        .content {
            flex: 1;
            padding: 20px;
        }

        .cards {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #4CAF50;
        }

        /* Rodapé */
        .footer {
            text-align: center;
            padding: 10px;
            background-color: none;
            color: black;
            margin-bottom: 5px;
        }

        /* Cores para datas */
        .data-inicio {
            color: green;
        }

        .data-fim {
            color: red;
        }
        .form-container {
    display: none;
    position: fixed; /* Torna o formulário fixo na tela */
    top: 20%; /* Ajuste a posição vertical conforme necessário */
    left: 50%;
    transform: translateX(-50%); /* Centraliza o formulário horizontalmente */
    border: 1px solid #ddd;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    width: 250px;
    max-width: 100%;
    z-index: 1000; /* Garante que o formulário fique acima de outros elementos */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para destaque */
}


        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            color: green;
        }
    </style>
    <script>
       function toggleForm() {
    const formContainer = document.getElementById('form-container');
    formContainer.style.display = formContainer.style.display === 'none' || formContainer.style.display === '' ? 'block' : 'none';
}

// Função para enviar o formulário via AJAX
function submitForm(event) {
    event.preventDefault(); // Impede o comportamento padrão do formulário

    // Criação de um objeto FormData com os dados do formulário
    const formData = new FormData(document.querySelector('form'));

    // Envia os dados via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'processa_notifica.php', true);
    
    // Função que é chamada quando a requisição termina
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Exibe a mensagem de sucesso ou erro
            document.getElementById('message').innerHTML = xhr.responseText;
            // Limpa o formulário
            document.querySelector('form').reset();
            // Recarrega a página para atualizar as notificações
            window.location.reload();
        } else {
            document.getElementById('message').innerHTML = 'Erro ao enviar o formulário!';
        }
    };

    // Envia os dados
    xhr.send(formData);
}

    </script>
</head>
<body>
    <?php
    include 'conexao2.php';

    // Consulta para contar os alunos da turma "PB"
    $queryAlunos = "SELECT COUNT(*) AS total FROM alunos WHERE turma = 'PB'";
    $resultAlunos = $conexao->query($queryAlunos);
    $totalAlunosPB = ($resultAlunos->num_rows > 0) ? $resultAlunos->fetch_assoc()['total'] : 0;

    // Consulta para obter as notificações com prazo final
    $queryNotificacoes = "SELECT autor, descricao, 
                                 DATE_FORMAT(date_start, '%d/%m/%Y') AS data_inicio, 
                                 DATE_FORMAT(date_end, '%d/%m/%Y') AS data_fim 
                          FROM notifica 
                          WHERE CURDATE() BETWEEN date_start AND date_end 
                          ORDER BY date_start DESC LIMIT 5";
    $resultNotificacoes = $conexao->query($queryNotificacoes);
    ?>
    <main class="main-content">
        <section class="content">
            <div class="cards">
                <div class="card">
                    <h2>Total de Alunos</h2>
                    <p><?php echo $totalAlunosPB; ?></p>
                </div>
                <div class="card">
                    <h2>Próxima Aula</h2>
                    <p>Matemática - 14:00</p>
                </div>
                <div class="card">
                    <h2>Tarefas Pendentes</h2>
                    <p>5 Tarefas</p>
                </div>
            </div>
            <div class="section">
            <div class="container">
        <button onclick="toggleForm()">Adicionar Registro</button>
        <div id="form-container" class="form-container">
    <form onsubmit="submitForm(event)">
        <div>
            <label for="autor">Autor:</label><br>
            <input type="text" id="autor" name="autor" value="<?php echo $_SESSION['nome']; ?>" readonly required>
        </div>
        <div>
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>
        </div>
        <div>
            <label for="destinatario">Destinatário:</label><br>
            <input type="text" id="destinatario" name="destinatario" required>
        </div>
        <div>
            <label for="date_start">Data de Início:</label><br>
            <input type="date" id="date_start" name="date_start" required>
        </div>
        <div>
            <label for="date_end">Data de Término:</label><br>
            <input type="date" id="date_end" name="date_end">
        </div>
        <div style="margin-top: 20px;">
            <button type="submit">Salvar Registro</button>
        </div>
    </form>
    <!-- Mensagem de sucesso ou erro -->
    <div id="message" class="message"></div>
    <!-- Botão de Fechar -->
    <button type="button" onclick="toggleForm()">Fechar</button>
</div>

    </div>
                <h2>Últimas Notificações</h2>
                <ul>
                    <?php if ($resultNotificacoes->num_rows > 0): ?>
                        <?php while ($notificacao = $resultNotificacoes->fetch_assoc()): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($notificacao['autor']); ?>:</strong>
                                <?php echo htmlspecialchars($notificacao['descricao']); ?>
                                <span class="data-inicio">
                                    <?php echo htmlspecialchars($notificacao['data_inicio']); ?>
                                </span>
                                <span class="data-fim">
                                    <?php echo htmlspecialchars($notificacao['data_fim']); ?>
                                </span>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>Não há notificações no momento.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </main>
</body>
<footer class="footer">
    <p>&copy; 2024 Reforço Sede do Saber. Todos os direitos reservados.</p>
</footer>
</html>
