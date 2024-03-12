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
            background-color: #f2f2f2;
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
<h1 class="titulo">Alunos Matriculados</h1> 
<a href="./painel.php" class="menu-link">
    <div style="background-color: white; padding: 5px; border-radius: 50%;">
        <img src="../imagens/logo sem fundo2.png" alt="Menu" style="width: 50px; height: 50px;">
    </div>
</a>

<div class="search-container">
<input type="text" id="searchInput" class="search-input" placeholder="Buscar por RA ou nome do aluno" value="<?php echo isset($_GET['id_aluno']) ? $_GET['id_aluno'] : ''; ?>">
    <button onclick="searchTable()" class="search-btn">Buscar</button>
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
$usuario = "root";
$senha = "";

$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$sql = "SELECT * FROM alunos";
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
    var cells = document.querySelectorAll('.zoomable');
    cells.forEach(function(cell) {
        cell.addEventListener('click', function() {
            cell.classList.toggle('zoomed-cell');
        });
    });

    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("alunosTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (var j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function generatePDF() {
        var doc = new jsPDF();
        doc.autoTable({html: '#alunosTable'});
        // Obtém o link de download
        var downloadLink = document.getElementById('download-link');
        // Define o conteúdo do PDF como a string Base64 do documento
        downloadLink.href = doc.output('datauristring');
        // Aciona o clique no link de download
        downloadLink.click();
    }
</script>
</body>
</html>
