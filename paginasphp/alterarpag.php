<?php
// Verificar se há um RA passado pela página anterior
if(isset($_GET['ra'])) {
    $ra = $_GET['ra'];
} else {
    $ra = "";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados da Tabela</title>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('buscarPagador').addEventListener('click', function() {
            var ra = document.getElementById('ra').value;
            if (ra) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'buscar_pagador.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
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
                xhr.send('ra=' + ra);
            }
        });
    });
    </script>
</head>
<body>
    <h2>Alterar Dados da Tabela</h2>
    <?php
    // Exibir mensagens de erro se houver
    if(isset($_GET['error'])) {
        echo "<p style='color: red;'>Erro: ".$_GET['error']."</p>";
    }
    ?>
    <form action="alterar_dados.php" method="POST">
    <label for="ra">RA:</label>
<input type="text" id="ra" name="ra" value="<?php echo $ra; ?>" required><br><br>

        
        <label for="janeiro">Janeiro:</label>
        <input type="date" id="janeiro" name="janeiro"><br><br>
        
        <label for="fevereiro">Fevereiro:</label>
        <input type="date" id="fevereiro" name="fevereiro"><br><br>
        
        <label for="marco">Março:</label>
        <input type="date" id="marco" name="marco"><br><br>
        
        <label for="abril">Abril:</label>
        <input type="date" id="abril" name="abril"><br><br>
        
        <label for="maio">Maio:</label>
        <input type="date" id="maio" name="maio"><br><br>
        
        <label for="junho">Junho:</label>
        <input type="date" id="junho" name="junho"><br><br>
        
        <label for="julho">Julho:</label>
        <input type="date" id="julho" name="julho"><br><br>
        
        <label for="agosto">Agosto:</label>
        <input type="date" id="agosto" name="agosto"><br><br>
        
        <label for="setembro">Setembro:</label>
        <input type="date" id="setembro" name="setembro"><br><br>
        
        <label for="outubro">Outubro:</label>
        <input type="date" id="outubro" name="outubro"><br><br>
        
        <label for="novembro">Novembro:</label>
        <input type="date" id="novembro" name="novembro"><br><br>
        
        <label for="dezembro">Dezembro:</label>
        <input type="date" id="dezembro" name="dezembro"><br><br>
        
        <input type="submit" value="Atualizar">
        <button type="button" id="buscarPagador">Buscar Pagador</button>
    </form>
</body>
</html>

