<?php
// Configurações do banco de dados
$hostname = "localhost";
$bancodedados = "id21964020_sistemadoreforco";
$usuario = "root";
$senha = "";

// Conexão com o banco de dados
$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($conexao->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->connect_error);
}

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Insere os dados na tabela
    $sql = "INSERT INTO avaliacao (ns, comente, data) VALUES ('$rating', '$comment', NOW())";

    if ($conexao->query($sql) === TRUE) {
        echo "Avaliação registrada com sucesso!";
    } else {
        echo "Erro ao registrar a avaliação: " . $conexao->error;
    }
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="./imagens/3333.ico" type="image/x-icon" >
<title>Avaliação de Produto</title>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .container {
        max-width: 500px;
        padding: 30px;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    .rating {
        unicode-bidi: bidi-override;
        direction: rtl;
        text-align: center;
        margin-bottom: 20px;
    }
    .rating > label {
        display: inline-block;
        padding: 0 10px;
        font-size: 30px;
        color: #aaa;
        cursor: pointer;
    }
    .rating > input[type="radio"] {
        display: none;
    }
    .rating > label:hover, .rating > label:hover ~ label, .rating > input[type="radio"]:checked ~ label {
        color: purple;
    }
    #comment {
        width: 100%;
        height: 100px;
        resize: none;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 15px;
        padding: 10px;
        font-size: 16px;
        color: #555;
    }
    #submitBtn {
        width: 100%;
        padding: 12px;
        background-color: purple;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s ease;
    }
    #submitBtn:hover {
        background-color: #45a049;
    }
    .success-message {
        display: none;
        text-align: center;
        color: green;
    }
    .thank-you {
        display: none;
        text-align: center;
    }
    .thank-you img {
        max-width: 200%;
        height: 200%;
    }
    .star-rating {
        font-size: 0; /* Remove o espaço entre as estrelas */
    }
    .star-rating label {
        font-size: 20px;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label:before {
        content: '☆';
        margin: 5px;
    }
    .star-rating input[type="radio"]:checked ~ label:before,
    .star-rating label:hover ~ input[type="radio"] ~ label:before {
        content: '★';
        color: purple;
    }
</style>
</head>
<body>

<div class="container">
    <h1>Deixe sua contribuição</h1>

    <p class="animated" id="instruction">Qual seu nível de satisfação com o Reforço?</p>

    <div class="rating star-rating">
        <input type="radio" id="star5" name="rating" value="5"><label for="star5">5</label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4">4</label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3">3</label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2">2</label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1">1</label>
    </div>

    <p class="animated" id="commentLabel">Envie um comentário/sugestão: (opcional)</p>
    <textarea id="comment" class="form-control animated" placeholder="Digite seu comentário aqui" maxlength="100"></textarea>

    <button id="submitBtn" class="btn btn-success mt-3">Enviar</button>
    <div class="success-message">Avaliação registrada com sucesso!</div>
</div>

<div class="thank-you">
    <img src="https://media.giphy.com/media/3oKIPtFaNBrfMQONSU/giphy.gif" alt="Agradecimento">
</div>

<!-- Bootstrap JS, jQuery, jQuery UI -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        $("#submitBtn").click(function() {
            var rating = $("input[name='rating']:checked").val();
            var comment = $("#comment").val();

            if (!rating) {
                $("#instruction").effect("shake");
                return;
            }

            if (comment.length > 100) {
                alert("O comentário não pode exceder 100 caracteres.");
                return;
            }

            // Prepare os dados para envio
            var formData = {
                rating: rating,
                comment: comment
            };

            // Envie os dados para o PHP
            $.ajax({
                type: "POST",
                url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                data: formData,
                success: function(response) {
                    $(".success-message").show().delay(3000).hide(); // Exibe a mensagem de sucesso e a faz desaparecer após 3 segundos
                    $("#submitBtn").prop("disabled", true); // Desativa o botão de envio após o sucesso
                    $(".container").fadeOut(1000, function() {
                        $(".thank-you").fadeIn(1000); // Exibe a animação de agradecimento após o formulário desaparecer
                    });
                }
            });
        });
    });
</script>

</body>
</html>
