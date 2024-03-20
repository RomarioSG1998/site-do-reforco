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
    <title>Alterar Dados da Tabela</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            width: 100%;
            max-width: 500px; /* Máximo de 500px */
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"],
        input[type="datetime-local"],
        input[type="submit"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Alterar Dados da Tabela</h2>
    <?php if(isset($_GET['error'])): ?>
        <p class="error-message">Erro: <?php echo $_GET['error']; ?></p>
    <?php endif; ?>
    <form action="alterar_dados.php" method="POST">
        <label for="ra">RA:</label>
        <input type="text" id="ra" name="ra" value="<?php echo isset($_GET['ra']) ? $_GET['ra'] : ''; ?>" required>

        <?php
            $meses = array(
                'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho',
                'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
            );
            
            foreach($meses as $mes) {
                echo '<label for="' . $mes . '">' . ucfirst($mes) . ':</label>';
                echo '<input type="datetime-local" id="' . $mes . '" name="' . $mes . '" value="' . (isset($_GET[$mes]) ? date('Y-m-d\TH:i', strtotime($_GET[$mes])) : '') . '">';
            }
        ?>
        
        <label for="obs">Observação:</label>
        <input type="text" id="obs" name="obs" value="<?php echo isset($_GET['obs']) ? $_GET['obs'] : ''; ?>">

        <input type="submit" value="Atualizar">
        <button type="button" id="buscarPagador">Buscar Pagador</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('buscarPagador').addEventListener('click', function() {
                var ra = document.getElementById('ra').value;
                if (ra) {
                    var formData = new FormData();
                    formData.append('ra', ra);
                    
                    <?php
                    foreach($meses as $mes) {
                        echo "formData.append('$mes', new Date(document.getElementById('$mes').value).toISOString().substring(0, 16));";
                    }
                    ?>
                    
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'buscar_pagador.php', true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response) {
                                alert(JSON.stringify(response)); // Exemplo de manipulação dos dados do pagador
                            } else {
                                alert("Nenhum pagador encontrado para o RA informado.");
                            }
                        }
                    };
                    xhr.send(formData);
                }
            });
        });
    </script>
</body>
</html>
