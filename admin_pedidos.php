<?php
session_start();
require 'config.php';

// CATRACA DO ADMIN
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: loja.php");
    exit;
}

// Busca todos os pedidos, juntando com o nome do usuário
$sql = "SELECT p.id, p.total, p.forma_pagamento, p.data_pedido, u.nome as cliente_nome, u.email 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.data_pedido DESC";
$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pedidos | ERROR 404</title>
    <style>
        body {
            background-color: #008080;
            font-family: 'Tahoma', 'MS Sans Serif', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .admin-janela {
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right-color: #000;
            border-bottom-color: #000;
            box-shadow: inset 1px 1px #dfdfdf, inset -1px -1px #808080;
            padding: 4px;
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
        }
        .barra-titulo {
            background: #000080;
            color: #fff;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 2px solid #808080;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .admin-nav a {
            color: #000;
            text-decoration: none;
            background: #c0c0c0;
            border: 1px outset #fff;
            padding: 3px 8px;
            font-weight: bold;
        }
        .admin-nav a:active { border: 1px inset #fff; }
        
        .conteudo { padding: 10px; }

        /* ESTILO DOS PEDIDOS */
        .pedido-box {
            background: #dfdfdf;
            border: 2px inset #fff;
            margin-bottom: 20px;
            padding: 10px;
        }
        .pedido-header {
            background: #000080;
            color: #fff;
            padding: 5px 10px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 13px;
            margin: -10px -10px 10px -10px;
        }
        .pedido-info {
            font-size: 12px;
            margin-bottom: 10px;
            color: #333;
            line-height: 1.5;
        }
        .pedido-info strong {
            color: #000;
        }

        /* TABELA DE ITENS */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #c0c0c0;
            color: #000;
        }
        .total-final {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            color: #008000;
        }
    </style>
</head>
<body>

    <div class="admin-janela">
        <div class="barra-titulo">
            <span>admin_pedidos.exe</span>
            <span onclick="window.location.href='admin.php'" style="cursor:pointer;">X</span>
        </div>

        <div class="admin-nav">
            <div>
                <a href="admin.php">« Voltar ao Menu</a>
            </div>
            <div>
                <strong>Histórico de Vendas</strong>
            </div>
        </div>

        <div class="conteudo">
            
            <?php if (count($pedidos) == 0): ?>
                <p style="text-align: center; font-weight: bold; padding: 20px;">Nenhum pedido realizado até o momento.</p>
            <?php endif; ?>

            <?php foreach($pedidos as $pedido): ?>
                <div class="pedido-box">
                    <div class="pedido-header">
                        <span>Pedido #<?php echo $pedido['id']; ?></span>
                        <span>Data: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span>
                    </div>
                    
                    <div class="pedido-info">
                        <strong>Cliente:</strong> <?php echo $pedido['cliente_nome']; ?> (<?php echo $pedido['email']; ?>) <br>
                        <strong>Pagamento:</strong> <?php echo strtoupper($pedido['forma_pagamento']); ?>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Qtd</th>
                                <th>Preço Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Busca os itens deste pedido específico
                            $stmt_itens = $pdo->prepare("
                                SELECT i.quantidade, i.preco_unitario, p.nome 
                                FROM itens_pedido i 
                                JOIN produtos p ON i.produto_id = p.id 
                                WHERE i.pedido_id = ?
                            ");
                            $stmt_itens->execute([$pedido['id']]);
                            $itens = $stmt_itens->fetchAll();

                            foreach($itens as $item): 
                                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                            ?>
                            <tr>
                                <td><?php echo $item['nome']; ?></td>
                                <td><?php echo $item['quantidade']; ?></td>
                                <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="total-final">
                        Total do Pedido: R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

</body>
</html>
