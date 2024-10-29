<?php
$a = 1;
$b = 2;
$c = 4;

if ($a + $b == $c) {
    echo "Acertou!";
} else {
    echo "Errou!";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Soma</title>
</head>
<body>

    <form action="processa.php" method="post">
        <label for="a">Digite o valor de A:</label>
        <input type="" id="a" name="a" ><br><br>

        <label for="b">Digite o valor de B:</label>
        <input type="number" id="b" name="b" required><br><br>

        <label for="c">Digite o valor de C:</label>
        <input type="number" id="c" name="c" required><br><br>

        <input type="submit" value="Verificar">
    </form>

</body>
</html>