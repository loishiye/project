<?php
include 'mainfile.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  
    $plateNo = $_GET["plate_number"];
    $location = $_GET["location"];
    

    if (isset($plateNo) && isset($location)) {
        //data received
        $date = date("Y-m-d");
        $time = date("H:i:s");

        $sql = "SELECT * FROM information WHERE car_number = '$plateNo' AND date = '$date' ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            //data available, check if car existed in same location for less than 1 hr
            $row = $result->fetch_assoc();
            $selectedTime = $row["time"];

            //get current time
            $currentTimeStamp = time();

            //converting selected time from string to time stamp
            $selectedTimeStamp = strtotime($selectedTime);

            //check time difference
            $timeDifference = $currentTimeStamp - $selectedTimeStamp;

            //check if 1 hr has passed
            if ($timeDifference >= 3600) {
                //1 hr has passed, insert data to the database
                if ($statement = $conn->prepare("INSERT INTO information(id, car_number, location, date, time) VALUES('', ?, ?, ?, ?)")) {
                    $statement->bind_param('ssss', $plateNo, $location, $date, $time);
                    $statement->execute();
                    echo "Car Billed";
    
                } else {
                    //failed to insert data
                    echo "Billing Failed";
                }

            } else {
                //less than 1 hr
                echo "Billing Failed";
            }

        } else {
            //data not available, insert to the database
            if ($statement = $conn->prepare("INSERT INTO information(id, car_number, location, date, time) VALUES('', ?, ?, ?, ?)")) {
                $statement->bind_param('ssss', $plateNo, $location, $date, $time);
                $statement->execute();
                echo "Car Billed";

            } else {
                //failed to insert data
                echo "Billing Failed";
            }
    
        }


    } else {
        echo "Could not receive data";
    }

} else {
    echo "Use GET method";
}
?>