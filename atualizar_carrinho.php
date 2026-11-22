<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = $_POST['id_produto'];
    $acao = $_POST['acao']; // 'aumentar' ou 'diminuir'

    if (isset($_SESSION['carrinho'][$id_produto])) {
        $qtd_atual = $_SESSION['carrinho'][$id_produto];

        if ($acao === 'aumentar') {
            // Verifica o estoque antes de aumentar
            $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = ?");
            $stmt->execute([$id_produto]);
            $produto = $stmt->fetch();
            
            if ($qtd_atual < $produto['estoque']) {
                $_SESSION['carrinho'][$id_produto]++;
            }
        } elseif ($acao === 'dimitruir' || $acao === 'diminuir') { // Corrigido 'dimitruir' para evitar erro de digitação
            $_SESSION['carrinho'][$id_produto]--;
            
            // Se a quantidade chegar a 0, remove o item
            if ($_SESSION['carrinho'][$id_produto] <= 0) {
                unset($_SESSION['carrinho'][$id_produto]);
            }
        }
    }
}

header("Location: carrinho.php");
exit;
?>