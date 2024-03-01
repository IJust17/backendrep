<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required = $_POST['required'];

    if (preg_match("/^[0-9\+\-\*\/\(\) ]+$/", $required)) {
        $result = calculaterequired($required);
        echo $result;
    } else {
        echo 'Ошибка: Неверное выражение.';
    }
}

function calculaterequired($required) {
    $required = str_replace(' ', '', $required);
    return calculateAdditionAndSubtraction($required);
}

function calculateAdditionAndSubtraction(&$required) {
    $result = calculateMultiplicationAndDivision($required);

    while (strlen($required) > 0) {
        $operator = $required[0];
        
        if ($operator == '+' || $operator == '-') {
            $required = substr($required, 1);
            $num2 = calculateMultiplicationAndDivision($required);

            if ($operator == '+') {
                $result += $num2;
            } elseif ($operator == '-') {
                $result -= $num2;
            }
        } else {
            break;
        }
    }

    return $result;
}

function calculateMultiplicationAndDivision(&$required) {
    $result = calculateNumber($required);

    while (strlen($required) > 0) {
        $operator = $required[0];

        if ($operator == '*' || $operator == '/') {
            $required = substr($required, 1);
            $num2 = calculateNumber($required);

            if ($operator == '*') {
                $result *= $num2;
            } elseif ($operator == '/') {
                $result /= $num2;
            }
        } else {
            break;
        }
    }

    return $result;
}

function calculateNumber(&$required) {
    $number = "";

    if ($required[0] == "(") {
        $required = substr($required, 1);
        $number = calculateAdditionAndSubtraction($required);
        $required = substr($required, 1); 
    } else {
        while (strlen($required) > 0 && is_numeric($required[0])) {
            $number .= $required[0];
            $required = substr($required, 1);
        }
        $number = intval($number);
    }

    return $number;
}
?>