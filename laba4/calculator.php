<?php
require_once 'trigonometry.php';

function transformto($expression) {
    $arguments = interpretation($expression);
    return calculate($arguments);
}

function interpretation($expression) {
    preg_match_all('/(-?\d+\.?\d*)|([\+\-\*\/\(\)])|(sin|cos|tan)/', $expression, $matches);
    $arguments = $matches[0];

    $together = [];
    for ($i = 0; $i < count($arguments); $i++) {
        if (in_array($arguments[$i], ['sin', 'cos', 'tan']) && is_numeric($arguments[$i + 1])) {
            $together[] = ['func' => $arguments[$i], 'arg' => $arguments[$i + 1]];
            $i++;
        } else {
            $together[] = $arguments[$i];
        }
    }

    return $together;
}

function calculate($arguments) {
    $index = 0;
    return transtoformulated($arguments, $index);
}

function transtoformulated(&$arguments, &$index) {
    $result = parsitagain($arguments, $index);

    while ($index < count($arguments) && (in_array($arguments[$index], ['+', '-']))) {
        $operator = $arguments[$index++];
        $operand = parsitagain($arguments, $index);

        if ($operator == '+') {
            $result += $operand;
        } else {
            $result -= $operand;
        }
    }

    return $result;
}

function parsitagain(&$arguments, &$index) {
    $result = zaparssit($arguments, $index);

    while ($index < count($arguments) && (in_array($arguments[$index], ['*', '/']))) {
        $operator = $arguments[$index++];
        $operand = zaparssit($arguments, $index);

        if ($operator == '*') {
            $result *= $operand;
        } else {
            $result /= $operand;
        }
    }

    return $result;
}

function zaparssit(&$arguments, &$index) {
    if (is_numeric($arguments[$index]) || ($arguments[$index] == '-' && is_numeric($arguments[$index + 1]))) {
        $result = $arguments[$index++];
        if ($result == '-' && is_numeric($arguments[$index])) {
            $result .= $arguments[$index++];
        }
    } elseif (in_array($arguments[$index], ['sin', 'cos', 'tan', 'cot', 'sec', 'csc'])) {
        $func = $arguments[$index++];
        $arg = transtoformulated($arguments, $index);
        $result = intotrigga($func, $arg);
    } elseif ($arguments[$index] == '(') {
        $index++;
        $result = transtoformulated($arguments, $index);
        $index++;
    }

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['expression'])) {
        $expression = $_POST['expression'];
        $result = transformto($expression);
        echo $result;
    }
}
?>
