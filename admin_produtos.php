<?php
session_start();
require 'config.php';

// CATRACA DO ADMIN
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: loja.php");
    exit;
}

$msg = '';
$modo_edicao = false;
$produto_editar = ['id' => '', 'nome' => '', 'descricao' => '', 'preco' => '', 'estoque' => '', 'imagem' => '', 'categoria_id' => 1];

// ==========================================
// 1. DELETAR PRODUTO
// ==========================================
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "<p style='color: green; font-weight: bold;'>Produto excluído com sucesso!</p>";
    } catch (PDOException $e) {
        $msg = "<p style='color: red; font-weight: bold;'>Erro ao excluir (o produto pode estar em um pedido).</p>";
    }
}

// ==========================================
// 2. CARREGAR PRODUTO PARA EDIÇÃO
// ==========================================
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto_editar = $stmt->fetch();
    if ($produto_editar) {
        $modo_edicao = true;
    }
}

// ==========================================
// 3. SALVAR (CADASTRAR OU ATUALIZAR)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = str_replace(',', '.', $_POST['preco']); // Troca vírgula por ponto
    $estoque = $_POST['estoque'];
    $imagem = trim($_POST['imagem']);
    $categoria_id = $_POST['categoria_id'];
    $id = $_POST['id'];

    if (empty($nome) || empty($preco) || empty($estoque)) {
        $msg = "<p style='color: red; font-weight: bold;'>Preencha os campos obrigatórios!</p>";
    } else {
        if (!empty($id)) {
            // Atualizar
            $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, imagem=?, categoria_id=? WHERE id=?");
            $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem, $categoria_id, $id]);
            $msg = "<p style='color: green; font-weight: bold;'>Produto atualizado com sucesso!</p>";
            $modo_edicao = false;
        } else {
            // Cadastrar novo
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, imagem, categoria_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $descricao, $preco, $estoque, $imagem, $categoria_id]);
            $msg = "<p style='color: green; font-weight: bold;'>Produto cadastrado com sucesso!</p>";
        }
    }
}

// Busca todos os produtos para listar
$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll();

// Busca categorias para o select
$stmt_cat = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt_cat->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Produtos | ERROR 404</title>
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

        /* ESTILO DA TABELA */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #000080;
            color: #fff;
        }
        td img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border: 1px solid #ccc;
        }

        /* BOTÕES */
        .btn {
            display: inline-block;
            padding: 4px 10px;
            text-decoration: none;
            border: 2px outset #fff;
            background: #c0c0c0;
            color: #000;
            font-weight: bold;
            font-size: 11px;
            cursor: pointer;
        }
        .btn:active { border: 2px inset #fff; }
        .btn-vermelho { color: #ff0000; }
        .btn-azul { color: #0000ff; }
        .btn-verde { background: #00ff00; color: #000; width: 100%; padding: 10px; font-size: 14px; }

        /* FORMULÁRIO */
        .form-box {
            background: #dfdfdf;
            border: 2px inset #fff;
            padding: 15px;
            margin-top: 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .form-group input, .form-group select, .form-group textarea {
            padding: 5px;
            border: 2px inset #fff;
            font-family: 'Tahoma', sans-serif;
            font-size: 12px;
        }
        .form-group textarea { resize: vertical; }
    </style>
</head>
<body>

    <div class="admin-janela">
        <div class="barra-titulo">
            <span>admin_produtos.exe</span>
            <span onclick="window.location.href='admin.php'" style="cursor:pointer;">X</span>
        </div>

        <div class="admin-nav">
            <div>
                <a href="admin.php">« Voltar ao Menu</a>
            </div>
            <div>
                <strong>Modo Administrador</strong>
            </div>
        </div>

        <div class="conteudo">
            <?php echo $msg; ?>

            <!-- LISTAGEM DE PRODUTOS -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><img src="<?php echo $p['imagem']; ?>" alt=""></td>
                        <td><?php echo $p['nome']; ?></td>
                        <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $p['estoque']; ?></td>
                        <td>
                            <a href="admin_produtos.php?editar=<?php echo $p['id']; ?>" class="btn btn-azul">Editar</a>
                            <a href="admin_produtos.php?deletar=<?php echo $p['id']; ?>" class="btn btn-vermelho" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- FORMULÁRIO (NOVO OU EDITAR) -->
            <div class="form-box">
                <h3 style="margin-top:0; font-size: 14px;">
                    <?php echo $modo_edicao ? '✏️ Editando Produto #' . $produto_editar['id'] : '➕ Cadastrar Novo Produto'; ?>
                </h3>
                
                <form method="POST" action="admin_produtos.php">
                    <input type="hidden" name="id" value="<?php echo $produto_editar['id']; ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nome do Produto:</label>
                            <input type="text" name="nome" value="<?php echo $produto_editar['nome']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Categoria:</label>
                            <select name="categoria_id">
                                <?php foreach($categorias as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo ($produto_editar['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo $cat['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Preço (R$):</label>
                            <input type="text" name="preco" value="<?php echo $produto_editar['preco']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Estoque:</label>
                            <input type="number" name="estoque" value="<?php echo $produto_editar['estoque']; ?>" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>URL da Imagem:</label>
                            <input type="text" name="imagem" value="<?php echo $produto_editar['imagem']; ?>">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Descrição:</label>
                            <textarea name="descricao" rows="3"><?php echo $produto_editar['descricao']; ?></textarea>
                        </div>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-verde">
                        <?php echo $modo_edicao ? 'Salvar Alterações' : 'Cadastrar Produto'; ?>
                    </button>
                    
                    <?php if($modo_edicao): ?>
                        <br><br>
                        <a href="admin_produtos.php" style="font-size: 11px; color: #000;">Cancelar Edição</a>
                    <?php endif; ?>
                </form>
            </div>

        </div>
    </div>

</body>
</html>