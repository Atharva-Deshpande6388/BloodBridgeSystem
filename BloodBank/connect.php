<?php
$server_name = "localhost";
$db_name = "bloodbank";
$pass = "";
$username = "root";

$conn = mysqli_connect($server_name, $username, $pass, $db_name);
if(!$conn)
{
    die("Connection Failed : ".mysqli_connect_error());
}


?>