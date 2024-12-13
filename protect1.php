<?php
// Verifica se a sessão já foi iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Você não pode acessar esta página, pois  não está logado.<p><a href=\"logprof.php\">Entrar</a></p>");
}
?>
