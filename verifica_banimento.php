<?php
if (isset($_SESSION['usuario_id'])) {
    $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt_ban = $pdo->prepare("SELECT id FROM newsletter WHERE email = ?");
        $stmt_ban->execute([$user['email']]);
        if ($stmt_ban->fetch()) {
            session_destroy();
            echo "<script>alert('Sua conta está suspensa. Fale com o suporte.'); window.location.href='login.php';</script>";
            exit;
        }
    }
}
?>