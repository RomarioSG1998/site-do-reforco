<?php
// Verificar se há um RA passado pela página anterior
if(isset($_GET['ra'])) {
    $ra = $_GET['ra'];
} else {
    $ra = "";
}

// Verificar se há datas passadas pela página anterior
$janeiro = isset($_GET['janeiro']) ? $_GET['janeiro'] : "";
$fevereiro = isset($_GET['fevereiro']) ? $_GET['fevereiro'] : "";
$marco = isset($_GET['marco']) ? $_GET['marco'] : "";
$abril = isset($_GET['abril']) ? $_GET['abril'] : "";
$maio = isset($_GET['maio']) ? $_GET['maio'] : "";
$junho = isset($_GET['junho']) ? $_GET['junho'] : "";
$julho = isset($_GET['julho']) ? $_GET['julho'] : "";
$agosto = isset($_GET['agosto']) ? $_GET['agosto'] : "";
$setembro = isset($_GET['setembro']) ? $_GET['setembro'] : "";
$outubro = isset($_GET['outubro']) ? $_GET['outubro'] : "";
$novembro = isset($_GET['novembro']) ? $_GET['novembro'] : "";
$dezembro = isset($_GET['dezembro']) ? $_GET['dezembro'] : "";
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
        <input type="datetime-local" id="janeiro" name="janeiro" value="<?php echo $janeiro; ?>"><br><br>
        
        <label for="fevereiro">Fevereiro:</label>
        <input type="datetime-local" id="fevereiro" name="fevereiro" value="<?php echo $fevereiro; ?>"><br><br>
        
        <label for="marco">Março:</label>
        <input type="datetime-local" id="marco" name="marco" value="<?php echo $marco; ?>"><br><br>
        
        <label for="abril">Abril:</label>
        <input type="datetime-local" id="abril" name="abril" value="<?php echo $abril; ?>"><br><br>
        
        <label for="maio">Maio:</label>
        <input type="datetime-local" id="maio" name="maio" value="<?php echo $maio; ?>"><br><br>
        
        <label for="junho">Junho:</label>
        <input type="datetime-local" id="junho" name="junho" value="<?php echo $junho; ?>"><br><br>
        
        <label for="julho">Julho:</label>
        <input type="datetime-local" id="julho" name="julho" value="<?php echo $julho; ?>"><br><br>
        
        <label for="agosto">Agosto:</label>
        <input type="datetime-local" id="agosto" name="agosto" value="<?php echo $agosto; ?>"><br><br>
        
        <label for="setembro">Setembro:</label>
        <input type="datetime-local" id="setembro" name="setembro" value="<?php echo $setembro; ?>"><br><br>
        
        <label for="outubro">Outubro:</label>
        <input type="datetime-local" id="outubro" name="outubro" value="<?php echo $outubro; ?>"><br><br>
        
        <label for="novembro">Novembro:</label>
        <input type="datetime-local" id="novembro" name="novembro" value="<?php echo $novembro; ?>"><br><br>
        
        <label for="dezembro">Dezembro:</label>
        <input type="datetime-local" id="dezembro" name="dezembro" value="<?php echo $dezembro; ?>"><br><br>
        
        <input type="submit" value="Atualizar">
        <button type="button" id="buscarPagador">Buscar Pagador</button>
    </form>
</body>
</html>
