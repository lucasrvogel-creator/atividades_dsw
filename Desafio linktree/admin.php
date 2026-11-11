<?php
session_start();
require 'conexao.php';

// Protege os textos mostrados na página
function mostrar($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// Verifica se a URL é um endereço http ou https
function urlValida($url) {
    $protocolo = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    return filter_var($url, FILTER_VALIDATE_URL) && in_array($protocolo, ['http', 'https'], true);
}

// Gera um token para proteger as alterações
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$erro = '';
$logado = isset($_SESSION['admin_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        $erro = 'Atualize a página e tente novamente.';
    } elseif ($acao === 'login' && !$logado) {
        // Confere usuário e senha para liberar o painel
        $usuario = trim($_POST['usuario'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $consulta = $conexao->prepare('SELECT id, senha FROM administradores WHERE usuario = ?');
        $consulta->bind_param('s', $usuario);
        $consulta->execute();
        $administrador = $consulta->get_result()->fetch_assoc();

        if ($administrador && password_verify($senha, $administrador['senha'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $administrador['id'];
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header('Location: admin.php');
            exit;
        }
        $erro = 'Usuário ou senha incorretos.';
    } elseif ($logado && in_array($acao, ['incluir', 'alterar'], true)) {
        // Recebe e confere os dados do link
        $url = trim($_POST['url'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $apelido = strtolower(trim($_POST['apelido'] ?? ''));
        $urlAntiga = $_POST['url_antiga'] ?? '';

        if (!urlValida($url) || strlen($url) > 255) {
            $erro = 'Informe uma URL válida com http:// ou https:// (até 255 caracteres).';
        } elseif ($nome === '' || strlen($nome) > 50) {
            $erro = 'O nome precisa ter de 1 a 50 caracteres.';
        } elseif (!preg_match('/^[a-z0-9_-]{1,50}$/', $apelido)) {
            $erro = 'O apelido deve ter até 50 letras, números, _ ou -.';
        } else {
            try {
                if ($acao === 'incluir') {
                    // Parte 1: inclui um novo link
                    $comando = $conexao->prepare('INSERT INTO links (url, nome, apelido) VALUES (?, ?, ?)');
                    $comando->bind_param('sss', $url, $nome, $apelido);
                } else {
                    // Parte 2: altera um link existente
                    $comando = $conexao->prepare('UPDATE links SET url = ?, nome = ?, apelido = ? WHERE url = ?');
                    $comando->bind_param('ssss', $url, $nome, $apelido, $urlAntiga);
                }
                $comando->execute();
                header('Location: admin.php?sucesso=' . ($acao === 'incluir' ? 'incluido' : 'alterado'));
                exit;
            } catch (mysqli_sql_exception $e) {
                // Não permite cadastrar a mesma URL duas vezes (chave primária)
                $erro = $e->getCode() === 1062 ? 'Essa URL já está cadastrada.' : 'Erro ao salvar o link.';
            }
        }
    } elseif ($logado && $acao === 'excluir') {
        // Parte 2: remove o link escolhido
        $url = $_POST['url_antiga'] ?? '';
        $comando = $conexao->prepare('DELETE FROM links WHERE url = ?');
        $comando->bind_param('s', $url);
        $comando->execute();
        header('Location: admin.php?sucesso=excluido');
        exit;
    }
}

$mensagens = [
    'incluido' => 'Link incluído com sucesso!',
    'alterado' => 'Link alterado com sucesso!',
    'excluido' => 'Link excluído com sucesso!'
];
$mensagem = $mensagens[$_GET['sucesso'] ?? ''] ?? '';
$edicao = null;
$links = [];

if ($logado) {
    // Busca um link para preencher o formulário de alteração
    if (isset($_GET['editar'])) {
        $urlEditar = $_GET['editar'];
        $consulta = $conexao->prepare('SELECT url, nome, apelido FROM links WHERE url = ?');
        $consulta->bind_param('s', $urlEditar);
        $consulta->execute();
        $edicao = $consulta->get_result()->fetch_assoc();
    }

    // Lista todos os links cadastrados
    $consulta = $conexao->query('SELECT url, nome, apelido FROM links ORDER BY apelido, nome');
    $links = $consulta->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Linktree</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main class="painel">
        <?php if (!$logado): ?>
            <h1>Acesso administrativo</h1>
            <p class="descricao">Entre para cadastrar, alterar ou excluir links.</p>
            <?php if ($erro !== ''): ?><p class="erro"><?= mostrar($erro) ?></p><?php endif; ?>
            <form method="post" class="formulario-login">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="acao" value="login">
                <label>Usuário
                    <input type="text" name="usuario" required autocomplete="username">
                </label>
                <label>Senha
                    <input type="password" name="senha" required autocomplete="current-password">
                </label>
                <button class="botao" type="submit">Entrar</button>
            </form>
            <p class="dica">Primeiro acesso? Abra <strong>criar_admin.php</strong> para criar o administrador.</p>
            <a class="link-voltar" href="index.php">← Voltar para o site</a>
        <?php else: ?>
            <div class="topo-painel">
                <div>
                    <h1>Gerenciar links</h1>
                    <p class="descricao">Cadastre e organize os links de cada apelido.</p>
                </div>
                <a class="botao secundario" href="sair.php">Sair</a>
            </div>

            <?php if ($erro !== ''): ?><p class="erro"><?= mostrar($erro) ?></p><?php endif; ?>
            <?php if ($mensagem !== ''): ?><p class="sucesso"><?= mostrar($mensagem) ?></p><?php endif; ?>

            <section class="cartao">
                <h2><?= $edicao ? 'Alterar link' : 'Adicionar link' ?></h2>
                <form method="post">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="acao" value="<?= $edicao ? 'alterar' : 'incluir' ?>">
                    <?php if ($edicao): ?>
                        <input type="hidden" name="url_antiga" value="<?= mostrar($edicao['url']) ?>">
                    <?php endif; ?>
                    <label>URL (link completo)
                        <input type="url" name="url" maxlength="255" placeholder="https://github.com/seuusuario" value="<?= mostrar($edicao['url'] ?? '') ?>" required>
                    </label>
                    <label>Nome do link
                        <input type="text" name="nome" maxlength="50" placeholder="Meu GitHub" value="<?= mostrar($edicao['nome'] ?? '') ?>" required>
                    </label>
                    <label>Apelido do perfil
                        <input type="text" name="apelido" maxlength="50" placeholder="fulano" value="<?= mostrar($edicao['apelido'] ?? '') ?>" required>
                    </label>
                    <div class="acoes">
                        <button class="botao" type="submit"><?= $edicao ? 'Salvar alteração' : 'Adicionar link' ?></button>
                        <?php if ($edicao): ?><a class="botao secundario" href="admin.php">Cancelar</a><?php endif; ?>
                    </div>
                </form>
                <p class="dica">Exemplo: use o apelido <strong>fulano</strong> para acessar <strong>localhost/lkt?a=fulano</strong>.</p>
            </section>

            <section class="cartao">
                <h2>Links cadastrados (<?= count($links) ?>)</h2>
                <?php if (count($links) === 0): ?>
                    <p class="aviso">Nenhum link cadastrado até o momento.</p>
                <?php else: ?>
                    <div class="rolagem-tabela">
                        <table>
                            <thead><tr><th>Apelido</th><th>Nome</th><th>URL</th><th>Ações</th></tr></thead>
                            <tbody>
                            <?php foreach ($links as $link): ?>
                                <tr>
                                    <td><a href="index.php?a=<?= urlencode($link['apelido']) ?>" target="_blank" rel="noopener noreferrer">@<?= mostrar($link['apelido']) ?></a></td>
                                    <td><?= mostrar($link['nome']) ?></td>
                                    <td class="coluna-url"><a href="<?= mostrar($link['url']) ?>" target="_blank" rel="noopener noreferrer"><?= mostrar($link['url']) ?></a></td>
                                    <td>
                                        <div class="acoes-tabela">
                                            <a class="botao pequeno-botao secundario" href="admin.php?editar=<?= urlencode($link['url']) ?>">Editar</a>
                                            <form method="post" onsubmit="return confirm('Deseja realmente excluir este link?');">
                                                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="url_antiga" value="<?= mostrar($link['url']) ?>">
                                                <button class="botao pequeno-botao perigo" type="submit">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
            <a class="link-voltar" href="index.php">← Voltar para o site</a>
        <?php endif; ?>
    </main>
</body>
</html>
