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
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
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
            cursor: pointer;
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        /* Rodapé da tabela */
        tfoot td {
            font-weight: bold;
            background-color: #f4f4f4;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Lista de Alunos - Turma PB</h2>
    <table>
        <thead>
            <tr>
                <th>RA</th>
                <th>Nome</th>
                <th>Celular</th>
                <th>Responsável</th>
                <th>Turma</th>
                <th>Situação</th>
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
                echo "<tr><td colspan='6' style='text-align:center;'>Nenhum aluno encontrado na turma PB.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
