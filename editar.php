<?php
include('conexao2.php');
include('protect.php');


// Verifique se o RA do aluno foi fornecido na URL
if(isset($_GET['ra'])) {
    $ra = $_GET['ra'];

    // Consulte o aluno com o RA fornecido
    $sql = "SELECT * FROM alunos WHERE ra = '$ra'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        $aluno = $result->fetch_assoc();

        // Verifique se os dados do aluno foram enviados via POST
        if(isset($_POST['ra']) && isset($_POST['nome']) && isset($_POST['datanasc']) && isset($_POST['celular']) && isset($_POST['responsavel']) && isset($_POST['genero']) && isset($_POST['turma']) && isset($_POST['situacao'])) {
            $nome = $_POST['nome'];
            $datanasc = $_POST['datanasc'];
            $celular = $_POST['celular'];
            $responsavel = $_POST['responsavel'];
            $genero = $_POST['genero'];
            $turma = $_POST['turma'];
            $situacao = $_POST['situacao']; // Adicionado

            // Atualize os dados do aluno no banco de dados
            $sql = "UPDATE alunos SET nome='$nome', datanasc='$datanasc', celular='$celular', responsavel='$responsavel', genero='$genero', turma='$turma', situacao='$situacao' WHERE ra='$ra'";
            if ($conexao->query($sql) === TRUE) {
                // Exibir mensagem de sucesso
                echo "<p>Edição concluída com sucesso!</p>";
                // Redirecionar para a página principal após 2 segundos
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'modcadastro.php';
                        }, 2000); // 2 segundos
                      </script>";
                exit();
            } else {
                echo "Erro ao editar aluno: " . $conexao->error;
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Aluno</title>
            <style>
                /* Adicione o CSS aqui */
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500&family=Open+Sans:wght@300;400;500;600&display=swap');
        * {
            padding: 8;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            width: 100%;
            height: 100vh;
            background-image: url("./imagens/111.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; 
        }

        form {
            width: 40%;
            max-width: 330px;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 90px; 
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: none;
            color: #44277D;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        select {
            width: calc(100% - 20px); /* Ajuste o tamanho conforme necessário */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #44277D;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Adicione regras de mídia para telas menores */
        @media screen and (max-width: 750px) {
            * {
            padding: 10;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            }
            form {
                width: 90%;
            }
        }
            </style>
        </head>

        <body>
            <form action="" method="post">
                <input type="hidden" name="ra" value="<?php echo $aluno['ra']; ?>">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $aluno['nome']; ?>"><br>
                <label for="datanasc">Data de Nascimento:</label>
                <input type="date" name="datanasc" id="datanasc" value="<?php echo $aluno['datanasc']; ?>"><br>
                <label for="celular">Celular:</label>
                <input type="text" name="celular" id="celular" value="<?php echo $aluno['celular']; ?>"><br>
                <label for="responsavel">Responsável:</label>
                <input type="text" name="responsavel" id="responsavel" value="<?php echo $aluno['responsavel']; ?>"><br>
                <label for="genero">Gênero:</label>
                <select name="genero" id="genero">
                    <option value="Masculino" <?php if($aluno['genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="Feminino" <?php if($aluno['genero'] == 'Feminino') echo 'selected'; ?>>Feminino</option>
                </select><br>
                <label for="turma">Turma:</label>
                <select name="turma" id="turma">
                    <option value="Turma 1" <?php if($aluno['turma'] == 'Turma 1') echo 'selected'; ?>>Turma 1</option>
                    <option value="Turma 2" <?php if($aluno['turma'] == 'Turma 2') echo 'selected'; ?>>Turma 2</option>
                    <option value="Turma 3" <?php if($aluno['turma'] == 'Turma 3') echo 'selected'; ?>>Turma 3</option>
                    <option value="Turma 4" <?php if($aluno['turma'] == 'Turma 4') echo 'selected'; ?>>Turma 4</option>
                    <option value="Turma 5" <?php if($aluno['turma'] == 'Turma 5') echo 'selected'; ?>>Turma 5</option>
                    <option value="Turma 6" <?php if($aluno['turma'] == 'Turma 6') echo 'selected'; ?>>Turma 6</option>
                    <option value="PB" <?php if($aluno['turma'] == 'PB') echo 'selected'; ?>>PB</option>
                </select><br>
                <label for="situacao">Situação:</label>
                <select name="situacao" id="situacao">
                    <option value="Ativo" <?php if($aluno['situacao'] == 'Ativo') echo 'selected'; ?>>Ativo</option>
                    <option value="Inativo" <?php if($aluno['situacao'] == 'Inativo') echo 'selected'; ?>>Inativo</option>
                </select><br>
                <input type="submit" value="Salvar">
            </form>
        </body>

        </html>
        <?php
    } else {
        echo "Aluno não encontrado.";
    }
} else {
    echo "RA do aluno não fornecido.";
}

// Feche a conexão com o banco de dados
$conexao->close();
?>
