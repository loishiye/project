<?php
include 'mainfile.php';

//if user did not log in, be directed back to log in
if (!isset($_SESSION['loggedin'])){
	header('Location: login.html');
}

//selecting user data from database
 $id = $_SESSION['id'];
 if ($sql = "SELECT * FROM users WHERE id = $id"){
 	$result = $conn->query($sql);
 	//checking if there is any available data
 	if ($result->num_rows > 0){
 		//data is available 
 		$row = $result->fetch_assoc();
 	}
 }
?>
<!DOCTYPE html>
<html>
<style>
.navtop {
	background-color: #2f3947;
	height: 60px;
	width: 100%;
	border: 0;
}
.navtop div {
	display: flex;
	margin: 0 auto;
	width: 74%;
	height: 100%;
}
.navtop div h1, .navtop div a {
	display: inline-flex;
	align-items: center;
}
.navtop div h1 {
	flex: 1;
	font-size: 24px;
	padding: 0;
	margin: 0;
	color: #eaebed;
	font-weight: normal;
}
.navtop div a {
	padding: 0 20px;
	text-decoration: none;
	color: #c1c4c8;
	font-weight: bold;
}
.navtop div a:hover {
	color: #eaebed;
}
body.loggedin {
	background-color: #f3f4f7;
}
.content {
	width: 74%;
	margin: 0 auto;
}
.content h2 {
	flex: 1;
	margin: 0;
	padding: 25px 0;
	font-size: 22px;
	border-bottom: 1px solid #2f3947;
	color: #4a536e;
}
.content h5 {
	color: red;
}
#car-info, #users, #back-home, #api-info {
    text-decoration: none;
    color: white;
    background-color: green;
    padding: 6px;
	border: none;
	border-radius: 3px;
	width: 15%;
	margin: 10px 0 0 0;
	font-weight: bold;
}
#back-home {
    background-color: transparent;
    border: 1px solid green;
    color: green;
    font-weight: bold;
}
#car-info:active, #users:active, #back-home:active, #api-info:active {
	background-color: #00e600;
}

</style>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
	</head>

	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Car Parking Billing System</h1>
				<a href="logout.php">Logout</a>
			</div>
		</nav>

		<div class="content">
			<div>
			    <h2>Home Page</h2>
			    <h4><?php echo "<span>" . $row["status"] . "</span>" . ": " . $row["name"]; ?></h4>
			</div>
		</div>
		<div class="content">
			<button id="back-home">Home</button>
		    <button id="car-info">Car Information >></button>
			<button id="users">Users >></button>
			<button id="api-info">API Info >></button>
			<?php
			if ($row["status"] == "Unverified User") {
				echo "<h5>To access more pages for data, Please wait for Verification from Main Admin</h5>";
			}
			?>
			<h2></h2>
		</div>

		<?php
		// $searchTerm = "T 234";

		// // Prepare the SQL query
		// $sql = "SELECT * FROM information WHERE car_number LIKE '%$searchTerm%'";
		
		// // Execute the query
		// $results = $conn->query($sql);
		
		// // Check if there are any matching rows
		// if ($results->num_rows > 0) {
		// 	// Loop through the rows and display the data
		// 	while ($rowP = $results->fetch_assoc()) {
		// 		// Display the data from the row
		// 		echo $rowP['car_number'] . "<br>";
		// 	}
		// } else {
		// 	// No matching rows found
		// 	echo "No results found.";
		// }
		
		?>
	</body>

	<script>
		 var backHome = document.getElementById('back-home');
        backHome.addEventListener('click', function() {
            window.location.href = "home.php";
        });


		var carInfo = document.getElementById('car-info');
		carInfo.addEventListener('click', function() {
			window.location.href = "information.php";
		});


        var userInfo = document.getElementById('users');
		userInfo.addEventListener('click', function() {
			window.location.href = "users.php";
		});


		var apiInfo = document.getElementById('api-info');
		apiInfo.addEventListener('click', function() {
			window.location.href = "api.php";
		});
	</script>
</html>
