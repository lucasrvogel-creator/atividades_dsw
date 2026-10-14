<?php date_default_timezone_set('America/Sao_Paulo')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteio</title>
</head>
<body>
    <h1>Números sorteados:</h1>
</body>
</html>

<?php
    $numeros = range(0, 60);
    shuffle($numeros);
    $nmrssort = array_slice($numeros, 0, 6);

sort($nmrssort);
echo "Números sorteados: " . implode(", " , $nmrssort);

?>