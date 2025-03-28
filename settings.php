<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "feenix-mariadb.swin.edu.au";
$user = "s103847381";
$pswd = "181202";
$dbnm = "s103847381_db";

$table1 = "friends";
$table2 = "myfriends";
$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("Connection failed: " . mysqli_connect_error());
?>