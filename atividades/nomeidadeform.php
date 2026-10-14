
<!DOCTYPE html>
<html lang="Pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nome e idade</title>
</head>
<body>
    <h1>Nome e Idade</h1>

    <form action="#" method="POST">
        <label for="nome">Informe seu nome:</label><br>
        <input type="text" name="nome" id="nome" required>
        <br><br>

        <label for="idade">Informe sua idade:</label><br>
        <input type="number" name="idade" id="idade" required>
        <br><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>

<?php
    if (isset($_POST["nome"]) && isset($_POST["idade"])) {
        $nome = $_POST["nome"];
        $idade = $_POST["idade"];

        echo "Olá " . htmlspecialchars($nome) . ", você possui " . (int)$idade . " anos";
    }
?>
