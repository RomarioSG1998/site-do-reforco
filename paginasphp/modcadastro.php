<?php 
    include('conexao2.php');
    include('admin.php');
    include('protect.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Dados da Tabela</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        body {
            
            background-image: url("../imagens/111.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .cadastro-frase {
            font-size: 35px;
            font-family: 'Tahoma', sans-serif;
            font-weight: bold;
            text-align:center;
            margin-bottom: 20px;
            color:#44277D; /* Define a cor do texto */
            text-shadow: 
                -2px -2px 0 white,  
                2px -2px 0 white,
                -2px 2px 0 white,
                2px 2px 0 white;
        }

        /* Estilos para a imagem */
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 10%;
            margin-top: -25px;
            margin-bottom: 7px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #fff;
            padding: 8px;
        }

        th {
            background-color: #d2cdf0; /* lilás claro */
        }

        th, td {
            color: #000; /* Preto */
        }

        /* Cor de fundo para células de colunas pares */
        tr:nth-child(even) td {
            background-color: #cccccc; /* Cor de fundo mais escura para células de colunas pares */
        }

        /* Cor de fundo para células de colunas ímpares */
        tr:nth-child(odd) td {
            background-color: #ffffff; /* Cor de fundo para células de colunas ímpares */
        }

        .icon {
            font-size: 18px;
            margin-right: 5px;
            cursor: pointer; /* Adicionando estilo de cursor para indicar que é clicável */
        }
        
        .titulo {
            text-align: center;
            color: #6a5acd; /* lilás médio */
            margin-top: 20px;
        }

        @media only screen and (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }
            
            th, td {
                width: 100%;
            }
        }

        .menu-link {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #6a5acd; /* lilás médio */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .menu-link:hover {
            background-color: #836FFF; /* lilás mais escuro */
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 5px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 5px;
        }

        .search-btn {
            padding: 5px 10px;
            background-color: #6a5acd; /* lilás médio */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #836FFF; /* lilás mais escuro */
        }

        .edit-icon {
            color: green; /* Verde para ícone de edição */
        }

        .delete-icon {
            color: red; /* Vermelho para ícone de lixeira */
        }

        .print-icon {
            color: #0000FF; /* Azul para ícone de impressão */
        }

        /* Oculta o link de download */
        #download-link {
            display: none;
        }
    </style>
</head>
<body>
<div class="content">
    <p class="cadastro-frase">CADASTRO DO ALUNO/A:</p>
    <a href="./pageadmin.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="../imagens/logo sem fundo1.png" alt="Descrição da imagem">
    </a>
</div>

    <div class="search-container">
        <form method="GET" action="">
            <!-- Adiciona um campo hidden para o id_aluno -->
            <input type="hidden" name="id_aluno" value="<?php echo isset($_GET['id_aluno']) ? $_GET['id_aluno'] : ''; ?>">
            <input type="text" name="search" class="search-input" placeholder="Buscar por RA ou nome do aluno" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="search-btn">Buscar</button>
        </form>
        <!-- Adiciona um link oculto para download do PDF -->
        <a id="download-link" download="alunos.pdf"></a>
        <a href="#" onclick="generatePDF()" class="print-icon"><i class="fas fa-print"></i></a>
    </div>

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
            <th class="zoomable">Celular</th>
            <th>Responsável</th>
            <th>Gênero</th>
            <th>Turma</th>
            <th>Ações</th>
        </tr>
        <?php
        $hostname = "localhost";
        $bancodedados = "id21964020_sistemadoreforco";
        $usuario = "id21964020_root";
        $senha = "J3anlak#1274";

        $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

        if ($conexao->connect_error) {
            die("Erro na conexão: " . $conexao->connect_error);
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Consulta SQL modificada para incluir pesquisa por RA ou nome
        $sql = "SELECT * FROM alunos WHERE ra LIKE '%$search%' OR nome LIKE '%$search%'";
        $result = $conexao->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                // Modificação aqui: o número do aluno (RA) agora é um link para mensalidade.php
                echo "<td><a href='mensalidade.php?ra=".$row["ra"]."'>".$row["ra"]."</a></td>"; 
                echo "<td>".$row["nome"]."</td>";
                echo "<td>".$row["datanasc"]."</td>";
                echo "<td>".$row["celular"]."</td>";
                echo "<td>".$row["responsavel"]."</td>";
                echo "<td>".$row["genero"]."</td>";
                echo "<td>".$row["turma"]."</td>";
                echo "<td>";
                if(isset($row["ra"])) {
                    echo "<a href='editar.php?ra=".$row["ra"]."'><i class='fas fa-edit icon edit-icon'></i></a> | ";
                    echo "<a href='apagar.php?ra=".$row["ra"]."'><i class='fas fa-trash-alt icon delete-icon'></i></a>";
                } else {
                    echo "<i class='fas fa-edit icon edit-icon'></i> | <i class='fas fa-trash-alt icon delete-icon'></i>";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>0 resultados</td></tr>";
        }

        $conexao->close();
        ?>
    </table>

    <script>
        // Função JavaScript não precisa de alteração
    </script>
</body>
</html>
