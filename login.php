<?php
include 'mainfile.php';

$email = $_POST['email'];
$passwordc =$_POST['password'];

 if($sql = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?") ) {
 	//binding parameter
 	$sql->bind_param('s', $_POST["email"]);
 	$sql->execute();
 	$sql->store_result();
 
 	if($sql->num_rows > 0) {
 		$sql->bind_result($id, $email, $passwordc);
 		$sql->fetch();
 		//to verify password
 		if($_POST["password"] === $passwordc) {
 			//password confirmed
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['id'] = $id;
 			header("Location: home.php");
			
 		} else {
 			exit("password not correct");
 		}
 	} else {
 		exit("email is not registered");
 	}


 	$sql->close();
 }
?>