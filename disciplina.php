<?php
include "conexao2.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $dia_aula = $_POST['dia_aula'];
    $hora_aula = $_POST['hora_aula'];

    $sql = "UPDATE disciplinas SET dia_aula = ?, hora_aula = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('ssi', $dia_aula, $hora_aula, $id);

    if ($stmt->execute()) {
        echo "Disciplina atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar a disciplina.";
    }

    $stmt->close();
    $conexao->close();
    exit;
}

// Obtém o ID da disciplina a partir dos parâmetros da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações da Disciplina</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .container:hover {
            transform: translateY(-5px);
        }
        h1 {
            color: #00796b;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            color: #616161;
            line-height: 1.8;
            margin-bottom: 10px;
        }
        p strong {
            color: #000;
        }
        #dashboard {
            margin-top: 20px;
            width: 100%;
            max-width: 700px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($id > 0) {
            // Consulta SQL para obter as informações da disciplina
            $sql = "SELECT id, nome, professor, dia_aula, hora_aula FROM disciplinas WHERE id = $id";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                // Exibe as informações da disciplina
                while ($row = $result->fetch_assoc()) {
                    echo "<h1>Informações da Disciplina</h1>";
                    echo "<p><strong>ID:</strong> " . $row["id"] . "</p>";
                    echo "<p><strong>Nome:</strong> " . $row["nome"] . "</p>";
                    echo "<p><strong>Professor:</strong> " . $row["professor"] . "</p>";
                    echo "<p><strong>Dia da Aula:</strong> " . $row["dia_aula"] . "</p>";
                    echo "<p><strong>Hora da Aula:</strong> " . $row["hora_aula"] . "</p>";
                    echo "<form id='updateForm'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<p><strong>Atualizar Dia da Aula:</strong> <select name='dia_aula'>";
                    $dias = ["Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira"];
                    foreach ($dias as $dia) {
                        $selected = $row["dia_aula"] == $dia ? "selected" : "";
                        echo "<option value='$dia' $selected>$dia</option>";
                    }
                    echo "</select></p>";
                    echo "<p><strong>Atualizar Hora da Aula:</strong> <input type='time' name='hora_aula' value='" . $row["hora_aula"] . "'></p>";
                    echo "<p><input type='button' onclick='atualizarDisciplina()' value='Atualizar'></p>";
                    echo "</form>";
                }
            } else {
                echo "<p>Nenhuma disciplina encontrada com o ID fornecido.</p>";
            }
        } else {
            echo "<p>ID inválido.</p>";
        }
        ?>
    </div>
    <script>
        function atualizarDisciplina() {
            var form = document.getElementById('updateForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Disciplina atualizada com sucesso!');
                } else {
                    alert('Erro ao atualizar a disciplina.');
                }
            };
            xhr.send(new URLSearchParams(formData).toString());
        }
// Atualiza imediatamente ao carregar a página
    </script>
</body>
</html>
