<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Old settings (commented out for reference)
// $host = "feenix-mariadb.swin.edu.au";
// $user = "s103847381";
// $pswd = "181202";
// $dbnm = "s103847381_db";

$host = getenv("DB_HOST"); // Replace with Azure MySQL hostname
$user = getenv("DB_USERNAME"); // Replace with Azure username
$pswd = getenv("DB_PASSWORD"); // Replace with Azure password
$dbnm = getenv("DB_DATABASE"); // Replace with Azure database name

$table1 = "friends";
$table2 = "myfriends";
$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("Connection failed: " . mysqli_connect_error());
?>
