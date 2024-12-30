<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha uma Matéria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #dcdcdc;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 150px;
            height: 50px;
            transition: transform 0.3s, background-color 0.3s;
            border: none;
            border-radius: 5px;
            color: white;
        }
        .button-container button:hover {
            transform: scale(1.1);
            background-color: #555;
        }
        .button-container button:nth-child(1) {
            background: linear-gradient(45deg, #4CAF50, #81C784); /* Green gradient */
        }
        .button-container button:nth-child(2) {
            background: linear-gradient(45deg, #2196F3, #64B5F6); /* Blue gradient */
        }
        .button-container button:nth-child(3) {
            background-color: #f44336; /* Red */
            color: white;
        }
        
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-transform: uppercase;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .inactive-row {
            color: #999;
            opacity: 0.3;
        } 
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.5s;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            position: relative;
            animation: slideIn 0.5s;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        iframe {
            width: 100%;
            height: 400px;
        }
        .dashboard-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            animation: fadeIn 1s;
        }
        .dashboard-list {
            list-style-type: none;
            padding: 0;
        }
        .dashboard-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .dashboard-item:last-child {
            border-bottom: none;
        }
        .dashboard-item strong {
            color: #333;
        }

        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                margin: 0 0 1rem 0;
            }
            tr:nth-child(odd) {
                background: #ccc;
            }
            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                top: 12px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                content: attr(data-label);
                text-align: left;
                font-weight: bold;
            }
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @keyframes slideIn {
            from {transform: translateY(-50px);}
            to {transform: translateY(0);}
        }
        .divider {
            width: 100%;
            height: 50px;
            background: url('path/to/your/divider.svg') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <?php
        include 'conexao2.php';

        $sql = "SELECT id, nome FROM disciplinas";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<button onclick=\"openPopup('disciplina.php?id=" . $row['id'] . "')\">" . $row['nome'] . "</button>";
                
            }
        } else {
            echo "0 results";
        }
         //$conexao->close();
        ?>
        <button onclick="openPopup('cad_disciplina.php')">Nova disciplina</button>
    </div>
    <div class="divider"></div>
<h2>Próximas Aulas</h2>
<div id="dashboard" class="dashboard-container">
    <?php
    $dias_semana = array(
        'Segunda-feira' => 'Monday',
        'Terça-feira' => 'Tuesday',
        'Quarta-feira' => 'Wednesday',
        'Quinta-feira' => 'Thursday',
        'Sexta-feira' => 'Friday'
    );

    $sql = "SELECT nome, dia_aula, hora_aula FROM disciplinas ORDER BY FIELD(dia_aula, 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira')";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul class='dashboard-list'>";
        while ($row = $result->fetch_assoc()) {
            $data_aula = date('d/m/Y', strtotime('next ' . $dias_semana[$row["dia_aula"]]));
            echo "<li class='dashboard-item'><strong>" . $row["nome"] . "</strong> - " . $row["dia_aula"] . " às " . $row["hora_aula"] . " (" . $data_aula . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhuma aula encontrada.</p>";
    }
    ?>
</div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <iframe id="popup-iframe" src="" frameborder="0"></iframe>
        </div>
    </div>
    <script>
        function openPopup(url) {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('popup-iframe').src = url;
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('popup-iframe').src = '';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('popup')) {
                closePopup();
            }
        }

        function atualizarDashboard() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'atualizar_dashboard.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('dashboard').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        setInterval(atualizarDashboard, 604800000); // Atualiza a cada semana (7 dias * 24 horas * 60 minutos * 60 segundos * 1000 milissegundos)
        atualizarDashboard(); 
    </script>
    <h2>Alunos da Turma PB</h2>
    <?php
    include 'conexao2.php';

    $sql = "SELECT ra, nome, datanasc, celular, responsavel, genero, turma, situacao FROM alunos WHERE turma = 'PB'";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>RA</th><th>Nome</th><th>Data de Nascimento</th><th>Celular</th><th>Responsável</th><th>Gênero</th><th>Turma</th><th>Situação</th></tr>";
        while($row = $result->fetch_assoc()) {
            $rowClass = $row["situacao"] == 'Inativo' ? 'class="inactive-row"' : '';
            echo "<tr $rowClass><td>" . $row["ra"]. "</td><td>" . $row["nome"]. "</td><td>" . $row["datanasc"]. "</td><td>" . $row["celular"]. "</td><td>" . $row["responsavel"]. "</td><td>" . $row["genero"]. "</td><td>" . $row["turma"]. "</td><td>" . $row["situacao"]. "</td></tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "0 results";
    }
    // Remova ou comente a linha abaixo se a conexão for necessária em outro lugar
    $conexao->close();
    ?>
    
    
</body>
</html>