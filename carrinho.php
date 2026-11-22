<?php
session_start();
require 'config.php';
require 'verifica_banimento.php'; // <--- A LINHA MÁGICA AQUI

// A catraca normal de não logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
// ... resto do código
// Se o carrinho estiver vazio
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<script>alert('Seu carrinho está vazio!'); window.location.href='loja.php';</script>";
    exit;
}

$total_geral = 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho | ERROR 404</title>
    <link rel="stylesheet" href="loja.css">
    <style>
        .tabela-carrinho { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        .tabela-carrinho th, .tabela-carrinho td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        .tabela-carrinho th { background: #000080; color: white; font-family: 'Impact', sans-serif; }
        .total-box { text-align: right; font-size: 20px; font-weight: bold; color: #009900; margin-top: 20px; padding: 10px; background: #f4f4f4; border: 1px solid #999; }
        .btn-remover { background: #ff0000; color: white; text-decoration: none; padding: 5px 10px; font-size: 12px; border: 1px solid #000; font-weight: bold; }
        .btn-qtd { background: #c0c0c0; border: 1px outset #fff; padding: 2px 8px; cursor: pointer; font-weight: bold; }
        .acoes-carrinho { display: flex; justify-content: space-between; margin-top: 20px; }
    </style>
</head>
<body>

    <header class="top-nav">
        <h1>Seu Carrinho</h1>
        <nav>
            <a href="loja.php">Continuar Comprando</a>
            <a href="logout.php" style="color: #ff5555;">[ Sair ]</a>
        </nav>
    </header>

    <div class="catalogo-container">
        
        <div class="catalogo-header">
            <h2>Resumo do Pedido</h2>
        </div>

        <table class="tabela-carrinho">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Qtd</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($_SESSION['carrinho'] as $id_produto => $quantidade): 
                    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
                    $stmt->execute([$id_produto]);
                    $produto = $stmt->fetch();

                    if ($produto):
                        $subtotal = $produto['preco'] * $quantidade;
                        $total_geral += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $produto['nome']; ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td>
                            <!-- BOTÕES DE + E - -->
                            <form method="POST" action="atualizar_carrinho.php" style="display: inline;">
                                <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                                <input type="hidden" name="acao" value="diminuir">
                                <button type="submit" class="btn-qtd">-</button>
                            </form>
                            
                            <span style="margin: 0 10px; font-weight: bold;"><?php echo $quantidade; ?></span>
                            
                            <form method="POST" action="atualizar_carrinho.php" style="display: inline;">
                                <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                                <input type="hidden" name="acao" value="aumentar">
                                <button type="submit" class="btn-qtd">+</button>
                            </form>
                        </td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        <td>
                            <!-- BOTÃO DE REMOVER (X) -->
                            <a href="remover_carrinho.php?id=<?php echo $id_produto; ?>" class="btn-remover">X</a>
                        </td>
                    </tr>
                <?php 
                    endif; 
                endforeach; 
                ?>
            </tbody>
        </table>

        <div class="total-box">
            Total Geral: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
        </div>

        <div class="acoes-carrinho">
            <a href="loja.php" class="btn-comprar" style="text-decoration: none; width: auto;">« Voltar</a>
            <a href="checkout.php" class="btn-comprar" style="background: #00ff00; border-color: #00aa00; text-decoration: none; width: auto;">Finalizar Compra »</a>
        </div>

    </div>

</body>
</html>