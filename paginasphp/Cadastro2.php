
<?php

include('protect.php');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Cadastro2.css">
    <title>Formulário</title>
</head>

<body>
    <div class="container">
        <div class="form-image">
            <img src="../imagens/logo sem fundo2.png" alt="">
        </div>
        <div class="form">
            <form action="./conexao.php" method="POST">
                <div class="form-header">
                    <div class="title">
                        <p>Preencha com os dados do(a) aluno(a).</p>
                    </div>
                    <div class="login-button">
                        <button type="submit">Salvar</button>
                    </div>
                    
                    <a href="./painel.php" class="menu-link">Menu</a>
                   
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="firstname">Nome</label>
                        <input id="firstname" type="text" name="firstname" placeholder="Digite..." required>
                    </div>

                    <div class="input-box">
                        <label for="dataNascimento">Data de Nascimento</label>
                        <input id="dataNascimento" type="date" name="dataNascimento" required>
                    </div>

                    <div class="input-box">
                        <label for="number">Celular</label>
                        <input id="number" type="tel" name="number" placeholder="(xx) xxxx-xxxx" required>
                    </div>

                    <div class="input-box">
                        <label for="responsavelAluno">Responsável pelo aluno</label>
                        <input id="responsavelAluno" type="text" name="responsavelAluno" placeholder="Digite o nome do pagador" required>
                    </div>

                    <div class="input-box">
                        <label for="genero">Gênero</label>
                        <select id="genero" name="genero" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <label for="turma">Turma</label>
                        <select id="turma" name="turma" required>
                            <option value="" disabled selected>Selecione a turma</option>
                            <option value="Turma 1">1</option>
                            <option value="Turma 2">2</option>
                            <option value="Turma 3">3</option>
                            <option value="Turma 4">4</option>
                            <option value="Turma 5">5</option>
                            <option value="Turma 6">6</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Verifica se há uma mensagem na URL
    if (isset($_GET['msg'])) {
        // Exibe a mensagem correspondente
        if ($_GET['msg'] === 'success') {
            echo "<div id='message' style='color: green;'>Registro inserido com sucesso!</div>";
        } elseif ($_GET['msg'] === 'error') {
            echo "<div id='message' style='color: red;'>Erro ao inserir registro.</div>";
        }

        // JavaScript para esconder a mensagem e redirecionar após alguns segundos
        echo "<script>
                setTimeout(function() {
                    var message = document.getElementById('message');
                    if (message) {
                        message.style.display = 'none';
                        window.location.href = './cadastro2.php'; // Substitua pela URL desejada
                    }
                }, 3000); // 3000 milissegundos = 3 segundos
              </script>";
    }
    ?>
</body>

</html>
