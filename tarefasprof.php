<?php
// Inclui o arquivo de conexão
include 'conexao2.php';

// Definir variáveis e inicializar com valores vazios
$descricao = $link = $turma = $date_end = "";
$erro = "";

// Supõe que a autenticação do usuário é feita e o nome do usuário logado está armazenado na variável $usuario_logado
$autor = $_SESSION['nome'];  // Aqui você substitui isso com a variável real do usuário logado, por exemplo, $_SESSION['nome'].

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['descricao']) && !empty($_POST['link']) && !empty($_POST['turma']) && !empty($_POST['date_end'])) {
        // Coleta os dados do formulário
        $descricao = $_POST['descricao'];
        $link = $_POST['link'];
        $turma = $_POST['turma'];
        $date_end = $_POST['date_end'];
        $date_start = date('Y-m-d');  // Data de início automática, com a data atual

        // Insere os dados na tabela 'tarefa'
        $sql = "INSERT INTO tarefa (autor, descricao, link, turma, date_start, date_end) VALUES ('$autor', '$descricao', '$link', '$turma', '$date_start', '$date_end')";
        
        if ($conexao->query($sql) === TRUE) {
            $sucesso = "Tarefa cadastrada com sucesso!";
        } else {
            $erro = "Erro ao cadastrar tarefa: " . $conexao->error;
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

// Consulta SQL para exibir todas as tarefas
$sql_tarefas = "SELECT id, autor, descricao, link, turma, date_start, date_end FROM tarefa";
$result_tarefas = $conexao->query($sql_tarefas);

// Consulta para pegar todas as turmas únicas da tabela 'alunos'
$sql_alunos = "SELECT DISTINCT turma FROM alunos"; // Usando DISTINCT para evitar turmas repetidas
$result_alunos = $conexao->query($sql_alunos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tarefa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            margin: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input, select, textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        .message {
            text-align: center;
            font-size: 18px;
            color: green;
        }

        .error {
            text-align: center;
            font-size: 18px;
            color: red;
        }

    </style>
</head>
<body>

    <div class="container">
        <!-- Formulário de cadastro de tarefas -->
        <h2>Cadastro de Tarefa</h2>
        <form method="POST" action="">
            <label for="autor">Autor</label>
            <input type="text" id="autor" name="autor" value="<?php echo $autor; ?>" readonly>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4" required><?php echo $descricao; ?></textarea>

            <label for="link">Link</label>
            <input type="url" id="link" name="link" value="<?php echo $link; ?>" required>

            <label for="turma">Turma</label>
            <select id="turma" name="turma" required>
                <option value="">Selecione a turma</option>
                <?php
                if ($result_alunos->num_rows > 0) {
                    while ($row = $result_alunos->fetch_assoc()) {
                        echo "<option value='{$row['turma']}'>{$row['turma']}</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma turma encontrada</option>";
                }
                ?>
            </select>

            <label for="date_end">Data de Fim</label>
            <input type="date" id="date_end" name="date_end" value="<?php echo $date_end; ?>" required>

            <button type="submit">Cadastrar</button>
        </form>

        <?php
        if (isset($sucesso)) {
            echo "<p class='message'>$sucesso</p>";
        }
        if (isset($erro)) {
            echo "<p class='error'>$erro</p>";
        }
        ?>
    </div>

    <div class="container">
        <!-- Exibição das tarefas cadastradas -->
        <h2>Tarefas Cadastradas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autor</th>
                    <th>Descrição</th>
                    <th>Link</th>
                    <th>Turma</th>
                    <th>Data de Início</th>
                    <th>Data de Fim</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_tarefas->num_rows > 0) {
                    while ($row = $result_tarefas->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['autor']}</td>
                                <td>{$row['descricao']}</td>
                                <td><a href='{$row['link']}' target='_blank'>Link</a></td>
                                <td>{$row['turma']}</td>
                                <td>{$row['date_start']}</td>
                                <td>{$row['date_end']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>Nenhuma tarefa cadastrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
