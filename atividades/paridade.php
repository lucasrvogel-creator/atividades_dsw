
<!DOCTYPE html>
<html lang="Pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paridade</title>
</head>
<body>
    <h1>Numeros Pares</h1>

    <form action="#" method="POST">
        <label for="inumero">Informe um Numero:</label><br>
        <input type="number" name="numero" id="inumero" min="0" step="1" required>
        <br><br>

        <button type="submit">Calcular</button>
    </form>
</body>
</html>

<?php
    if (isset($_POST["numero"])) {
        $numero = (int) $_POST["numero"];
        $contador = 0;

        while ($contador <= $numero) {
            if ($contador % 2 == 0) {
                echo $contador . "<br>";
            }

            $contador++;
        }
    }
?>
