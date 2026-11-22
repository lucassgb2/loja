<?php
session_start();
require 'config.php';
require 'verifica_banimento.php'; 

// A catraca normal de não logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Lógica da Busca (Agora busca por Nome do Produto OU Nome da Categoria)
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca !== '') {
    // O JOIN junta a tabela de produtos com a de categorias para podermos buscar pelo nome da categoria
    $sql = "SELECT p.* FROM produtos p 
            JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.nome LIKE ? OR c.nome LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    // Se não tem busca, mostra todos
    $stmt = $pdo->query("SELECT * FROM produtos");
}
$produtos = $stmt->fetchAll();

$qtd_carrinho = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo | ERROR 404</title>
    <link rel="stylesheet" href="loja.css">
    
    <style>
        .estoque-tag {
            display: inline-block;
            background: #e0e0e0;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #aaa;
            border-radius: 3px;
            margin-bottom: 10px;
            color: #333;
        }
        .esgotado {
            background: #ffcccc !important;
            color: #cc0000 !important;
            border-color: #cc0000 !important;
        }
        
        /* ESTILO DA BARRA DE BUSCA */
        .barra-busca-container {
            background: #000080;
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #fff;
        }
        .barra-busca-container input {
            width: 60%;
            padding: 8px;
            border: 2px inset #c0c0c0;
            font-family: 'Tahoma', sans-serif;
        }
        .barra-busca-container button {
            padding: 8px 20px;
            background: #c0c0c0;
            border: 2px outset #fff;
            font-weight: bold;
            cursor: pointer;
        }
        .barra-busca-container button:active {
            border: 2px inset #fff;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="top-nav">
        <h1>ERROR 404 Store</h1>
        <nav>
            <a href="apresentacao.php">Portal</a>
            <a href="jogo.html">???</a> 
            
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="admin.php" style="color: #ffff00; font-weight: bold; text-decoration: underline;">[ PAINEL ADMIN ]</a>
            <?php endif; ?>

            <span style="color: white; margin-left: 20px; font-size: 14px; font-weight: bold;">
                Olá, <?php echo $_SESSION['usuario_nome']; ?>!
            </span>
            <a href="logout.php" style="color: #ff5555; margin-left: 10px;">[ Sair ]</a>
            <a href="carrinho.php">🛒 Carrinho (<?php echo $qtd_carrinho; ?>)</a>
        </nav>
    </header>

    <!-- BARRA DE BUSCA -->
    <div class="barra-busca-container">
        <form method="GET" action="loja.php" style="margin: 0;">
            <input type="text" name="busca" placeholder="Pesquisar por produto ou categoria..." value="<?php echo htmlspecialchars($busca); ?>">
            <button type="submit">Buscar</button>
            <?php if($busca !== ''): ?>
                <a href="loja.php" style="color: white; margin-left: 10px; font-size: 12px;">Limpar Busca</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="catalogo-container">
        
        <!-- CABEÇALHO -->
        <div class="catalogo-header">
            <h2>Catálogo de Vendas</h2>
            <p>Aceitamos cartão, boleto e promessas!</p>
        </div>

        <!-- AVISO -->
        <div class="aviso-piscante">
            ⚠️ NÃO DAMOS REEMBOLSO ⚠️
        </div>

        <!-- PRODUTOS -->
        <div class="produtos-grid">
            
            <?php if(count($produtos) == 0): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 20px; font-weight: bold; font-size: 18px;">
                    Nenhum produto ou categoria encontrado para "<?php echo htmlspecialchars($busca); ?>".
                </div>
            <?php endif; ?>

            <?php foreach($produtos as $produto): ?>
                <div class="produto">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    
                    <h3><?php echo $produto['nome']; ?></h3>
                    
                    <div class="detalhes">
                        <?php echo $produto['descricao']; ?>
                    </div>
                    
                    <!-- ESTOQUE EM DESTAQUE -->
                    <div>
                        <?php if ($produto['estoque'] > 0): ?>
                            <span class="estoque-tag">Em estoque: <?php echo $produto['estoque']; ?> un.</span>
                        <?php else: ?>
                            <span class="estoque-tag esgotado">Produto Esgotado</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                    
                    <?php if ($produto['estoque'] > 0): ?>
                      <a href="adicionar_carrinho.php?id=<?php echo $produto['id']; ?>" class="btn-comprar" style="text-align: center;">Comprar</a>
                    <?php else: ?>
                        <button class="btn-comprar" disabled style="opacity: 0.5; cursor: not-allowed;">Esgotado</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- BANNER DO JOGO DA COBRINHA -->
        <div style="background: #000; text-align: center; padding: 15px; border-bottom: 3px dashed #00ff00;">
            <h3 style="margin: 0; color: #00ff00; font-family: 'Courier New', monospace; font-size: 24px; text-shadow: 0 0 10px #00ff00;">
                > INSTALANDO SNAKE.EXE...
            </h3>
            <p style="color: #fff; margin: 5px 0;">Deseja executar o jogo?</p>
            <a href="snake.html" class="btn-comprar" style="display: inline-block; width: 250px; background: #00ff00; border-color: #008800; font-size: 16px; color: #000; font-weight: bold;">
                EXECUTAR
            </a>
        </div>
        
        <!-- RODAPÉ -->
        <div class="rodape-loja">
            <p>© 2006 ERROR 404 Store. Todos os direitos reservados.</p>
            <p><a href="apresentacao.php">Voltar para o Portal Principal</a></p>
        </div>

    </div>

    <script>
        // Pega o elemento do aviso
        const aviso = document.querySelector('.aviso-piscante');
        
        // Lista de cores (arco-íris)
        const cores = ['#b94242', '#ff7f00', '#71ff39', '#00ccff', '#0000ff', '#9808ff', '#ffe600'];

        // Função que troca a cor
        function piscarArcoIris() {
            const corAleatoria = cores[Math.floor(Math.random() * cores.length)];
            aviso.style.background = corAleatoria;
            
            if (corAleatoria === '#ff0000' || corAleatoria === '#2645ad') {
                aviso.style.color = '#421515';
            } else {
                aviso.style.color = '#fff';
            }
        }

        setInterval(piscarArcoIris, 150);
    </script>

    <!-- BOTÃO PARA ATIVAR/DESATIVAR ANÚNCIOS -->
    <div style="position: fixed; bottom: 10px; left: 50%; transform: translateX(-50%); z-index: 9999;">
        <form method="POST" action="toggle_ads.php">
            <button type="submit" style="background: #000080; color: white; border: 2px outset #fff; padding: 5px 15px; cursor: pointer; font-weight: bold; font-family: Tahoma;">
                <?php echo (isset($_SESSION['ads_off']) && $_SESSION['ads_off']) ? 'Ligar Anúncios' : 'Desligar Anúncios'; ?>
            </button>
        </form>
    </div>

    <!-- CONTAINERS DAS PROPAGANDAS -->
    <?php if (!isset($_SESSION['ads_off']) || !$_SESSION['ads_off']): ?>
        <div id="ads-left" class="ads-lateral"></div>
        <div id="ads-right" class="ads-lateral"></div>

       <script>
    const imagensAds = [
        "https://i.pinimg.com/736x/d1/9f/1a/d19f1a4f738532fa0776aa822538d233.jpg",
        "https://i.pinimg.com/736x/cd/ac/1c/cdac1c8ee35ad25fac59bdfa6dfb8eb0.jpg",
        "https://i.pinimg.com/1200x/d0/67/f4/d067f42888c8ae7328fc5fe3ff7a59c3.jpg",
        "https://i.pinimg.com/736x/9e/0d/7b/9e0d7bdb88b64ce49ac1f27a08effaa9.jpg",
        "https://i.pinimg.com/736x/54/61/53/546153a714357993b712b84e763e5980.jpg",
        "https://i.pinimg.com/1200x/df/58/b9/df58b9b9cb2912d13e9092be2b450d94.jpg",
        "https://i.pinimg.com/736x/0e/d5/21/0ed521d5f72ebc1766e73d675afbac13.jpg",
        "https://i.pinimg.com/736x/52/e3/ef/52e3ef7591a3c68f6108b62330da4054.jpg",
        "https://i.pinimg.com/1200x/7d/56/de/7d56de8fc2f76c6d76c217eac986d8f6.jpg",
        "https://i.pinimg.com/736x/c3/ce/89/c3ce894d89ca52eaf1eaf285f209c709.jpg",
        "https://i.pinimg.com/1200x/0c/c4/59/0cc4596aaad17cf189a1fe776f77448e.jpg",
        "https://i.pinimg.com/736x/58/57/6b/58576baebecd7b64e0eb7cb63c2381ba.jpg",
        "https://i.pinimg.com/1200x/f8/05/79/f805790dff9f28110f19561982ecb242.jpg",
        "https://i.pinimg.com/736x/2c/4d/e8/2c4de85f710f3c6a35c55805a0df5bb1.jpg",
        "https://i.pinimg.com/1200x/44/b6/c1/44b6c1795ee3e0db568cd07ac5eb0c09.jpg",
        "https://i.pinimg.com/736x/a7/09/fa/a709fa00d6f633120b5dd274a715d7d5.jpg",
        "https://i.pinimg.com/736x/3b/b5/33/3bb5338553c3b34ac9de0fa1615f8871.jpg",
        "https://i.pinimg.com/736x/8d/9d/83/8d9d83491827521891db38cc76526bb6.jpg",
        "https://i.pinimg.com/736x/7e/2f/57/7e2f57de4e5ed3e6275004e6fd2fe763.jpg",
        "https://i.pinimg.com/1200x/ad/0c/2c/ad0c2cb6645f7d65a43265aa7b9292b1.jpg",
        "https://i.pinimg.com/1200x/d8/39/e3/d839e39467ef755a15f5bf01f787afc1.jpg",
        "https://i.pinimg.com/736x/45/90/41/459041fc8f30eb40bc5bab89f5e4b996.jpg",
        "https://i.pinimg.com/736x/b6/94/18/b694184ede1d1aee3cb0dd5d54dde483.jpg",
        "https://i.pinimg.com/736x/59/79/46/59794681bac5de9c503487a7a66ba4e2.jpg",
        "https://i.pinimg.com/736x/06/ea/f7/06eaf714604e950dc4415983da9da71a.jpg",
        "https://i.pinimg.com/1200x/c2/b4/d7/c2b4d7116ee46572ab47dd0addac4c75.jpg",
        "https://i.pinimg.com/1200x/9a/eb/39/9aeb39e0182d7a6d8d0e17c783a1b39e.jpg",
        "https://i.pinimg.com/736x/e3/14/9c/e3149c5ec658b272e460a95b00fcadd7.jpg",
        "https://i.pinimg.com/736x/44/87/e1/4487e1a06ed330c85329ef3a77b469c2.jpg",
        "https://i.pinimg.com/736x/4b/f1/d7/4bf1d7d90ec3c092cf6fbf1fd1fd9126.jpg",
        "https://i.pinimg.com/736x/a6/42/4b/a6424bb6b2f9bc8c02a3e875bd8dc81b.jpg",
        "https://i.pinimg.com/736x/e1/2f/a8/e12fa8c6608425f5d27b6061d3f603f1.jpg",
        "https://i.pinimg.com/1200x/5e/98/2f/5e982fc2af2728f44d77868371ce259e.jpg",
        "https://i.pinimg.com/736x/27/a1/31/27a13106386afcae9fb3b84c2017a1d5.jpg",
        "https://i.pinimg.com/736x/ec/c7/fd/ecc7fd7f04b7fe2ad4107172a6490643.jpg",
        "https://i.pinimg.com/736x/d0/8a/95/d08a95bb209f11a8763e17ea3450ec2c.jpg",
        "https://i.pinimg.com/736x/e8/76/82/e87682deb76f0fd5c8275d2590c33a11.jpg",
        "https://i.pinimg.com/736x/3c/a0/3f/3ca03ff71a297159ed9aab60c2b2d0bc.jpg",
        "https://i.pinimg.com/1200x/33/4a/20/334a20e82e29c8220f345846b0d49b6f.jpg",
        "https://i.pinimg.com/736x/a8/dd/7a/a8dd7a0a5b11a116c1d9148f4df3d807.jpg",
        "https://i.pinimg.com/736x/39/6b/23/396b23cc4aca49bb3cad7e3c65dafe2a.jpg",
        "https://i.pinimg.com/736x/c6/cf/62/c6cf625112435b914ad452b89d8e4fca.jpg",
        "https://i.pinimg.com/736x/d5/a7/85/d5a78511ce017431ae8d1dfae110ead8.jpg",
        "https://i.pinimg.com/736x/97/98/e4/9798e48113437679a9e849dbc47d3333.jpg",
        "https://i.pinimg.com/736x/42/8e/42/428e42d05ec1e61fb17d49fb5923a342.jpg",
        "https://i.pinimg.com/736x/b6/ae/6f/b6ae6f0d019b4a10c9832a10a0775454.jpg",
        "https://i.pinimg.com/736x/c0/08/d7/c008d78e5a5dcd5e040df0f2a434781a.jpg",
        "https://i.pinimg.com/736x/65/9d/12/659d1200b1333850c4e4c0d79df20d6e.jpg",
        "https://i.pinimg.com/736x/fa/b3/d8/fab3d8ea96ef8c83d144ca5b148b1902.jpg",
        "https://i.pinimg.com/736x/de/d6/66/ded666a69210d689ed4124a4c8af53e7.jpg",
        "https://i.pinimg.com/1200x/b1/cc/d1/b1ccd10b3d347d1438896c615f44f26c.jpg",
        ];

    function criarAdAleatorio() {
        const lado = Math.random() > 0.5 ? 'ads-left' : 'ads-right';
        const container = document.getElementById(lado);
        
        if (!container) return;

        const img = document.createElement('img');
        img.src = imagensAds[Math.floor(Math.random() * imagensAds.length)];
        
        img.style.position = 'absolute';
        img.style.border = '2px solid #000';
        img.style.boxShadow = '3px 3px 0 #000';
        
        const tamanhoAleatorio = Math.floor(Math.random() * (400 - 150 + 1)) + 80;
        img.style.width = tamanhoAleatorio + 'px';

        const alturaAleatoria = Math.floor(Math.random() * (window.innerHeight - 250));
        img.style.top = alturaAleatoria + 'px';
        
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.5s ease-in-out';

        container.appendChild(img);
        setTimeout(() => img.style.opacity = '1', 50);

        const tempoNaTela = Math.random() * 3000 + 3000; 
        setTimeout(() => {
            img.style.opacity = '0'; 
            setTimeout(() => img.remove(), 500); 
        }, tempoNaTela);
    }

    criarAdAleatorio();
    criarAdAleatorio();
    setInterval(criarAdAleatorio, 2000);
     </script>
    <?php endif; ?>
 
</body>
</html>