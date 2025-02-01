<?php
include 'pass.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre o Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dcdcdc;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .header-image {
            width: 100%;
            height: auto;
        }
        header {
            background:rgba(68, 39, 125, 0.8) !important;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77aaff 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        #main {
            padding: 20px;
            background: #fff;
            margin-top: 20px;
        }
        h1 {
            color: rgba(68, 39, 125, 0.8) !important;
            font-size: 4.5vw;
        }
        #main h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        form input[type="text"],
        form input[type="radio"],
        form textarea,
        form select {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            display: block;
            width: 100%;
            background: rgba(68, 39, 125, 0.8) !important;
            color: white;
            border: 0;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        form button:hover {
            background:black;
        }
    </style>
    <script>
        function showMonths() {
            document.getElementById('monthsDropdown').style.display = 'block';
        }

        function showPaymentStatus() {
            document.getElementById('paymentStatus').style.display = 'block';
        }

        function showObservationField() {
            document.getElementById('observationField').style.display = 'block';
        }

        function toggleSubmitButton() {
            var pago = document.getElementById('pago').checked;
            var naoPago = document.getElementById('naoPago').checked;
            var submitButton = document.getElementById('submitButton');
            
            if (pago || naoPago) {
                submitButton.style.display = 'block';
            } else {
                submitButton.style.display = 'none';
            }
        }
        function fetchObservation() {
        const alunoId = document.getElementById('students').value;
        const mes = document.getElementById('months').value;

        if (alunoId && mes) {
            fetch(`fetch_observacao.php?id_aluno=${alunoId}&mes=${mes}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('observacao').value = data;
                })
                .catch(error => {
                    console.error("Erro ao buscar observação:", error);
                });
        }
    }
    </script>
</head>
<body>
    
    <header>
        <div class="container" style="text-align: center;">
            <img src="./imagens/logo sem fundo1.png" alt="Header Image" class="header-image" style="width: 100px; height: auto;">
        </div>
        </header>
    <div class="container" id="main">
        <h1>Registre o pagamento</h1>
        <?php
        include("conexao2.php");

        $observacao = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_aluno = $_POST["id_aluno"];
            $mes = $_POST["mes"];
            $status = $_POST["status"];
            $observacao = $_POST["observacao"];

            // Define a data com base no status
            $data = $status === "pago" ? date("Y-m-d") : "9999-30-01";

            // Atualiza o banco de dados
            $sql = "UPDATE meses SET $mes = ?, obs = ? WHERE id_aluno = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssi", $data, $observacao, $id_aluno);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Pagamento atualizado com sucesso!</p>";
            } else {
                echo "<p style='color: red;'>Erro ao atualizar pagamento: " . $conexao->error . "</p>";
            }

            $stmt->close();
        }

        // Consulta para obter os alunos
        $sql = "SELECT id_aluno, pagador FROM meses";
        $result = $conexao->query($sql);
        ?>

        <form method="POST">

            <label for="students">Quem pagou:</label>
            <h3 style="color: red; font-size: 10px;">Atenção: os nomes estão em ordem alfabética.</h3>
            <select id="students" name="id_aluno" onchange="showMonths()" required>
                <option value="">--Selecione o responsável--</option>
                <?php
                $sql = "SELECT id_aluno, pagador FROM meses ORDER BY pagador ASC";
                $result = $conexao->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id_aluno"] . "'>" . $row["pagador"] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum aluno encontrado</option>";
                }
                ?>
            </select>

            <div id="monthsDropdown" style="display:none;">
                <label for="months">Qual o mês:</label>
                <select id="months" name="mes" onchange="showPaymentStatus(); fetchObservation();" required>

                    <option value="janeiro">Janeiro</option>
                    <option value="fevereiro">Fevereiro</option>
                    <option value="marco">Março</option>
                    <option value="abril">Abril</option>
                    <option value="maio">Maio</option>
                    <option value="junho">Junho</option>
                    <option value="julho">Julho</option>
                    <option value="agosto">Agosto</option>
                    <option value="setembro">Setembro</option>
                    <option value="outubro">Outubro</option>
                    <option value="novembro">Novembro</option>
                    <option value="dezembro">Dezembro</option>
                </select>
            </div>

            <div id="paymentStatus" style="display:none;">
                <label>Status do pagamento:</label>
                <h3 style="color: red; font-size: 10px;">Atenção: caso você marque como "não pago", essa pessoa entrará na lista de notificação.</h3>
                <div style="display: flex; align-items: center;">
                    <input type="radio" id="pago" name="status" value="pago" onclick="showObservationField(); toggleSubmitButton();" required style="margin-right: 5px;">
                    <label for="pago" style="margin-right: 20px;">Pago</label>
                    <input type="radio" id="naoPago" name="status" value="naoPago" onclick="showObservationField(); toggleSubmitButton();" required style="margin-right: 5px;">
                    <label for="naoPago">Não Pago</label>
                </div>
            </div>

            <div id="observationField" style="display:none;">
                <label for="observacao">Observação:</label>
                <textarea id="observacao" name="observacao" rows="4" cols="50"></textarea>
            </div>

            <button type="submit" id="submitButton" style="display:none;">Atualizar</button>
        </form>
    </div>
</body>
</html>
