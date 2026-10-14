
<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="Pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomes</title>
</head>
<body>
    <h1>Cadastro de Nomes</h1>

    <form action="#" method="POST">
        <label for="nome">Informe um nome:</label><br>
        <input type="text" name="nome" id="nome" required>
        <br><br>

        <button type="submit" name="salvar">Salvar Nome</button>
        <button type="submit" name="listar">Mostrar Nomes</button>
    </form>

</body>
</html>

<?php
    if (!isset($_SESSION["nomes"])) {
        $_SESSION["nomes"] = [];
    }

    if (isset($_POST["salvar"])) {
        $nome = trim($_POST["nome"]);

        if ($nome != "") {
            $_SESSION["nomes"][] = $nome;
            echo "Nome salvo com sucesso!";
        }
    }

    if (isset($_POST["listar"])) {
        echo "<h2>Nomes cadastrados:</h2>";

        foreach ($_SESSION["nomes"] as $nome) {
            echo htmlspecialchars($nome) . "<br>";
        }
    }
?>
