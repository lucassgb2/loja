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
// Se o carrinho estiver vazio, volta
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: loja.php");
    exit;
}

$total_geral = 0;
// Calcula o total para mostrar na tela
foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
    $stmt = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch();
    if ($produto) {
        $total_geral += $produto['preco'] * $quantidade;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra | ERROR 404</title>
    <link rel="stylesheet" href="loja.css">
    <style>
        .box-pagamento {
            background: #fff;
            padding: 30px;
            border: 2px solid #000;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
        }
        .metodo-pagamento {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .metodo-pagamento label {
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
        }
        .total-checkout {
            font-size: 24px;
            color: #009900;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <header class="top-nav">
        <h1>Finalizar Compra</h1>
        <nav>
            <a href="carrinho.php">« Voltar ao Carrinho</a>
        </nav>
    </header>

    <div class="catalogo-container">
        <div class="catalogo-header">
            <h2>Pagamento</h2>
            <p>Escolha a forma de pagamento para concluir.</p>
        </div>

        <div class="box-pagamento">
            <div class="total-checkout">Total a Pagar: R$ <?php echo number_format($total_geral, 2, ',', '.'); ?></div>
            
                       <form method="POST" action="processar_compra.php" onsubmit="return confirmarPagamento(event)">
                <div class="metodo-pagamento">
                    <label><input type="radio" name="pagamento" value="Promessas" required onchange="avisarPromessa()"> Promessas</label>
                    <label><input type="radio" name="pagamento" value="Boleto"> Boleto</label>
                    <label><input type="radio" name="pagamento" value="Cartão"> Cartão</label>
                </div>
                
                <button type="submit" class="btn-comprar" style="background: #00ff00; border-color: #00aa00; font-size: 18px; padding: 15px;">
                    CONFIRMAR PAGAMENTO
                </button>
            </form>
        </div>
    </div>
    <script>
        // Função para quando a pessoa clicar em "Promessas"
        function avisarPromessa() {
            // Mostra o aviso assim que seleciona a opção
            setTimeout(() => {
                alert("AVISO DO SISTEMA:\n\nAo prosseguir com o pagamento via Promessas, você declara estar ciente de que, após a morte, poderá assumir uma obrigação de trabalho compulsório, destinada à quitação de dívidas espirituais ou materiais pendentes.");
            }, 100); // Pequeno delay só para o rádio marcar primeiro
        }

        // Função que roda quando clica em "CONFIRMAR PAGAMENTO"
        function confirmarPagamento(event) {
            const pagamentoSelecionado = document.querySelector('input[name="pagamento"]:checked');

            if (pagamentoSelecionado && pagamentoSelecionado.value === "Promessas") {
                // O famoso "Tem certeza?" do Windows
                const confirmou = confirm("Deseja aceitar os termos desta promessa?\n\n(Esta ação é irrevogável.)");
                
                if (!confirmou) {
                    event.preventDefault(); // Cancela o envio do formulário
                    return false;
                }
            }
            
            // Se for boleto, cartão ou se ele aceitou a promessa, o formulário envia
            return true; 
        }
    </script>
</body>
</html>