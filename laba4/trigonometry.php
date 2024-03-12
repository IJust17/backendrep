<?php
function intotrigga($function, &$parameter) {
    $parameter = deg2rad($parameter);

    switch ($function) {
        case 'sin':
            return sin($parameter);
        case 'cos':
            return cos($parameter);
        case 'tan':
            return tan($parameter);
        default:
            return 'Неверная функция';
    }
}

?>
