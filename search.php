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

 if ($row["status"] == "Unverified User") {
    header('Location: home.php');
 } 

 if (!isset($_POST["plate_number"])) {
    //no data received
    header('Location: information.php');
 }
 if (empty($_POST["plate_number"])) {
    //no data received
    header('Location: information.php');
 }
?>
<html>
    <head>
        <title>Car Info</title>
    </head>

    <style>
        body{
	       background-color: #f3f4f7;
        }
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

.content {
	width: 74%;
	margin: 0 auto;
}
.content h2 {
	margin: 0;
	padding: 25px 0;
	font-size: 22px;
	border-bottom: 1px solid #2f3947;
	color: #4a536e;
}
#car-info {
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
#car-info {
    background-color: transparent;
    border: 1px solid green;
    color: green;
    font-weight: bold;
}
#car-info:active {
	background-color: #00e600;
}
       
        #search {
            width: 80%;
            margin: 10px auto;
            display: inline-block;
        }
        #search input {
            width: 30%;
            margin: 0 0 0 55%;
            padding: 5px;
            display: inline-block;
            border: 1px solid #2f3947;
            background-color: transparent;
            color: #2f3947;
            font-weight: bold;
        }
        #search button {
            width: 10%;
            padding: 5px;
            display: inline-block;
            border: 1px solid #2f3947;
            background-color: #2f3947;
            color: white;
            font-weight: bold;
        }
        #table {
            width: 74%;
            margin: 10px auto;
        }
        #table table {
            border-collapse: collapse;
            width: 100%;
        }
        #table th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #123;
        }
        #table th {
            background-color: #2f3947;
            color: white;
            font-weight: bold;
        }
    </style>
    <body>
        <nav class="navtop">
			<div>
				<h1>Car Parking Billing System</h1>
				<a href="logout.php">Logout</a>
			</div>
		</nav>

        <div class="content">
			<div>
			    <h2>Search Results For - <?php echo $_POST["plate_number"]; ?></h2>
			    <h4><?php echo "<span>" . $row["status"] . "</span>" . ": " . $row["name"]; ?></h4>
			</div>
		</div>
        <div class="content">
            <button id="car-info"><< Back</button>
            <h2></h2>
		</div>

        <div id="table">
            <table>
                <tr>
                    <th>Id</th>
                    <th>Car Number</th>
                    <th>Location</th>
                    <th>Time</th>
                </tr>
                <?php
                    $searchTerm = $_POST["plate_number"];

                    $statement = $conn->prepare("SELECT * FROM information WHERE car_number LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%' OR date LIKE '%$searchTerm%' ORDER BY id ASC ");
                    $statement->execute();
                    $statement->store_result();
                    $idarray = array();

                    if ($statement->num_rows > 0) {
                        $statement->bind_result($id, $car_number, $location,$date, $time);
                        while ($statement->fetch()) {
                            array_push($idarray, $id);
                        }
                    } else{
                        echo "<h4>No data Found</h4>";
                    }

                    for ($x = 0; $x < count($idarray); $x++){
                        if(isset($x)){
                            $query = $conn->query("SELECT * FROM information WHERE id = $idarray[$x] ");
                            $res = $query->fetch_array();

                            $info_id = $res["id"];
                            $info_carNumber = $res["car_number"];
                            $info_location= $res["location"];
                            $info_time= $res["date"] . " " . $res["time"];
    
                ?>

                <tr>
                    <td><?php echo $info_id; ?></td>
                    <td><?php echo $info_carNumber; ?></td>
                    <td><?php echo $info_location; ?></td>
                    <td><?php echo $info_time; ?></td>
                </tr>
                <?php
                        }
                    }
                ?>
            </table>
        </div>
    </body>

    <script>
		var carInfo = document.getElementById('car-info');
		carInfo.addEventListener('click', function() {
			window.location.href = "information.php";
		});

    </script>
</html>

