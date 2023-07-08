<?php
include 'mainfile.php';

// Get registration form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate form data
$errors = array();
if (empty($name)) {
    $errors[] = "Name is required";
}
if (empty($email)) {
    $errors[] = "Email is required";
}
if (empty($password)) {
    $errors[] = "Password is required";
}
if ($password != $confirm_password) {
    $errors[] = "Password confirmation does not match";
}

// If form data is valid, insert into database
if (empty($errors)) {
    // Escape user inputs for security
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    // Hash the password before inserting into the database
    
    
    // Attempt insert query execution
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if(mysqli_query($conn, $sql)){
        echo "Registration successful.";
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
} else {
    // Display error messages
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}

// Close connection
mysqli_close($conn);
?>
