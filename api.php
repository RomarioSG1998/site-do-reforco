<?php
//endereço da minha api: https://sededosaber.infinityfreeapp.com/api.php

// Configuração do banco de dados
$hostname = "sql209.infinityfree.com";
$bancodedados = "if0_36181052_sistemadoreforco";
$usuario = "if0_36181052";
$senha = "A7E5zgIppyr";

$conexao = new mysqli($hostname, $usuario, $senha, $bancodedados);
$conexao->set_charset("utf8");

if ($conexao->error) {
    die("Falha ao conectar ao banco de dados: " . $conexao->error);
}

// Definir cabeçalhos (CORS e JSON)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization");

// Identificar a tabela e o método HTTP
$tabela = $_GET['tabela'] ?? null; // Define a tabela (usuarios ou meses)
$metodo = $_SERVER['REQUEST_METHOD'];

// Processar requisições para cada tabela
switch ($tabela) {
    case "usuarios":
        processarTabelaUsuarios($metodo, $conexao);
        break;

    case "meses":
        processarTabelaMeses($metodo, $conexao);
        break;

    default:
        echo json_encode(["erro" => "Tabela não especificada ou inválida"]);
        break;
}

// Função para processar operações na tabela `usuarios`
function processarTabelaUsuarios($metodo, $conexao)
{
    switch ($metodo) {
        case "GET":
            // Listar todos os usuários ou buscar por ID
            $id = $_GET['id'] ?? null;
            if ($id) {
                $query = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
                $query->bind_param("i", $id);
                $query->execute();
                $resultado = $query->get_result();
                echo json_encode($resultado->fetch_assoc());
            } else {
                $query = $conexao->query("SELECT * FROM usuarios");
                echo json_encode($query->fetch_all(MYSQLI_ASSOC));
            }
            break;

        case "POST":
            // Criar um novo usuário
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("INSERT INTO usuarios (nome, senha, email, data_criacao, tipo, usu_img) VALUES (?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssss", $dados['nome'], $dados['senha'], $dados['email'], $dados['data_criacao'], $dados['tipo'], $dados['usu_img']);
            $query->execute();
            echo json_encode(["id" => $conexao->insert_id]);
            break;

        case "PUT":
            // Atualizar um usuário
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("UPDATE usuarios SET nome = ?, senha = ?, email = ?, tipo = ?, usu_img = ? WHERE id = ?");
            $query->bind_param("sssssi", $dados['nome'], $dados['senha'], $dados['email'], $dados['tipo'], $dados['usu_img'], $dados['id']);
            $query->execute();
            echo json_encode(["mensagem" => "Usuário atualizado"]);
            break;

        case "DELETE":
            // Excluir um usuário
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
            $query->bind_param("i", $dados['id']);
            $query->execute();
            echo json_encode(["mensagem" => "Usuário deletado"]);
            break;

        default:
            echo json_encode(["erro" => "Método não permitido"]);
            break;
    }
}

// Função para processar operações na tabela `meses`
function processarTabelaMeses($metodo, $conexao)
{
    switch ($metodo) {
        case "GET":
            // Listar todos os registros ou buscar por ID do aluno
            $id_aluno = $_GET['id_aluno'] ?? null;
            if ($id_aluno) {
                $query = $conexao->prepare("SELECT * FROM meses WHERE id_aluno = ?");
                $query->bind_param("i", $id_aluno);
                $query->execute();
                $resultado = $query->get_result();
                echo json_encode($resultado->fetch_assoc());
            } else {
                $query = $conexao->query("SELECT * FROM meses");
                echo json_encode($query->fetch_all(MYSQLI_ASSOC));
            }
            break;

        case "POST":
            // Criar um novo registro
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("INSERT INTO meses (id_aluno, pagador, janeiro, fevereiro, marco, abril, maio, junho, julho, agosto, setembro, outubro, novembro, dezembro, obs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param(
                "issssssssssssss",
                $dados['id_aluno'],
                $dados['pagador'],
                $dados['janeiro'],
                $dados['fevereiro'],
                $dados['marco'],
                $dados['abril'],
                $dados['maio'],
                $dados['junho'],
                $dados['julho'],
                $dados['agosto'],
                $dados['setembro'],
                $dados['outubro'],
                $dados['novembro'],
                $dados['dezembro'],
                $dados['obs']
            );
            $query->execute();
            echo json_encode(["id" => $conexao->insert_id]);
            break;

        case "PUT":
            // Atualizar um registro
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("UPDATE meses SET pagador = ?, janeiro = ?, fevereiro = ?, marco = ?, abril = ?, maio = ?, junho = ?, julho = ?, agosto = ?, setembro = ?, outubro = ?, novembro = ?, dezembro = ?, obs = ? WHERE id_aluno = ?");
            $query->bind_param(
                "sssssssssssssssi",
                $dados['pagador'],
                $dados['janeiro'],
                $dados['fevereiro'],
                $dados['marco'],
                $dados['abril'],
                $dados['maio'],
                $dados['junho'],
                $dados['julho'],
                $dados['agosto'],
                $dados['setembro'],
                $dados['outubro'],
                $dados['novembro'],
                $dados['dezembro'],
                $dados['obs'],
                $dados['id_aluno']
            );
            $query->execute();
            echo json_encode(["mensagem" => "Registro atualizado"]);
            break;

        case "DELETE":
            // Excluir um registro
            $dados = json_decode(file_get_contents("php://input"), true);
            $query = $conexao->prepare("DELETE FROM meses WHERE id_aluno = ?");
            $query->bind_param("i", $dados['id_aluno']);
            $query->execute();
            echo json_encode(["mensagem" => "Registro deletado"]);
            break;

        default:
            echo json_encode(["erro" => "Método não permitido"]);
            break;
    }
}
