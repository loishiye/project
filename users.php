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


 if ($row["status"] != "Main Admin") {
    header('Location: home.php');
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
#car-info, #users, #back-home {
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
#users {
    background-color: transparent;
    border: 1px solid green;
    color: green;
    font-weight: bold;
}
#car-info:active, #users:active, #back-home:active {
	background-color: #00e600;
}


#users-data {
	width: 74%;
	margin: 0 auto;
}
#users-data h4 {
	text-align: center;
}
#users-data table {
	width: 100%;
	border-collapse: collapse;
	margin: 10px 0 0 0;
}
#users-data th, td {
	text-align: left;
	padding: 10px;
	border: 1px solid #123;
	font-weight: bold;
}
#users-data th {
	background-color: #2f3947;
    color: white;
}
#users-data button {
	width: 50%;
	padding: 5px;
	font-weight: bold;
	background-color: green;
	border: none;
	border-radius: 10px;
	color: white;
}
#users-data button:active {
	background-color: lightgreen;
}

</style>
	<head>
		<meta charset="utf-8">
		<title>Users Info</title>
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
			    <h2>Other Users Info Page</h2>
			    <h4><?php echo "<span>" . $row["status"] . "</span>" . ": " . $row["name"]; ?></h4>
			</div>
		</div>
		<div class="content">
		    <button id="back-home"><< Back Home</button>
			<button id="car-info"><< Car Information</button>
            <button id="users">Users</button>
            <h2></h2>
		</div>


		<div id="users-data">
			<h4>USERS DATA</h4>
			<table>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Status</th>
				</tr>
				<?php
				    $status = array('Verified User', 'Unverified User');
				    $statement = $conn->prepare("SELECT * FROM users WHERE status IN ('" . implode("','", $status) . "') ORDER BY id DESC ");
                    $statement->execute();
                    $statement->store_result();
                    $idarray = array();

                    if ($statement->num_rows > 0) {
                        $statement->bind_result($id, $name, $email, $status, $password);
                        while ($statement->fetch()) {
                            array_push($idarray, $id);
                        }
                    } else{
                        echo " ";
                    }

                    for ($x = 0; $x < count($idarray); $x++){
                        if(isset($x)){
                            $query = $conn->query("SELECT * FROM users WHERE id = $idarray[$x] ");
                            $res = $query->fetch_array();

                            $user_id = $res["id"];
							$user_name = $res["name"];
							$user_email = $res["email"];
							$user_status = $res["status"];
				?>
				<tr>
					<td><?php echo $user_name; ?></td>
					<td><?php echo $user_email; ?></td>
					<td><button class="change-user" data-post-id="<?php echo $user_id; ?>"><?php echo $user_status; ?></button></td>
				</tr>
				<?php
                        }
                    }
                ?>
			</table>
		</div>
		
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


		var changeButton = document.querySelectorAll('.change-user');

		for (var i = 0; i < changeButton.length; i++) {
			if (changeButton[i].innerHTML == "Unverified User") {
				changeButton[i].style.backgroundColor = 'red';
			}
			changeButton[i].addEventListener('click', changeStatus);
		}

		function changeStatus() {
			//get post id
			var idNumber = event.target.dataset.postId;

			//sending id to the database
			var formdata = new FormData();
			formdata.append('id', idNumber);

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'changestatus.php', true);

			xhr.onload = function() {
				if (this.status == 200) {
					var output = this.responseText;

					if (output == "101") {
						window.location.href = "users.php";
					}
				}
			}

			xhr.send(formdata);
		}

	</script>
</html>
