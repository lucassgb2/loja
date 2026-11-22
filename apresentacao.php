<?php
$mensagem = "";
if (isset($_GET['news'])) {
if ($_GET['news'] == 'banido') $mensagem = "<p style='color: #ff0000; font-weight: bold; background: #000; padding: 5px; border: 1px solid red;'>⚠️ E-mail registrado.<br>Você foi banido do sistema.<br>Não há como voltar atrás.<br><br><span style='font-size: 11px; color: #aaa; font-weight: normal;'> decreto Nº 99.842, DE 21 DE NOVEMBRO DE 2006</span></p>";
    if ($_GET['news'] == 'erro_duplicado') $mensagem = "<p style='color: #ff0000; font-weight: bold; background: #000; padding: 5px;'>Este e-mail já foi banido anteriormente.</p>";
    if ($_GET['news'] == 'invalido') $mensagem = "<p style='color: #ff0000; font-weight: bold; background: #000; padding: 5px;'>Digite um e-mail válido. e sera eliminado</p>";
}
?>
?>
<!DOCTYPE 
html>
<html lang="pt-BR">
<head>
    <!-- Bootstrap (Adicionado para requisito da matéria) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao ERROR 404 | O Portal</title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="efeitos.css">

    <style>
        /* Estilos exclusivos dessa página de apresentação caótica */
        body {
            background-image: url('https://i.pinimg.com/736x/6a/95/cb/6a95cbd61db4ff1126c6faf25563ca46.jpg');
            color: #000;
            font-family: 'Times New Roman', serif;
        }

        .portal-container {
            max-width: 1000px;
            margin: 100px auto 50px auto;
            background: #e0e0e0;
            border: 4px outset #fff;
            padding: 20px;
            box-shadow: 5px 5px 0 rgba(0,0,0,0.5);
        }

        .portal-header {
            text-align: center;
            border-bottom: 4px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .portal-header h1 {
            font-size: 50px;
            color: #ff0000;
            text-shadow: 2px 2px #000, -1px -1px #fff;
            margin: 0;
            animation: piscar 1s infinite alternate;
        }

        @keyframes piscar {
            from { opacity: 1; }
            to { opacity: 0.6; }
        }

        .portal-header p {
            font-size: 18px;
            font-weight: bold;
            color: #000080;
        }

        /* Layout de 3 colunas */
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 20px;
        }

        @media (max-width: 800px) {
            .portal-grid { grid-template-columns: 1fr; }
        }

        .portal-box {
            border: 2px inset #fff;
            background: #fff;
            padding: 10px;
            margin-bottom: 20px;
        }

        .portal-box h3 {
            background: #000080;
            color: #fff;
            margin: -10px -10px 10px -10px;
            padding: 5px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .portal-box p, .portal-box li {
            font-size: 14px;
            line-height: 1.4;
        }

        .centro-imagem {
            text-align: center;
            margin: 15px 0;
        }

        .centro-imagem img {
            max-width: 100%;
            border: 2px solid #000;
        }

        .btn-loja {
            display: block;
            width: 300px;
            margin: 30px auto;
            text-align: center;
            background: #00ff00;
            color: #000;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            border: 4px outset #00aa00;
            padding: 15px;
            font-family: Arial, sans-serif;
            animation: pular 0.5s infinite alternate;
        }

        .btn-loja:hover {
            background: #ffff00;
            border-color: #aaaa00;
        }

        @keyframes pular {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }

        .marquee-box {
            background: #000;
            color: #00ff00;
            font-family: monospace;
            padding: 5px;
            border: 1px solid #555;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header class="navbar">
       <a href="apresentacao.php" class="logo-box">
            <img src="https://i.pinimg.com/736x/76/6f/6f/766f6f492fb2fde6e689351ecbb41471.jpg" alt="Logo" class="logo-img">
            <span class="logo-text">ERROR 404 (Portal)</span>
        </a>

       <nav class="navigation">
    <a href="apresentacao.php">Início</a>
    <a href="loja.php" style="color: #00ff00; text-decoration: underline;">Acessar Loja</a>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
</nav>
        </nav>
    </header>

    <div class="portal-container">

        <div class="marquee-box">
            <marquee scrollamount="5">!!! ATENÇÃO: VOCÊ ESTÁ ENTRANDO NA ZONA DESCONHECIDA DA INTERNET !!! CUIDADO COM OS ANÚNCIOS MALICIOSOS !!! BEM-VINDO AO PORTAL ERROR 404 !!!</marquee>
        </div>

        <div class="portal-header">
            <h1>ERROR 404</h1>
            <p>O melhor portal de conteúdos inexplicáveis da web!</p>
        </div>

        <div class="portal-grid">

            <!-- COLUNA ESQUERDA -->
            <div class="col-left" style="max-width: 100%;">
                <div class="portal-box">
                    <h3>Sobre o Portal</h3>
                    <p>Fundado em 1998, este site já foi acessado por mais de <strong>10.001 pessoas</strong> (comprovado).</p>
                    <div class="centro-imagem">
                        <!-- TROQUE O LINK ABAIXO PARA COLOCAR UMA IMAGEM SUA -->
                        <img src="https://i.pinimg.com/1200x/77/16/bc/7716bc3ceb1a95b697bd670efdbfe7ac.jpg" alt="GIF" width="100">
                    </div>
                </div>

                <div class="portal-box">
                    <h3>Avisos Importantes</h3>
                    <ul style="padding-left: 20px;">
                        <li>Não olhe para trás.</li>
                        <li>Os homens altos te perseguem.</li>
                        <li>Você está sendo observado.</li>
                        <li>Aceite os cookies.</li>
                        <li><strong>SE DIVIRTA.</strong></li>
                    </ul>
                </div>

                <div class="portal-box">
                    <h3>Usuários ativos | KERNEL: ESTÁVEL</h3>
                    <div id="contador-visitas" style="font-family: monospace; font-size: 20px; text-align: center; background: #000; color: #0f0; padding: 5px; letter-spacing: 2px;">
                        ...
                    </div>
                </div>
            </div>

            <!-- COLUNA CENTRAL -->
            <div class="col-center">
                <div class="portal-box">
                    <h3>A Grande Verdade</h3>
                    <p>
                        Você já se perguntou por que as nuvens no Windows XP são tão perfeitas? Ou por que aquele jogo do Space Invaders nunca acaba? 
                    </p>
                    <p>
                        A internet não é uma rede de computadores. É um organismo vivo. Cada vez que você clica num link suspeito, você alimenta a máquina. Nós do <strong>ERROR 404</strong> estamos aqui para documentar esse fenômeno.
                    </p>
                    <div class="centro-imagem">
                        <img src="https://i.pinimg.com/originals/5a/35/36/5a353680d020a3984c51570aa5b0e2e0.gif" alt="Gif aleatorio" height="150">
                    </div>
                    <p>
                        Se você leu até aqui, você já faz parte do experimento. Mas não se preocupe, temos coisas muito mais estranhas guardadas.
                    </p>
                </div>

                <!-- BOTÃO GIGANTE PARA A LOJA -->
                <a href="loja.php" class="btn-loja">ENTRAR NA LOJA</a>
            </div>

            <!-- COLUNA DIREITA -->
            <div class="col-right">
                <div class="portal-box">
    <h3>Fique Atualizados</h3>
    <p>Assine nosso jornal digital:</p>

    <!-- Mostra a mensagem de erro ou sucesso -->
    <?php if(isset($mensagem)) echo $mensagem; ?>

    <form method="POST" action="newsletter.php">
        <input type="email" name="email_news" placeholder="Seu email aqui" required style="width: 100%; margin-bottom: 5px; padding: 5px; border: 1px solid #000;">
        <button type="submit" style="width: 100%; padding: 5px; background: #c0c0c0; border: 1px outset #fff; cursor: pointer;">Inscrever</button>
    </form>
</div>

                <div class="portal-box">
                    <h3>(Webrings)</h3>
<p style="text-align: center; font-size: 14px;">
    <a href="https://www.youtube.com/@BANANANILSON">[ <- Site Anterior ]</a><br>
    <a href="https://thecatamites.itch.io/goblet-grotto">[ Próximo Site -> ]</a><br>
    <a href="https://youtu.be/CWzDCwhAuKc?si=oAyTLo8Nvqi1U4Wr">[ Aleatório ]</a><br>
    <a href="https://youtu.be/hdLlAIVeAMU?si=ptHmL-cx7R5mBeSV">[ Home do Webring ]</a>
</p>
            </div>

        </div> <!-- FIM DO GRID -->

        <div style="text-align: center; margin-top: 20px; font-size: 12px; border-top: 2px solid #999; padding-top: 10px;">
            Melhor visualizado no Internet Explorer 6.0 - Resolução 800x600 <br>
    <!-- O "onclick" mostra o alerta, o "href" abre o email -->
    <a href="#" onclick="alert('ATENÇÃO: A POSSE, LEITURA OU CIRCULAÇÃO DE LIVROS CONSTITUI INFRAÇÃO CONTRA A ESTABILIDADE COGNITIVA DO ESTADO. DECRETO Nº 99.842, DE 21 DE NOVEMBRO DE 2006'); return false;">Assinar o Livro de Visitas</a> | 
    <a href="https://youtu.be/dQw4w9WgXcQ?si=5CIxpskQ2zTP-lLO&t=42">Mande um Email!</a>
</div>
        </div>
        
    </div> <!-- FIM DO CONTAINER -->

    <!-- SISTEMA DE EFEITOS E ANÚNCIOS -->
    <div id="ads-left" aria-hidden="true"></div>
    <div id="ads-right" aria-hidden="true"></div>
    <div id="notifications" aria-live="polite"></div>
    <div id="flyers-layer" aria-hidden="true"></div>

    <script src="efeitos.js"></script>

    <!-- SCRIPT DO CONTADOR -->
    <script>
        function numeroAleatorio() {
            return Math.floor(Math.random() * 1000) + 1;
        }
        document.getElementById("contador-visitas").innerText = numeroAleatorio();
    </script>
<!-- Player de música invisível -->

</body>
</html>