<?php
$servername = "localhost";
$dbname = "payrl_db";
$dbusername = "payrl_admin";
$dbpassword = "ZLofiCuEIbMeha1n";

$dbconnection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($dbconnection->connect_error) {
    die("Connection failed: " . $dbconnection->connect_error);
}
?>