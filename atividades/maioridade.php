
<!DOCTYPE html>
<html lang="Pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maioridade</title>
</head>
<body>
    <h1>Calculo de maioridade</h1>

    <form action="#" method="POST">
        <label for="nome">Informe seu nome:</label><br>
        <input type="text" name="nome" id="nome" required>
        <br><br>

        <label for="datansc">Informe sua data de nascimento:</label><br>
        <input type="date" name="datansc" id="datansc" required>
        <br><br>

        <button type="submit">Calcular</button>
    </form>
</body>
</html>

<?php
    if (isset($_POST["datansc"]) && isset($_POST["nome"])) {
        $nome = $_POST["nome"];
        $dtnascto = new DateTime($_POST["datansc"]);
        $hoje = new DateTime();
        $intervalo = $dtnascto->diff($hoje);
        $idade = $intervalo->y;

        if ($dtnascto > $hoje) {
            echo "Data de nascimento inválida";
        }
        elseif ($idade >= 18) {
            echo "Olá " . htmlspecialchars($nome) . ", você possui " . $idade . " anos e é maior de idade";
        }
        else {
            echo "Olá " . htmlspecialchars($nome) . ", você possui " . $idade . " anos e é menor de idade";
        }
    }
?>
