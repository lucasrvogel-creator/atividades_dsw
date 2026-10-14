
<?php
    date_default_timezone_set('America/Sao_Paulo');

    if (isset($_POST["salvar"])) {
        $data = date("d/m/Y H:i:s");
        $sorteio = $_POST["numeros"];

        $texto = "Data: " . $data . " | Numeros: " . $sorteio;

        header("Content-Type: text/plain");
        header("Content-Disposition: attachment; filename=sorteio.txt");

        echo $texto;
        exit;
    }

    $numeros = range(0, 60);
    shuffle($numeros);
    $nmrssort = array_slice($numeros, 0, 6);
    sort($nmrssort);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sorteio</title>
</head>
<body>

    <h1>Numeros sorteados:</h1>

    <?php
        echo implode(", ", $nmrssort);
    ?>

    <br><br>

    <form method="POST">
        <input type="hidden" name="numeros"
        value="<?php echo implode(", ", $nmrssort); ?>">

        <button type="submit" name="salvar">Salvar Sorteio</button>
    </form>

    <br>

    <form method="GET">
        <button type="submit">Novo Sorteio</button>
    </form>

</body>
</html>
