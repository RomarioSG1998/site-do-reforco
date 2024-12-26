<?php
session_start();
include 'conexao2.php';

// Verifique se a variável de sessão está definida
if(isset($_SESSION['nome'])) {
    // Pegue o nome do usuário logado
    $nome_usuario = $_SESSION['nome'];

    // Atualize a descrição se o formulário for enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['descricao']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $descricao = $_POST['descricao'];

        $sql_update = "UPDATE disciplinas SET discricao = ? WHERE id = ?";
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bind_param("si", $descricao, $id);

        if ($stmt_update->execute()) {
            echo "Descrição atualizada com sucesso.";
        } else {
            echo "Erro ao atualizar a descrição.";
        }

        $stmt_update->close();
    }

    $sql = "SELECT * FROM disciplinas WHERE professor = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $nome_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disciplinas do Professor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Disciplinas do Professor</h3>
        <?php
            if ($result->num_rows > 0) {
                echo '<div class="row">';
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['nome']) . '</h5>';
                    echo '<p class="card-text"><strong>ID:</strong> ' . htmlspecialchars($row['id']) . '</p>';
                    echo '<p class="card-text"><strong>Professor:</strong> ' . htmlspecialchars($row['professor']) . '</p>';
                    echo '<p class="card-text"><strong>Dia da Aula:</strong> ' . htmlspecialchars($row['dia_aula']) . '</p>';
                    echo '<p class="card-text"><strong>Hora da Aula:</strong> ' . htmlspecialchars($row['hora_aula']) . '</p>';
                    echo '<form method="post" action="">';
                    echo '<div class="form-group">';
                    echo '<label for="descricao">Descrição:</label>';
                    echo '<textarea class="form-control" id="descricao" name="descricao">' . htmlspecialchars($row['discricao']) . '</textarea>';
                    echo '</div>';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="submit" class="btn btn-primary">Salvar</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<p>Nenhuma disciplina encontrada.</p>';
            }
        } else {
            echo '<p>Você precisa estar logado para ver as disciplinas.</p>';
        }
        ?>
    </div>
</body>
</html>
<?php
    $stmt->close();
    $conexao->close();

?>