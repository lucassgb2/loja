<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email_news']), FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (?)");
            $stmt->execute([$email]);
            
            // Mensagem de sucesso (mas na verdade é banimento kkkk)
            header("Location: apresentacao.php?news=banido");
            exit;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                header("Location: apresentacao.php?news=erro_duplicado");
            } else {
                header("Location: apresentacao.php?news=erro");
            }
            exit;
        }
    } else {
        header("Location: apresentacao.php?news=invalido");
        exit;
    }
}
?>