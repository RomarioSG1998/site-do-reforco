<?php 
include('conexao2.php');
//include('admin.php');//
include('config.php');
include('protect.php');

// Consulta para obter o caminho da imagem do usuário atual
$id_usuario = $_SESSION['id']; // Supondo que você tenha uma variável de sessão para o ID do usuário
$sql = "SELECT usu_img FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($usu_img);
    $stmt->fetch();
    // Atribua o caminho da imagem à variável $usu_img
} else {
    // Usuário não encontrado
    $usu_img = ""; // Defina um valor padrão ou deixe em branco
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administração</title>
  <style>
    body {
      font-family: 'Times New Roman', Times, serif; /* Definindo a fonte para Times New Roman */
      margin: 0;
      padding: 0;
      background-color: #D9D9D9;
    }
    .admin-header {
      background-color:  #44277D; /* Lilás Escuro */
      color: #fff;
      padding: 25px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative; /* Adicionando posição relativa para .admin-header */
    }
    .admin-header h1 {
      margin-left: -50px;
      margin-top: 20px; /* Ajustando a margem superior */
      margin-bottom: 0px;
      font-size: 40px; /* Aumentando o tamanho da fonte */
      text-transform: uppercase; /* Transformando o texto em maiúsculas */
    }
    .admin-header .home-link {
      position: absolute;
      top: 10px;
      right: 10px; /* Alterado para o lado direito */
      text-decoration: none;
      color: #fff;
    }
    .admin-header .logo {
      width: 120px;
      height: auto;
      margin-top: -5px;
      margin-right: -10px;
    }
    .admin-profile {
      text-align: center;
      margin-top: 20px;
    }
    .admin-profile img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      margin-bottom: 10px;
      border: 4px solid #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .admin-profile h2 {
      margin: 0;
      font-size: 36px;
    }
    .admin-actions {
      text-align: center;
      margin-top: 30px;
    }
    .admin-actions a {
      display: block;
      text-decoration: none;
      color: #58216d; /* Lilás Escuro */
      background-color: #f9f4fc;
      margin: 10px auto;
      font-size: 16px;
      padding: 8px 16px;
      border: 4px solid #58216d; /* Lilás Escuro */
      border-radius: 20px;
      max-width: 200px; /* Definindo largura máxima para os botões */
    }
    .admin-actions a:hover {
      background-color: #9381a9; /* Lilás Mais Escuro */
      color: #fff;
    }
    .popup {
  font-family: 'Tahoma', Times, serif; 
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #44277D;
  padding: 20px;
  border: 2px solid #000000;
  z-index: 9999;
  border-radius: 25px;
  max-width: 500px; /* Definindo a largura máxima do container */
}
.popup p {
  max-width: 100%; /* Garantindo que o texto siga o padrão de largura do container */
}

.popup-content {
  font-family: 'Tahoma', Times, serif; 
  font-size: 15px;
  text-align: center;
  color: white;
}

#interrogation-button {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 24px;
        padding: 10px 20px;
        background-color: #44277D;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 25px;
        animation: blink-animation 2s forwards; /* Mudança: duração da animação e propriedade forwards */
    }

    @keyframes blink-animation {
        0%, 50%, 100% {
            opacity: 1;
        }
        25%, 75% {
            opacity: 0;
        }
    }
    .popup-content button {
  display: block;
  align:center;
  width: 100%;
  margin-bottom: 10px;
  padding: 10px;
  font-size: 16px;
  background-color: white; /* Cor de fundo */
  color: black; /* Cor do texto */
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.popup-content button:hover {
  background-color: #836fff; /* Altere a cor de fundo quando hover */
}



    /* Media queries para responsividade */
    @media screen and (max-width: 750px) {
      .admin-header {
        padding: 50px;
      }
      .admin-header h1 {
        font-size: 20px;
        padding-top: 20px;
      }
      .admin-header .logo {
        width: 80px;
      }
      .admin-profile img {
        width: 120px;
        height: 120px;
      }
      .admin-profile h2 {
        font-size: 30px;
      }
      .admin-actions a {
        font-size: 14px;
        background-color: #f9f4fc;
      }
      .popup {
    padding: 10px; /* Reduzindo o padding */
    font-size: 14px; /* Reduzindo o tamanho da fonte */
  }

    }
  </style>
</head>
<body>
  <div class="admin-header">
    <a href="./painel.php?nome=<?php echo urlencode($_SESSION['nome']); ?>" class="home-link">
      <img src="./imagens/logo sem fundo1.png" alt="Home" class="logo">
    </a>
    <h1><?php echo strtoupper("Central Administrativa de " . htmlspecialchars($_SESSION['nome'])); ?></h1>
  </div>

  <div class="admin-profile">
    <img src="<?php echo htmlspecialchars($usu_img); ?>" alt="Foto do Admin">
    <h2><?php echo htmlspecialchars($_SESSION['nome']); ?></h2>
  </div>

  <div class="admin-actions">
    <a href="cadusuario.php">Cadastrar Novo Usuário</a>
    <a href="mensalidade.php">Tabela de Pagamentos</a>
    <a href="Monitorar.php"> Monitorar Atividades</a>
  </div>
  <button id="interrogation-button">?</button>
<div id="popup" class="popup">
  <div class="popup-content">
  <p>
Ao clicar nos links abaixo, você será redirecionado para a página de avaliação ou atualização cadastral.<br> Esta página é destinada aos pais para avaliar a satisfação ou atualizar os dados do aluno.<br> Acesse o link, copie-o e envie aos pais.</p>
    <button id="popup-link">Avaliação</button>
    <button id="popup-link2">Atualização</button>
  </div>
</div>


<script> document.getElementById("interrogation-button").addEventListener("click", function() {
  document.getElementById("popup").style.display = "block";
});

document.getElementById("popup-link").addEventListener("click", function() {
  window.location.href = "./avaliacao.php";
});

document.getElementById("popup-link2").addEventListener("click", function() {
  window.location.href = "./cadpais.php";
});

document.addEventListener("click", function(e) {
  if (e.target.id === "popup") {
    document.getElementById("popup").style.display = "none";
  }
});

</script>
</body>
</html>
