<?php
$db = require('db.php');
$connect = mysqli_connect($db['host'], $db['username'], $db['password'], $db['database']);
    if (mysqli_connect_errno()) print_r(mysqli_connect_error());
require('header.php');
    if(isset($_GET) && $_GET['p']=='view') include('view.php');
require('footer.html');
?>