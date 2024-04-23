<?php
    $sql = 'SELECT * FROM `friends`';
    mysqli_query($connect, $sql);
    if (mysqli_errno($connect)) print_r(mysqli_error());

?>