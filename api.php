<!DOCTYPE html>
<html>
<head>
  <title>API Documentation</title>
  <style>
    /* CSS styles for the API documentation page */
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h1 {
      color: #333;
    }

    .endpoint {
      margin-top: 20px;
    }

    .endpoint h2 {
      color: #333;
    }

    .endpoint p {
      color: #666;
    }

    .endpoint pre {
      background-color: #f4f4f4;
      padding: 10px;
      border-radius: 5px;
      color: red;
    }
    .endpoint pre span {
      color: #123;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>Endpoint Documentation</h1>

  <div class="endpoint">
    <pre>
      <span>
POST /getdata.php
Content-Type: application/x-www-form-urlencoded
Method: POST
      </span>


      {
        "endpoint": "http://localhost/traffic/getdata.php",
        "method": "POST",
        "plate number": name = "plate_number",
        "location": name = "location"
      }
      <span>
Example of received data
      </span>
      {
        $plateNo = $_POST["plate_number"];
        $location = $_POST["location"];
      }

      <span>
Codes to Insert Sent data to the database
Method: POST (MUST)
      </span>

      
        
include 'mainfile.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $plateNo = $_POST["plate_number"];
    $location = $_POST["location"];
    

    if (isset($plateNo) && isset($location)) {
        //data received
        $date = date("Y-m-d");

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
                if ($statement = $conn->prepare("INSERT INTO information(id, car_number, location, date, time) VALUES('', ?, ?, ?, now())")) {
                    $statement->bind_param('sss', $plateNo, $location, $date);
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
            if ($statement = $conn->prepare("INSERT INTO information(id, car_number, location, date, time) VALUES('', ?, ?, ?, now())")) {
                $statement->bind_param('sss', $plateNo, $location, $date);
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
    echo "Use POST method";
}

      

    </pre>
  </div>

  <!-- Add more endpoint sections as needed -->

  <script>
    // JavaScript code for interactive behavior (if required)
    // You can add interactivity using JavaScript, such as collapsing/expand endpoint details, making API requests, etc.
    // Add your custom JavaScript code here.
  </script>
</body>
</html>
