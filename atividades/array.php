<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
</head>
<body>
    <form action="#" method="POST">
  <label>informe o número do Mês</label>
  <input type="number" name="meses" placeholder="Informe o mês">

  
  <button type="submit">Enviar</button>
</form>

</body>
</html>

<?php
$meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "outubro", "Novembro", "Dezembro"];
if (isset($_POST["meses"])) {
    $numeroMes = $_POST["meses"];
    echo "Mês número $numeroMes: {$meses[$numeroMes-1]}";
}

?>