<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmer_db";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Name = $_POST['farmerName'];
    $Password = $_POST['password'];

    if (!empty($Name) && !empty($Password)) {
        $stmt = $con->prepare("SELECT Password FROM register WHERE Name = ?");
        if (!$stmt) {
            echo "Prepare failed: (" . $con->errno . ") " . $con->error;
            exit();
        }
        $stmt->bind_param("s", $Name);

        if ($stmt->execute()) {
            $stmt->bind_result($hashedPassword);
            if ($stmt->fetch() && password_verify($Password, $hashedPassword)) {
                echo "<script>
                        alert('Login Successful! Welcome, $Name!');
                        window.location.href = 'dashboard.html';
                      </script>";
            } else {
                echo "<script>alert('Login Failed: Invalid Name or Password.')
                window.location.href = 'AfterRegister.html';
                </script>";
            }
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Login Failed: Please fill all fields.')</script>";
    }
}

$con->close();
?>
