<?php
/*
mysqli_report(MYSQLI_REPORT_OFF);

// Old settings (commented out for reference)
// $host = "feenix-mariadb.swin.edu.au";
// $user = "s103847381";
// $pswd = "181202";
// $dbnm = "s103847381_db";

$host = getenv("DB_HOST"); // Retrieve Azure MySQL hostname from environment variable
$user = getenv("DB_USER"); // Retrieve Azure username from environment variable
$pswd = getenv("DB_PASSWORD"); // Retrieve Azure password from environment variable
$dbnm = getenv("DB_NAME"); // Retrieve Azure database name from environment variable
$sslcert = "/ssl/DigiCertGlobalRootCA.crt.pem";

$table1 = "friends";
$table2 = "myfriends";
$conn = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("Connection failed: " . mysqli_connect_error());
*/

ini_set ('error_reporting', E_ALL);
ini_set ('display_errors', '1');
error_reporting (E_ALL|E_STRICT);
 
$db_ca_cert = realpath('../ssl/DigiCertGlobalRootCA.crt.pem');
$db_user = "patdmmcaqy";
$db_pass = "Phucdo1812" ;
$db_host = "swe40006group2project-server.mysql.database.azure.com";
$db_db = "swe40006group2project-database";
$db_port = "3306";

$table1 = "friends";
$table2 = "myfriends";

$conn=mysqli_init(); 
mysqli_ssl_set($conn, NULL, NULL, $db_ca_cert, NULL, NULL);
$link = mysqli_real_connect($conn, $db_host, $db_user, $db_pass, $db_db, $db_port, NULL, MYSQLI_CLIENT_SSL);
 
if (!$link)
{
 die ('Connect error (' . mysqli_connect_errno() . '): ' . mysqli_connect_error() . "\n");
} else {
 $res = $conn->query('SHOW TABLES;');
 print_r ($res->fetch_all());
}
?>