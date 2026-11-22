
<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Alterna o estado dos anúncios
$anunciosDesligados = $_SESSION['ads_off'] ?? false;

$_SESSION['ads_off'] = !$anunciosDesligados;

// Volta para a loja
header("Location: loja.php");
exit;
