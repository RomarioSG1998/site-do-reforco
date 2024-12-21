<?php 

include('conexao2.php');
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
        /* Estilos gerais */
        body {
            background-image: url("./imagens/111.png");
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
            color:white; /* Define a cor do texto */
            text-shadow: 
        }

        /* Estilos para a imagem */
        .cadastro-imagem {
            display: block;
            margin: 0 auto;
            max-width: 10%;
            margin-top: -25px;
            margin-bottom: 7px;
        }

        .table-container {
            max-height: 500px; /* Defina a altura máxima desejada */
            overflow-y: auto; /* Adiciona barra de rolagem vertical quando necessário */
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            table-layout: fixed;
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

        @media only screen and (max-width: 750px) {
            table, th, td {
                font-size: 14px;
                width: auto; 
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

        /* Adiciona estilo para linhas com a classe 'inativo' */
        tr.inativo td {
            background-color: #ff1b1b; /* Vermelho claro */
        }

        .inativo {
            background-color: #f8d7da; /* Cor de destaque para alunos inativos */
        }

        /* Estilo do popup (modal) */
        .modal {
            display: none; /* Escondido por padrão */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fundo escuro com transparência */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 90%; /* Largura para caber em telas de celular */
            max-width: 500px; /* Largura máxima em telas maiores */
            text-align: center;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #000;
            cursor: pointer;
        }

        .modal iframe {
            width: 100%;
            height: 400px;
            border: none;
        }
    </style>
    <script>
        // Função para abrir o popup (modal)
        function abrirJanela(url) {
            const modal = document.getElementById('modal');
            const iframe = document.getElementById('modal-iframe');

            // Configura o iframe com o URL desejado
            iframe.src = url;

            // Exibe o modal
            modal.style.display = 'flex';
        }

        // Função para fechar o popup (modal)
        function fecharJanela() {
            const modal = document.getElementById('modal');
            const iframe = document.getElementById('modal-iframe');

            // Esconde o modal
            modal.style.display = 'none';

            // Limpa o conteúdo do iframe
            iframe.src = '';

            // Atualiza a página após fechar o popup
            window.location.reload();
        }
    </script>
</head>
<body>
<div class="content">
    <p class="cadastro-frase">ALTERAR/EXCLUIR MATRÍCULAS</p>
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>">
        <img class="cadastro-imagem" src="./imagens/logo sem fundo1.png" alt="Descrição da imagem">
    </a>
</div>

<div class="search-container">
    <form method="GET" action="">
        <input type="hidden" name="id_aluno" value="<?php echo isset($_GET['id_aluno']) ? $_GET['id_aluno'] : ''; ?>">
        <input type="text" name="search" class="search-input" placeholder="Buscar por RA ou nome do aluno" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="search-btn">Buscar</button>
    </form>
    <a href="#" onclick="generatePDF()" class="print-icon"><i class="fas fa-print"></i></a>
</div>

<?php if(isset($_GET['exclusao_sucesso'])): ?>
    <p style="color: green;">Registro excluído com sucesso!</p>
<?php elseif(isset($_GET['exclusao_erro'])): ?>
    <p style="color: red;">Erro ao excluir o registro.</p>
<?php endif; ?>

<div class="table-container">
    <table id="alunosTable">
        <tr>
            <th>RA</th>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Celular</th>
            <th>Responsável</th>
            <th>Gênero</th>
            <th>Turma</th>
            <th>Situação</th>
            <th>Ações</th>
        </tr>
        <?php
        $hostname = "localhost";
        $bancodedados = "id21964020_sistemadoreforco";
        $usuario = "root";
        $senha = "";

        $conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

        if ($conexao->connect_error) {
            die("Erro na conexão: " . $conexao->connect_error);
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $sql = "SELECT * FROM alunos WHERE ra LIKE '%$search%' OR nome LIKE '%$search%'";
        $result = $conexao->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rowClass = ($row['situacao'] == 'Inativo') ? 'inativo' : '';
                echo "<tr class='$rowClass'>";
                echo "<td><a href='mensalidade.php?ra=".$row["ra"]."'>".$row["ra"]."</a></td>";
                echo "<td>".$row["nome"]."</td>";
                echo "<td>".$row["datanasc"]."</td>";
                echo "<td>".$row["celular"]."</td>";
                echo "<td>".$row["responsavel"]."</td>";
                echo "<td>".$row["genero"]."</td>";
                echo "<td>".$row["turma"]."</td>";
                echo "<td>".$row["situacao"]."</td>";
                echo "<td>";
                echo "<a href='#' onclick=\"abrirJanela('editar.php?ra=" . $row['ra'] . "')\"><i class='fas fa-edit icon edit-icon'></i></a> | ";
                echo "<a href='#' onclick=\"abrirJanela('apagar.php?ra=" . $row['ra'] . "')\"><i class='fas fa-trash icon delete-icon'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>Nenhum aluno encontrado.</td></tr>";
        }
        $conexao->close();
        ?>
    </table>
</div>

<!-- Modal para o popup -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fecharJanela()">&times;</span>
        <iframe id="modal-iframe"></iframe>
    </div>
</div>
</body>
</html>
