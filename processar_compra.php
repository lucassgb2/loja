<?php
session_start();
require 'config.php';

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
    header("Location: loja.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$forma_pagamento = $_POST['pagamento'] ?? 'PIX';
$total_geral = 0;

foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
    $stmt = $pdo->prepare("SELECT preco, estoque, nome FROM produtos WHERE id = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch();

    if (!$produto || $produto['estoque'] < $quantidade) {
        echo "<script>alert('Desculpe, o produto {$produto['nome']} não tem estoque suficiente!'); window.location.href='carrinho.php';</script>";
        exit;
    }
    $total_geral += $produto['preco'] * $quantidade;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, forma_pagamento) VALUES (?, ?, ?)");
    $stmt->execute([$usuario_id, $total_geral, $forma_pagamento]);
    $pedido_id = $pdo->lastInsertId();

    foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
        $stmt = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
        $stmt->execute([$id_produto]);
        $produto = $stmt->fetch();
        $preco_unitario = $produto['preco'];

        $stmt = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pedido_id, $id_produto, $quantidade, $preco_unitario]);

        $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
        $stmt->execute([$quantidade, $id_produto]);
    }

    $pdo->commit();
    unset($_SESSION['carrinho']);
    
    $sucesso = true;

} catch (Exception $e) {
    $pdo->rollBack();
    $sucesso = false;
    $erro_msg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo | ERROR 404</title>
    <link rel="stylesheet" href="loja.css">
    <style>
        body {
            background-color: #008080; /* Fundo verde clássico do Windows */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Janela de erro/sucesso estilo Windows 95 */
        .janela-recibo {
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right-color: #000;
            border-bottom-color: #000;
            box-shadow: inset 1px 1px #dfdfdf, inset -1px -1px #808080;
            padding: 4px;
            width: 450px;
        }

        .barra-titulo {
            background: #000080;
            color: #fff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .conteudo-recibo {
            padding: 20px;
            text-align: center;
            background: #fff;
            border: 2px inset #c0c0c0;
        }

        .icone {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .titulo-recibo {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 20px;
            font-family: 'Courier New', monospace;
        }

        .detalhes-recibo {
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            background: #000;
            color: #00ff00;
            padding: 15px;
            border: 2px inset #c0c0c0;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .btn-retornar {
            display: inline-block;
            background: #c0c0c0;
            color: #000;
            text-decoration: none;
            padding: 8px 20px;
            border: 2px outset #fff;
            font-weight: bold;
            font-family: 'Tahoma', sans-serif;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-retornar:active {
            border: 2px inset #fff;
        }
    </style>
</head>
<body>

    <div class="janela-recibo">
        <div class="barra-titulo">
            <span>Pedido_Confirmado.exe</span>
            <span>X</span>
        </div>
        
        <div class="conteudo-recibo">
            <?php if ($sucesso): ?>
                
                <div class="icone">✅</div>
                <div class="titulo-recibo">COMPRA APROVADA!</div>
                
                <div class="detalhes-recibo">
                    > PEDIDO Nº: #<?php echo $pedido_id; ?> <br>
                    > CLIENTE: <?php echo $_SESSION['usuario_nome']; ?> <br>
                    > PAGAMENTO: <?php echo strtoupper($forma_pagamento); ?> <br>
                    > TOTAL: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?> <br>
                    > STATUS: <span style="color: #ffff00;">Item sera enviado...</span>
                </div>

                <a href="loja.php" class="btn-retornar">Voltar para a Loja</a>

            <?php else: ?>
                
                <div class="icone">❌</div>
                <div class="titulo-recibo" style="color: red;">ERRO NO SISTEMA</div>
                
                <div class="detalhes-recibo" style="color: red;">
                    > Falha ao processar a compra. <br>
                    > Erro: <?php echo $erro_msg; ?>
                </div>

                <a href="carrinho.php" class="btn-retornar">Voltar ao Carrinho</a>

            <?php endif; ?>
        </div>
    </div>

</body>
</html>