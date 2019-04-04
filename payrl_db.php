<?php
$servername = "localhost";
$dbname = "payrl_db";
$dbusername = "payrl_admin";
$dbpassword = "ZLofiCuEIbMeha1n";

$dbconnection = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

if (!$dbconnection) {
    die("Connection failed: " . mysqli_connect_error());
}




// $dbconnection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// if ($dbconnection->connect_error) {
//     die("Connection failed: " . $dbconnection->connect_error);
// }
?>