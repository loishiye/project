<?php
include 'mainfile.php';

$admin_id = $_POST["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($admin_id)) {
        //data received

        //checking the status of other admin
        $sql = "SELECT status FROM users WHERE id = $admin_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            //data available
            $row = $result->fetch_assoc();

            if ($row["status"] == "Verified User") {
                //change to Unverified
                $status = "Unverified User";


                $statement = "UPDATE users SET status='$status' WHERE id=$admin_id";

                if ($conn->query($statement) === TRUE) {
                    //status changed successfully
                    echo "101";
                } else {
                    //failed to change status
                    echo "Not changed";
                }

            } else if ($row["status"] == "Unverified User") {
                //change to verified
                $status = "Verified User";


                $statement = "UPDATE users SET status='$status' WHERE id=$admin_id";

                if ($conn->query($statement) === TRUE) {
                    //status changed successfully
                    echo "101";
                } else {
                    //failed to change status
                    echo "Not changed";
                }

            }

        } else {
            //data not available
        }

    } else {
        //data not received
        echo "Could not receive the admin ID";
    }
}
?>