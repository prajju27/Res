<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cus_database";


$con = new mysqli($servername, $username, $password, $dbname);


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Name = $_POST['name'];
    $Phone = $_POST['phone'];
    $Location = $_POST['location'];

    if (strlen($Phone) == 10 && ctype_digit($Phone)) {
       
        if (!empty($Name) && !empty($Phone) && !empty($Location)) {

        
            $stmt = $con->prepare("SELECT * FROM register WHERE Name = ? AND Phone = ?");
            $stmt->bind_param("ss", $Name, $Phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
               
                echo "<script>alert('Account exists with the same name or phone number.');</script>";
                echo "<script>window.location.href =customer.html';</script>";
            } else {
            
                $stmt = $con->prepare("INSERT INTO register (Name, Phone, Location) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $Name, $Phone, $Location);

                if ($stmt->execute()) {
                    echo "<script>alert('Register Successful!');</script>";
               
                    echo "<script>window.location.href = 'first.html';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
            }
            $stmt->close();
        } else {
            echo "<script>alert('Please fill in all fields.');</script>";
            echo "<script>window.location.href = 'cus_register.php';</script>";
        }
    } else {
        echo "<script>alert('Phone field is incorrect. It should be exactly 10 digits.');</script>";
        echo "<script>window.location.href = 'cus_register.php';</script>";
    }
}

$con->close();
?>
