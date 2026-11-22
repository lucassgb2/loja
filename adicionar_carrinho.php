<?<?php
session_start();
require 'config.php';

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];
    
    // Verifica se o produto existe e tem estoque
    $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch();

    if ($produto && $produto['estoque'] > 0) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Pega a quantidade atual no carrinho (se não tiver, é 0)
        $qtd_atual = isset($_SESSION['carrinho'][$id_produto]) ? $_SESSION['carrinho'][$id_produto] : 0;

        // Se ainda tem estoque disponível, adiciona
        if ($qtd_atual < $produto['estoque']) {
            $_SESSION['carrinho'][$id_produto] = $qtd_atual + 1;
        }
    }
}

header("Location: carrinho.php"); // Já manda direto pro carrinho
exit;
?>