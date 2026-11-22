<?php
session_start();
require 'config.php';

$erro = '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['password'];

    // 1. Busca o usuário no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // 2. Verifica se o e-mail está na lista de banidos (newsletter)
    $stmt_ban = $pdo->prepare("SELECT id FROM newsletter WHERE email = ?");
    $stmt_ban->execute([$email]);
    $banido = $stmt_ban->fetch();

    // 3. Tomada de decisão
    if ($banido) {
        // Se estiver banido, nem deixa tentar a senha
        $erro = "Você foi banido! Crie uma nova conta com outro e-mail para continuar.";
    } elseif ($usuario && password_verify($senha, $usuario['senha'])) {
        // Se NÃO estiver banido e a senha estiver correta, faz o login
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['is_admin'] = $usuario['is_admin'];

        // Redireciona para a loja
        header("Location: loja.php");
        exit;
    } else {
        // Se não estiver banido, mas errou a senha ou o e-mail
        $erro = "E-mail ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ERROR 404</title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="efeitos.css">
</head>
<body>

    <!-- MENU SUPERIOR -->
    <header class="navbar">
        <a href="apresentacao.php" class="logo-box" aria-label="Página inicial">
            <img src="https://i.pinimg.com/736x/76/6f/6f/766f6f492fb2fde6e689351ecbb41471.jpg" alt="Logo" class="logo-img">
            <span class="logo-text">E̷̢̛̗͚̰̖̩̓͝R̸̡̨̪̪̰̓͝R̵̢̛̰̩͚̓͝Ǫ̷̛̗̰̖̓͝R̸̢̛̰̩͚̓͝ 404 (login) </span>
        </a>

        <nav class="navigation">
            <a href="apresentacao.php">Home</a>
            <a href="loja.php" style="color: #00ff00;">Loja</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

    <!-- CAIXA DE LOGIN -->
    <main>
        <section class="login-box" id="loginBox">
            <h2>Login</h2>

            <!-- Mostra mensagem de erro, se houver -->
            <?php if($erro): ?>
                <p style="color: red; text-align: center; font-weight: bold; margin-bottom: 15px;"><?php echo $erro; ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <!-- EMAIL -->
                <div class="input-box">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Email</label>
                    <span class="input-icon">✉</span>
                </div>

                <!-- SENHA -->
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <span class="input-icon">♟</span>
                </div>

                <!-- OPÇÕES -->
                <div class="remember-forgot">
                    <label><input type="checkbox">Zombie?</label>
                    <a href="#">was born before 2012</a>
                </div>

                <!-- BOTÃO -->
                <button type="submit" class="login-button">
                    Login
                </button>

                <!-- REGISTRO -->
                <div class="register-link">
                    <p>
                        Don't have an account?
                        <a href="register.php">Register</a>
                    </p>
                </div>
            </form>
        </section>
    </main>

    <!-- SISTEMA DE ANÚNCIOS E EFEITOS VISUAIS -->
    <div id="ads-left" aria-hidden="true"></div>
    <div id="ads-right" aria-hidden="true"></div>
    <div id="notifications" aria-live="polite"></div>
    <div id="flyers-layer" aria-hidden="true"></div>

    <script src="efeitos.js"></script>

</body>
</html>