<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Old settings (commented out for reference)
// $host = "feenix-mariadb.swin.edu.au";
// $user = "s103847381";
// $pswd = "181202";
// $dbnm = "s103847381_db";

$host = getenv("swe40006group2project-server.mysql.database.azure.com"); // Replace with Azure MySQL hostname
$user = getenv("phucdoox@gmail.com"); // Replace with Azure username
$pswd = getenv("Phucdo1812@"); // Replace with Azure password
$dbnm = getenv("swe40006group2project-server"); // Replace with Azure database name

$table1 = "friends";
$table2 = "myfriends";
$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("Connection failed: " . mysqli_connect_error());
?>