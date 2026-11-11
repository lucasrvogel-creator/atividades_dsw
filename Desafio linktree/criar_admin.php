<?php
session_start();
require 'conexao.php';

// Cria um token para proteger o formulário
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$erro = '';
$sucesso = false;

// Só permite criar o primeiro administrador
$consulta = $conexao->query('SELECT COUNT(*) AS total FROM administradores');
$jaExiste = (int) $consulta->fetch_assoc()['total'] > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$jaExiste) {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        $erro = 'Atualize a página e tente novamente.';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $usuario)) {
        $erro = 'O usuário deve ter de 3 a 50 letras, números, _ ou -.';
    } elseif (strlen($senha) < 8) {
        $erro = 'A senha precisa ter pelo menos 8 caracteres.';
    } else {
        // Salva um resumo seguro da senha, não a senha original
        $senhaProtegida = password_hash($senha, PASSWORD_DEFAULT);
        $comando = $conexao->prepare('INSERT INTO administradores (usuario, senha) VALUES (?, ?)');
        $comando->bind_param('ss', $usuario, $senhaProtegida);
        $comando->execute();
        $sucesso = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro administrador</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main class="painel pequeno">
        <h1>Primeiro administrador</h1>
        <?php if ($jaExiste): ?>
            <p class="aviso">O administrador já foi cadastrado. Acesse o login.</p>
            <p class="aviso">Por segurança, exclua o arquivo criar_admin.php após a configuração.</p>
            <a class="botao" href="admin.php">Ir para login</a>
        <?php elseif ($sucesso): ?>
            <p class="sucesso">Administrador criado com sucesso!</p>
            <p>Agora exclua o arquivo <strong>criar_admin.php</strong>.</p>
            <a class="botao" href="admin.php">Ir para login</a>
        <?php else: ?>
            <p class="descricao">Cadastre o usuário que poderá alterar os links.</p>
            <?php if ($erro !== ''): ?><p class="erro"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
            <form method="post">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <label>Usuário
                    <input type="text" name="usuario" maxlength="50" required autocomplete="username">
                </label>
                <label>Senha (mínimo 8 caracteres)
                    <input type="password" name="senha" minlength="8" required autocomplete="new-password">
                </label>
                <button class="botao" type="submit">Criar administrador</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
