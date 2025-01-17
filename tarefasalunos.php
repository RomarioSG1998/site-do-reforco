<?php
// Inclui o arquivo de conexão
include 'conexao2.php';
include('protect2.php');

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

// Verifica se o formulário de exclusão foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql_delete = "DELETE FROM tarefa WHERE id='$delete_id'";
    
    if ($conexao->query($sql_delete) === TRUE) {
        $sucesso = "Tarefa deletada com sucesso!";
    } else {
        $erro = "Erro ao deletar tarefa: " . $conexao->error;
    }
}

// Consulta SQL para exibir todas as tarefas
$sql_tarefas = "SELECT id, autor, descricao, link, turma, date_start, date_end FROM tarefa WHERE autor = '$autor'";
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
            background-color: #dcdcdc;
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
            width: 100%;
            max-width: 1200px;
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

        @media (max-width: 768px) {
            .container {
            width: 95%;
            padding: 10px;
            }

            th, td {
            padding: 8px 10px;
            }

            button {
            padding: 8px;
            }
        }

        @media (max-width: 480px) {
            h2 {
            font-size: 18px;
            padding: 15px 0;
            }

            label, input, select, textarea, button {
            font-size: 14px;
            }

            th, td {
            padding: 6px 8px;
            }

            button {
            padding: 6px;
            }
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
        <?php
        // Exibir as tarefas em uma tabela
        if ($result_tarefas->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Autor</th><th>Descrição</th><th>Link</th><th>Turma</th><th>Data de Início</th><th>Data de Término</th><th>Ações</th></tr>";
            while($row = $result_tarefas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["autor"] . "</td>";
                echo "<td>" . $row["descricao"] . "</td>";
                echo "<td>" . $row["link"] . "</td>";
                echo "<td>" . $row["turma"] . "</td>";
                echo "<td>" . $row["date_start"] . "</td>";
                echo "<td>" . $row["date_end"] . "</td>";
                echo "<td><form method='POST' action=''><input type='hidden' name='delete_id' value='" . $row["id"] . "'><input type='submit' value='Deletar'></form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhuma tarefa encontrada.";
        }
        ?>
    </div>
<?php
// Verifica se o botão de deletar foi clicado
if (isset($_POST['delete'])) {
    $id_to_delete = $_POST['id_to_delete'];
    $sql_delete = "DELETE FROM tarefa WHERE id='$id_to_delete'";
    
    if ($conexao->query($sql_delete) === TRUE) {
        echo "<p class='message'>Tarefa deletada com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao deletar tarefa: " . $conexao->error . "</p>";
    }
}
?>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja deletar esta tarefa?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>

<?php
// Adiciona o botão de deletar na tabela de tarefas
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
                <td>
                    <form id='delete-form-{$row['id']}' method='POST' action=''>
                        <input type='hidden' name='id_to_delete' value='{$row['id']}'>
                        <button type='button' onclick='confirmDelete({$row['id']})'>Deletar</button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>Nenhuma tarefa cadastrada.</td></tr>";
}
?>
</body>
</html>
