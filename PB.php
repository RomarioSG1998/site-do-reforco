<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: white;
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-top: 0;
        }

        .courses-list {
            list-style: none;
            padding: 0;
        }

        .courses-list li {
            background: #e8e8f8;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }

        form {
            margin-top: 20px;
        }

        form input,
        form textarea,
        form button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        form button {
            background: #44277D;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background: #4caf50;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>

</header>

<div class="container">
    <div class="card">
        <h2>Sobre o Cursinho</h2>
        <p>
            O Cursinho XYZ é referência na preparação de estudantes para o ENEM e vestibulares em todo o Brasil. 
            Oferecemos aulas dinâmicas, materiais didáticos atualizados e uma equipe de professores experientes.
        </p>
    </div>

    <div class="card">
        <h2>Localização</h2>
        <p>Rua das Flores, 123 - São Paulo, SP</p>
    </div>

    <div class="card">
        <h2>Cursos Oferecidos</h2>
        <ul class="courses-list">
            <li>Preparatório para o ENEM</li>
            <li>Cursos intensivos para vestibulares</li>
            <li>Reforço escolar em Matemática</li>
            <li>Aulas de Redação</li>
            <li>Cursos Online</li>
        </ul>
    </div>

    <div class="card">
        <h2>Contatos</h2>
        <p>
            Telefone: (11) 9999-9999<br>
            E-mail: contato@cursinhoxyz.com.br<br>
            Redes sociais: @cursinhoxyz
        </p>
    </div>

    <div class="card">
        <h2>Deixe seu Feedback</h2>
        <form action="processa_feedback.php" method="POST">
            <input type="text" name="nome" placeholder="Seu Nome" required>
            <input type="email" name="email" placeholder="Seu E-mail" required>
            <textarea name="mensagem" rows="5" placeholder="Escreva seu feedback aqui..." required></textarea>
            <button type="submit">Enviar Feedback</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Cursinho XYZ - Todos os direitos reservados</p>
</footer>

</body>
</html>
