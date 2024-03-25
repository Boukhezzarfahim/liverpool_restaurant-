<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "liverpool";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn){
    die("Connection failed:" .mysqli_connect_error());
    
}
mysqli_set_charset($conn, "utf8");
?>

