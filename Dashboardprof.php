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
    </style>
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
