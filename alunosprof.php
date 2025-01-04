<?php
// Inclui o arquivo de conexão
include 'conexao2.php';
include('protect1.php'); 

// Consulta SQL para buscar alunos apenas da turma PB
$sql = "SELECT ra, nome, celular, responsavel, turma, situacao FROM alunos WHERE turma = 'PB'";
$result = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos - Turma PB</title>
    <style>
        /* Fonte e cores */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #dcdcdc;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho */
        h2 {
            text-align: center;
            background-color: #44397d;
            color: white;
            padding: 20px 0;
            margin: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Tabela */
        .table-container {
            overflow-x: auto;
            margin: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            min-width: 600px; /* minimum width before scrolling */
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #44397d;
            color: white;
            white-space: nowrap;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        @media screen and (max-width: 600px) {
            .table-container {
                margin: 10px;
            }
            
            th, td {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h2>Lista de Alunos - Turma PB</h2>
    <!-- Wrap your table with this container -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>RA</th>
                    <th>Nome</th>
                    <th>Celular</th>
                    <th>Responsável</th>
                    <th>Turma</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Exibe cada linha da tabela
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['ra']}</td>
                                <td>{$row['nome']}</td>
                                <td>{$row['celular']}</td>
                                <td>{$row['responsavel']}</td>
                                <td>{$row['turma']}</td>
                                <td>{$row['situacao']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>Nenhum aluno encontrado na turma PB.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
