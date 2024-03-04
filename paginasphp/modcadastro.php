<?php
include('protect.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Dados da Tabela</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Estilo para os ícones */
        .icon {
            font-size: 18px;
            margin-right: 5px;
            cursor: pointer; /* Adicionando estilo de cursor para indicar que é clicável */
        }
        
        .titulo {
            text-align: center;
        }

        /* Estilo para tornar a tabela responsiva */
        @media only screen and (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }
            
            /* Ajuste das colunas para proporção da largura da tela */
            th, td {
                width: 100%;
            }
        }

        /* Estilo para o link 'Menu' */
        .menu-link {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: purple;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Estilo para a célula expandida */
        .zoomed-cell {
            transform: scale(2); /* Definindo o nível de zoom */
            transition: transform 0.3s ease; /* Adicionando uma transição suave */
        }
    </style>
</head>
<body>
<h1 class="titulo">Alunos Matriculados</h1> 
    <a href="./painel.php" class="menu-link">Menu</a>
    
    <!-- Exibição de mensagens de exclusão bem-sucedida ou falha -->
    <?php if(isset($_GET['exclusao_sucesso'])): ?>
        <p style="color: green;">Registro excluído com sucesso!</p>
    <?php elseif(isset($_GET['exclusao_erro'])): ?>
        <p style="color: red;">Erro ao excluir o registro.</p>
    <?php endif; ?>
    
    <table id="alunosTable">
        <tr>
            <th>RA</th>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th class="zoomable">Celular</th> <!-- Adicionando classe 'zoomable' à célula -->
            <th>Responsável</th>
            <th>Gênero</th>
            <th>Turma</th>
            <th>Ações</th> <!-- Nova coluna para as ações -->
        </tr>
        <?php
        $hostname = "localhost";
        $bancodedados = "sistemadoreforco";
        $usuario = "root";
        $senha = "";

        // Cria a conexão com o banco de dados
        $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

        // Verifica se há erro na conexão
        if ($conexao->connect_error) {
            die("Erro na conexão: " . $conexao->connect_error);
        }

        // Consulta SQL para obter os dados da tabela
        $sql = "SELECT * FROM alunos";
        $result = $conexao->query($sql);

        // Verifica se a consulta foi bem-sucedida
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["ra"]."</td>";
                echo "<td>".$row["nome"]."</td>";
                echo "<td>".$row["datanasc"]."</td>";
                echo "<td>".$row["celular"]."</td>";
                echo "<td>".$row["responsavel"]."</td>";
                echo "<td>".$row["genero"]."</td>";
                echo "<td>".$row["turma"]."</td>";
                // Adicionando ícones de editar e apagar como links clicáveis
                echo "<td>";
                if(isset($row["id"])) {
                    echo "<a href='editar.php?id=".$row["id"]."'><i class='fas fa-edit icon'></i></a> | ";
                    echo "<a href='apagar.php?id=".$row["id"]."'><i class='fas fa-trash-alt icon'></i></a>";
                } else {
                    echo "<i class='fas fa-edit icon'></i> | <i class='fas fa-trash-alt icon'></i>";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>0 resultados</td></tr>";
        }

        // Fecha a conexão com o banco de dados
        $conexao->close();
        ?>
    </table>

    <script>
        // Adicionando evento de clique para cada célula com a classe 'zoomable'
        var cells = document.querySelectorAll('.zoomable');
        cells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                cell.classList.toggle('zoomed-cell'); // Alternando a classe de zoom ao clicar
            });
        });
    </script>
</body>
</html>
