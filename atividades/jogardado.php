<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joga dados</title>
</head>
</html>

<?php

echo "<button onclick=\"location.reload();\">Jogar dado</button>";
echo "<br>";

$valor_dado = rand(1, 6);

switch ($valor_dado) {

    case 1:
        echo "<br>Você tirou um 1!";
        echo "<br><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRg8gZmPJdZdyyMk3NRW964G7TYkHn5hpM86fmVH8xDfQ&s=10'>";
        break;

    case 2:
        echo "<br>Você tirou um 2!";
        echo "<br><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoGhzLSEdi3tXpWPhS3Tm7i3ik_Cu4OCLbOF674BUKmQ&s=10'>";
        break;

    case 3:
        echo "<br>Você tirou um 3!";
        echo "<br><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-O6MSa4SGAfxZYRAF1S4zBPxjhV1Dyln0wqK3GhxL5Q&s=10'>";
        break;

    case 4:
        echo "<br>Você tirou um 4!";
        echo "<br><img src='https://down-br.img.susercontent.com/file/sg-11134201-22110-0e49w521dcjvfc'>";
        break;

    case 5:
        echo "<br>Você tirou um 5!";
        echo "<br><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR2zUkVplhY95kdyhrix2JYOaKlm4TWq3PEPowc5ZuvOw&s=10'>";
        break;

    case 6:
        echo "<br>Você tirou um 6!";
        echo "<br><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThCM-QciGTzJE4aSL4fZFaocgr6s1YntH1jkjRb77ZoQ&s=10'>";
        break;
}
?>