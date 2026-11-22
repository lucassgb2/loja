<?php
session_start();
require 'config.php';

// Catraca do Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: loja.php");
    exit;
}

$msg = '';

// LÓGICA PARA EXCLUIR O E-MAIL
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM newsletter WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "<p style='color: green; text-align: center; font-weight: bold;'>E-mail removido da lista!</p>";
    } catch (PDOException $e) {
        $msg = "<p style='color: red; text-align: center; font-weight: bold;'>Erro ao remover o e-mail.</p>";
    }
}

// Busca todos os e-mails cadastrados
$stmt = $pdo->query("SELECT * FROM newsletter ORDER BY data_inscricao DESC");
$inscritos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Newsletter | ERROR 404</title>
    <style>
        body {
            background-image: url('https://i.pinimg.com/736x/69/ae/86/69ae86f6a1f2fdb4e3fb4bc0fe3e58a1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Tahoma', 'MS Sans Serif', sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .admin-janela {
            background: #c0c0c0;
            border: 2px solid #fff;
            border-right-color: #000;
            border-bottom-color: #000;
            box-shadow: inset 1px 1px #dfdfdf, inset -1px -1px #808080, 5px 5px 15px rgba(0,0,0,0.5);
            padding: 4px;
            width: 600px;
        }
        .barra-titulo {
            background: #000080;
            color: #fff;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 2px solid #808080;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .admin-nav a {
            color: #000;
            text-decoration: none;
            background: #c0c0c0;
            border: 2px outset #fff;
            padding: 3px 8px;
            font-weight: bold;
        }
        .admin-nav a:active { border: 2px inset #fff; }
        
        .conteudo { padding: 10px; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            font-size: 12px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th { background: #000080; color: #fff; }
        
        /* BOTÃO DE EXCLUIR ESTILO WINDOWS */
        .btn-excluir {
            background: #c0c0c0;
            color: #ff0000;
            text-decoration: none;
            padding: 2px 6px;
            border: 2px outset #fff;
            font-weight: bold;
            font-size: 11px;
            cursor: pointer;
        }
        .btn-excluir:active {
            border: 2px inset #fff;
        }
    </style>
</head>
<body>

    <div class="admin-janela">
        <div class="barra-titulo">
            <span>admin_newsletter.exe</span>
            <span onclick="window.location.href='admin.php'" style="cursor:pointer;">X</span>
        </div>

        <div class="admin-nav">
     <div><a href="admin.php">« Voltar ao Menu</a></div>
     <div><strong>Lista de E-mails Banidos</strong></div>
     </div>

        <div class="conteudo">
            <?php echo $msg; ?>
            
            <p style="font-size: 12px; text-align: center; color: #333;">
                Total de pessoas interessadas no jornal: <strong><?php echo count($inscritos); ?></strong>
            </p>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>E-mail</th>
                        <th>Data da Inscrição</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($inscritos) == 0): ?>
                        <tr><td colspan="4" style="text-align: center;">Nenhum assinante ainda.</td></tr>
                    <?php endif; ?>

                    <?php foreach($inscritos as $i): ?>
                    <tr>
                        <td>#<?php echo $i['id']; ?></td>
                        <td><?php echo $i['email']; ?></td>
                        <td><?php echo date('d/m/Y \à\s H:i', strtotime($i['data_inscricao'])); ?></td>
                        <td>
                            <!-- BOTÃO DE EXCLUIR COM ALERTA DE CONFIRMAÇÃO -->
                            <a href="admin_newsletter.php?deletar=<?php echo $i['id']; ?>" 
                               class="btn-excluir" 
                               onclick="return confirm('Tem certeza que deseja desbanir a pessoa? <?php echo $i['email']; ?>?')">
                               X
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>