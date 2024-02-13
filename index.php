<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
    <header>
    </header>
    <main>
    <?php
    $equation = "4 * X = 36";
    $tokens = explode(" ", $equation);
    $first = $tokens[0];
    $x = $tokens[2];
    $actual_x = NAN;
    $res = $tokens[4];
    switch ($tokens[1]) {
        case "+":
            $actual_x = $res - $first;
            break;
        case "-":
            $actual_x = $first - $res;
            break;
        case "/":
            $actual_x = $first / $res;
            break;
        case "*":
            $actual_x = $res / $first;
            break;
        case "**":
            $actual_x = log($res, $first);
            break;
    }
    echo "<h1 style='text-align: center;'>$actual_x</h1>"
        ?>
    </main>
    <footer>
        <?php
            echo "Keel Knee Gears";
        ?>
    </footer>
</body>
</html>