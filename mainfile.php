<?php
//start session
session_start();
//error function
function error($errno, $errwo){
	echo "<b>Error:</b> [$errno] $errwo";
}
//set error handler
set_error_handler('error');

//calling server
$servername = "localhost";
$username = "dabe";
$password = "daBEpassword123...!@#";
$mydbase = "car";

//starting connection with server 
$conn = new mysqli($servername, $username, $password, $mydbase);
//check connection
if ($conn->connect_error){
	exit("Connection failed:" . $conn->connect_error);
}
?>