<?php
include 'conexao2.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $sql = "DELETE FROM disciplinas WHERE id = $id";
        if (mysqli_query($conexao, $sql)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $sql . '<br>' . mysqli_error($conexao)]);
        }
        exit;
    } else {
        $nome = $_POST['nome'];
        $professor = $_POST['professor'];
        $discricao = $_POST['discricao'];
        $dia_aula = $_POST['dia_aula'];
        $hora_aula = $_POST['hora_aula'];

        $sql = "INSERT INTO disciplinas (nome, professor, discricao, dia_aula, hora_aula) VALUES ('$nome', '$professor', '$discricao', '$dia_aula', '$hora_aula')";
        if (mysqli_query($conexao, $sql)) {
            echo json_encode(['status' => 'success', 'data' => ['id' => mysqli_insert_id($conexao), 'nome' => $nome, 'professor' => $professor, 'discricao' => $discricao, 'dia_aula' => $dia_aula, 'hora_aula' => $hora_aula]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $sql . '<br>' . mysqli_error($conexao)]);
        }
        exit;
    }
}

// Fetch existing records
$disciplinas = [];
$sql = "SELECT * FROM disciplinas";
$result = mysqli_query($conexao, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $disciplinas[] = $row;
    }
}

// Fetch professors
$professores = [];
$sql = "SELECT nome FROM usuarios";
$result = mysqli_query($conexao, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $professores[] = $row['nome'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disciplinas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Cadastro  de Disciplinas</h2>
    <div id="alert" class="alert d-none"></div>
    <form id="disciplinaForm" method="POST">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group col-md-2">
                <label for="professor">Professor</label>
                <select class="form-control" id="professor" name="professor" required>
                    <?php foreach ($professores as $professor): ?>
                        <option value="<?php echo $professor; ?>"><?php echo $professor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="discricao">Descrição</label>
                <input type="text" class="form-control" id="discricao" name="discricao" required>
            </div>
            <div class="form-group col-md-2">
                <label for="dia_aula">Dia da Aula</label>
                <select class="form-control" id="dia_aula" name="dia_aula" required>
                    <option value="Segunda-feira">Segunda-feira</option>
                    <option value="Terça-feira">Terça-feira</option>
                    <option value="Quarta-feira">Quarta-feira</option>
                    <option value="Quinta-feira">Quinta-feira</option>
                    <option value="Sexta-feira">Sexta-feira</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="hora_aula">Hora da Aula</label>
                <input type="time" class="form-control" id="hora_aula" name="hora_aula" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
    <h3>Disciplinas Cadastradas</h3>
    <table class="table mt-4" id="disciplinaTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Professor</th>
                <th>Descrição</th>
                <th>Dia da Aula</th>
                <th>Hora da Aula</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($disciplinas as $disciplina): ?>
                <tr data-id="<?php echo $disciplina['id']; ?>">
                    <td><?php echo $disciplina['id']; ?></td>
                    <td><?php echo $disciplina['nome']; ?></td>
                    <td><?php echo $disciplina['professor']; ?></td>
                    <td><?php echo $disciplina['discricao']; ?></td>
                    <td><?php echo $disciplina['dia_aula']; ?></td>
                    <td><?php echo $disciplina['hora_aula']; ?></td>
                    <td><button class="btn btn-danger btn-sm delete-btn">Apagar</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#disciplinaForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '',
            data: $(this).serialize(),
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == 'success') {
                    $('#disciplinaTable tbody').append('<tr data-id="' + res.data.id + '"><td>' + res.data.id + '</td><td>' + res.data.nome + '</td><td>' + res.data.professor + '</td><td>' + res.data.discricao + '</td><td>' + res.data.dia_aula + '</td><td>' + res.data.hora_aula + '</td><td><button class="btn btn-danger btn-sm delete-btn">Apagar</button></td></tr>');
                    $('#alert').removeClass('d-none alert-danger').addClass('alert-success').text('Disciplina adicionada com sucesso!');
                } else {
                    $('#alert').removeClass('d-none alert-success').addClass('alert-danger').text('Erro ao adicionar disciplina.');
                }
                $('#disciplinaForm')[0].reset();
            }
        });
    });

    $('#disciplinaTable').on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');
        $.ajax({
            type: 'POST',
            url: '',
            data: { delete_id: id },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == 'success') {
                    row.remove();
                    $('#alert').removeClass('d-none alert-danger').addClass('alert-success').text('Disciplina apagada com sucesso!');
                } else {
                    $('#alert').removeClass('d-none alert-success').addClass('alert-danger').text('Erro ao apagar disciplina.');
                }
            }
        });
    });
});
</script>
</body>
</html>