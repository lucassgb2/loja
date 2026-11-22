<?php
session_start();
require 'config.php';

$erro = '';
$sucesso = '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['password'];
    $confirma_senha = $_POST['confirm_password'];

    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } elseif ($senha !== $confirma_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verifica se o e-mail já existe no banco (Prepared Statement)
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            // Criptografa a senha (Segurança exigida pelo professor)
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere no banco (is_admin = 0, pois é um cliente comum)
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, is_admin) VALUES (?, ?, ?, 0)");
            
            try {
                $stmt->execute([$nome, $email, $senha_hash]);
                $sucesso = "Conta criada com sucesso! Você já pode fazer login.";
            } catch (PDOException $e) {
                $erro = "Erro ao criar conta: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ERROR 404</title>
    
    <!-- Puxando o visual do login -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="efeitos.css">
</head>
<body>

    <!-- MENU SUPERIOR -->
    <header class="navbar">
        <a href="apresentacao.php" class="logo-box" aria-label="Página inicial">
            <img src="https://i.pinimg.com/736x/76/6f/6f/766f6f492fb2fde6e689351ecbb41471.jpg" alt="Logo" class="logo-img">
            <span class="logo-text">E̷̢̛̗͚̰̖̩̓͝R̸̡̨̪̪̰̓͝R̵̢̛̰̩͚̓͝Ǫ̷̛̗̰̖̓͝R̸̢̛̰̩͚̓͝ 404 (register) </span>
        </a>

        <nav class="navigation">
            <a href="apresentacao.php">Home</a>
            <a href="loja.php" style="color: #00ff00;">Loja</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <!-- CAIXA DE LOGIN/REGISTRO -->
    <main>
        <section class="login-box" id="loginBox" style="width: 400px;">
            <h2>Registrar</h2>

            <!-- Mostra mensagens de erro ou sucesso -->
            <?php if($erro): ?>
                <p style="color: red; text-align: center; font-weight: bold; margin-bottom: 15px;"><?php echo $erro; ?></p>
            <?php endif; ?>
            
            <?php if($sucesso): ?>
                <p style="color: green; text-align: center; font-weight: bold; margin-bottom: 15px;"><?php echo $sucesso; ?></p>
                <p style="text-align: center;"><a href="login.php" style="color: #162b3b; font-weight: bold;">Ir para o Login</a></p>
            <?php else: ?>

            <form method="POST" action="register.php">
                <!-- NOME -->
                <div class="input-box">
                    <input type="text" id="nome" name="nome" placeholder=" " required>
                    <label for="nome">Nome de usuario</label>
                    <span class="input-icon">👤</span>
                </div>

                <!-- EMAIL -->
                <div class="input-box">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Email</label>
                    <span class="input-icon">✉</span>
                </div>

                <!-- SENHA -->
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Senha</label>
                    <span class="input-icon">♟</span>
                </div>

                <!-- CONFIRMAR SENHA -->
                <div class="input-box">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required>
                    <label for="confirm_password">Confirmar Senha</label>
                    <span class="input-icon">♟</span>
                </div>

                <button type="submit" class="login-button">
                    Criar Conta
                </button>

                <div class="register-link">
                    <p>Já tem uma conta? <a href="login.php">Faça Login</a></p>
                </div>
            </form>
            
            <?php endif; ?>
        </section>
    </main>

    <div id="ads-left" aria-hidden="true"></div>
    <div id="ads-right" aria-hidden="true"></div>
    <div id="notifications" aria-live="polite"></div>
    <div id="flyers-layer" aria-hidden="true"></div>

    <script src="efeitos.js"></script>
</body>
</html>