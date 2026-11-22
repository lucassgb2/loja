<?php
session_start();
require 'config.php';

// CATRACA DO ADMIN
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    echo "<script>alert('Acesso negado! Área restrita para administradores.'); window.location.href='loja.php';</script>";
    exit;
}

// Estatísticas para o Dashboard
$total_produtos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$total_pedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$faturamento = $pdo->query("SELECT SUM(total) FROM pedidos")->fetchColumn();
$faturamento = $faturamento ? $faturamento : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin | ERROR 404</title>
    <style>
        /* FUNDO DO WINDOWS XP */
        body {
            background-image: url('https://i.pinimg.com/736x/bc/5d/15/bc5d15a32d4feeac6bbedc0b4da02abe.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Tahoma', 'MS Sans Serif', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden; /* Evita barras de rolagem quando as janelas caírem */
        }

        /* JANELA DO SISTEMA */
        .admin-janela {
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right-color: #000;
            border-bottom-color: #000;
            box-shadow: inset 1px 1px #dfdfdf, inset -1px -1px #808080, 5px 5px 15px rgba(0,0,0,0.5);
            padding: 4px;
            width: 700px;
            position: relative;
            z-index: 10;
        }

        /* BARRA DE TÍTULO */
        .barra-titulo {
            background: #000080;
            color: #fff;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .barra-titulo span {
            cursor: pointer;
        }

        /* MENU SUPERIOR */
        .admin-nav {
            display: flex;
            justify-content: space-between;
            background: #c0c0c0;
            padding: 5px;
            border-bottom: 2px solid #808080;
            margin-bottom: 15px;
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

        /* CONTEÚDO */
        .conteudo-janela {
            padding: 10px;
            text-align: center;
        }

        h2 {
            font-size: 18px;
            margin-top: 0;
            color: #000;
            text-transform: uppercase;
            text-decoration: underline;
        }

        /* DASHBOARD (PLACAR) */
        .dashboard-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .dash-box {
            background: #000;
            color: #00ff00;
            border: 2px inset #fff;
            padding: 10px;
            width: 30%;
            text-align: center;
            font-family: 'Courier New', monospace;
        }

        .dash-box h4 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #888;
        }

        .dash-box .numero {
            font-size: 24px;
            font-weight: bold;
            text-shadow: 0 0 5px #00ff00;
        }

        /* CARDS DE OPÇÃO */
        .admin-grid {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }

        .admin-box {
            background: #fff;
            border: 2px inset #c0c0c0;
            width: 45%;
            padding: 15px;
            text-align: center;
        }

        .admin-box h3 {
            margin-top: 0;
            color: #000080;
            font-size: 16px;
        }

        .admin-box p {
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
        }

        /* BOTÕES */
        .btn-win {
            display: block;
            width: 100%;
            background: #c0c0c0;
            border: 2px outset #fff;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn-win:active { border: 2px inset #fff; }

        /* TERMINAL DE HACKER */
        .terminal-box {
            background: #000;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            text-align: left;
            padding: 5px;
            margin-top: 15px;
            border: 2px inset #c0c0c0;
            height: 60px;
            overflow: hidden;
        }

        /* ==========================================
           JANELAS MALUCAS (EASTER EGGS DO ADMIN)
        =========================================== */
        .popup-maluco {
            position: fixed;
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right-color: #000;
            border-bottom-color: #000;
            padding: 3px;
            z-index: 9999;
            box-shadow: 3px 3px 10px rgba(0,0,0,0.5);
            width: 250px;
            display: none;
        }

        .popup-titulo {
            background: #ff0000;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
            padding: 2px 5px;
            display: flex;
            justify-content: space-between;
        }

        .popup-conteudo {
            padding: 10px;
            font-size: 12px;
            text-align: center;
            background: #fff;
            margin-top: 3px;
        }

        .popup-btn {
            background: #c0c0c0;
            border: 1px outset #fff;
            padding: 3px 10px;
            font-size: 11px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="admin-janela">
        
        <div class="barra-titulo">
            <span>Painel_Admin_v2.0.exe</span>
            <div>
                <span onclick="window.location.href='loja.php'">X</span>
            </div>
        </div>

        <div class="admin-nav">
            <div>
                <a href="loja.php">« Voltar para a Loja</a>
            </div>
            <div>
                <span style="margin-right: 10px;">Logado: <strong><?php echo $_SESSION['usuario_nome']; ?></strong></span>
                <a href="logout.php" style="color: #ff0000;">Sair</a>
            </div>
        </div>

        <div class="conteudo-janela">
            
            <!-- DASHBOARD -->
            <div class="dashboard-grid">
                <div class="dash-box">
                    <h4>PRODUTOS</h4>
                    <div class="numero"><?php echo $total_produtos; ?></div>
                </div>
                <div class="dash-box">
                    <h4>PEDIDOS</h4>
                    <div class="numero"><?php echo $total_pedidos; ?></div>
                </div>
                <div class="dash-box">
                    <h4>FATURAMENTO</h4>
                    <div class="numero" style="font-size: 18px;">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></div>
                </div>
            </div>

            <h2>Painel de Controle</h2>
            
                        <div class="admin-grid" style="flex-wrap: wrap; gap: 10px;">
                <!-- PRODUTOS -->
                <div class="admin-box" style="width: 30%;">
                    <h3>📦 Produtos</h3>
                    <p>Gerenciar catálogo.</p>
                    <a href="admin_produtos.php" class="btn-win" style="background: #00ff00; color: #000;">Abrir</a>
                </div>

                <!-- PEDIDOS -->
                <div class="admin-box" style="width: 30%;">
                    <h3>🧾 Pedidos</h3>
                    <p>Ver histórico de vendas.</p>
                    <a href="admin_pedidos.php" class="btn-win" style="background: #00ffff; color: #000;">Abrir</a>
                </div>

                <!-- NEWSLETTER (NOVO) -->
                <div class="admin-box" style="width: 30%;">
                    <h3>✉️ Newsletter</h3>
                    <p>Ver e-mails cadastrados.</p>
                    <a href="admin_newsletter.php" class="btn-win" style="background: #ff00ff; color: #fff;">Abrir</a>
                </div>
            </div>
            <!-- TERMINAL -->
            <div class="terminal-box" id="terminal">
                > Iniciando painel administrativo...<br>
                > Conectando ao banco de dados... OK<br>
                > <span id="cursor">_</span>
            </div>

        </div>
    </div>

    <!-- JANELAS MALUCAS (FICAM ESCONDIDAS) -->
    <div class="popup-maluco" id="popup1">
        <div class="popup-titulo">
            <span>Erro do Sistema</span>
            <span style="cursor:pointer;" onclick="document.getElementById('popup1').style.display='none'">X</span>
        </div>
        <div class="popup-conteudo">
            O administrador está sendo observado. eliminar? <br>
            <button class="popup-btn" onclick="document.getElementById('popup1').style.display='none'">OK</button>
        </div>
    </div>

    <div class="popup-maluco" id="popup2">
        <div class="popup-titulo" style="background: #000080;">
            <span>Atualização</span>
            <span style="cursor:pointer;" onclick="document.getElementById('popup2').style.display='none'">X</span>
        </div>
        <div class="popup-conteudo">
            O sistema está 99% online. <br>
            O outro 1% está morto. <br>
            <button class="popup-btn" onclick="document.getElementById('popup2').style.display='none'">Assustador</button>
        </div>
    </div>

    <div class="popup-maluco" id="popup3">
        <div class="popup-titulo" style="background: #808000;">
            <span>Aviso de Estoque</span>
            <span style="cursor:pointer;" onclick="document.getElementById('popup3').style.display='none'">X</span>
        </div>
        <div class="popup-conteudo">
            Você não deveria estar aqui. por hora <br>
            <button class="popup-btn" onclick="document.getElementById('popup3').style.display='none'">Sair</button>
        </div>
    </div>

    <script>
        // ==========================================
        // 1. EFEITO DO TERMINAL DIGITANDO
        // ==========================================
        const mensagensTerminal = [
            "> Verificando integridade do sistema... OK",
            "> Lendo logs de acesso... OK",
            "> Analisando comportamento do usuário...",
            "> Aviso: Alguém está olhando para a tela.",
            "> Sistema operando em 100%."
        ];
        let indexMsg = 0;
        const terminal = document.getElementById("terminal");

        setInterval(() => {
            terminal.innerHTML = `> Iniciando painel...<br>> ${mensagensTerminal[indexMsg]}<br>> <span id="cursor">_</span>`;
            indexMsg = (indexMsg + 1) % mensagensTerminal.length;
        }, 3000);

        // ==========================================
        // 2. JANELAS MALUCAS CAINDO DO NADA
        // ==========================================
        function criarPopUpMaluco() {
            const popups = ['popup1', 'popup2', 'popup3'];
            // Sorteia um popup
            const idSorteado = popups[Math.floor(Math.random() * popups.length)];
            const popup = document.getElementById(idSorteado);

            // Sorteia a posição na tela
            const left = Math.random() * (window.innerWidth - 250);
            const top = Math.random() * (window.innerHeight - 150);

            popup.style.left = left + 'px';
            popup.style.top = top + 'px';
            popup.style.display = 'block';
            
            // Toca um som de clique (opcional, mas dá o clima)
            // let audio = new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3');
            // audio.volume = 0.2;
            // audio.play();

            // Esconde o popup depois de 4 segundos
            setTimeout(() => {
                popup.style.display = 'none';
            }, 4000);
        }

        // Chama um popup maluco a cada 8 segundos
        setInterval(criarPopUpMaluco, 8000);
        
        // Chama o primeiro depois de 3 segundos
        setTimeout(criarPopUpMaluco, 3000);
    </script>
</body>
</html>