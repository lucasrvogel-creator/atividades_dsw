<?php
require 'conexao.php';

// Evita que textos do banco sejam interpretados como HTML
function mostrar($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// O apelido é recebido pela URL: index.php?a=fulano
$apelido = trim($_GET['a'] ?? '');
$links = [];
$perfis = [];
$apelidoValido = preg_match('/^[a-zA-Z0-9_-]{1,50}$/', $apelido);

if ($apelido !== '' && $apelidoValido) {
    // Busca somente os links do apelido informado
    $consulta = $conexao->prepare('SELECT url, nome FROM links WHERE apelido = ? ORDER BY nome');
    $consulta->bind_param('s', $apelido);
    $consulta->execute();
    $links = $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
} elseif ($apelido === '') {
    // Sem apelido, mostra os perfis cadastrados
    $consulta = $conexao->query('SELECT apelido, COUNT(*) AS quantidade FROM links GROUP BY apelido ORDER BY apelido');
    $perfis = $consulta->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $apelido !== '' ? 'Links de @' . mostrar($apelido) : 'Meu Linktree' ?></title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main class="pagina-publica">
        <div class="avatar">🔗</div>
        <?php if ($apelido === ''): ?>
            <h1>Meu Linktree</h1>
            <p class="descricao">Escolha um perfil para acessar os links.</p>

            <?php if (count($perfis) === 0): ?>
                <p class="aviso">Ainda não existem links cadastrados.</p>
            <?php else: ?>
                <?php foreach ($perfis as $perfil): ?>
                    <a class="botao-link" href="?a=<?= urlencode($perfil['apelido']) ?>">
                        @<?= mostrar($perfil['apelido']) ?>
                        <small><?= (int) $perfil['quantidade'] ?> link(s)</small>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <h1>@<?= mostrar($apelido) ?></h1>
            <p class="descricao">Meus links em um só lugar</p>

            <?php if (!$apelidoValido || count($links) === 0): ?>
                <p class="aviso">Nenhum link encontrado para este apelido.</p>
            <?php else: ?>
                <?php foreach ($links as $link): ?>
                    <?php
                    // Aceita apenas links que começam com http ou https
                    $protocolo = strtolower((string) parse_url($link['url'], PHP_URL_SCHEME));
                    if (!in_array($protocolo, ['http', 'https'], true)) {
                        continue;
                    }
                    ?>
                    <a class="botao-link" href="<?= mostrar($link['url']) ?>" target="_blank" rel="noopener noreferrer">
                        <?= mostrar($link['nome']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
            <a class="link-voltar" href="index.php">← Ver outros perfis</a>
        <?php endif; ?>
        <footer><a href="admin.php">Área administrativa</a></footer>
    </main>
</body>
</html>
