<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmer_db";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Name = $_POST['name'];
    $Phone = $_POST['phone'];
    $Location = $_POST['location'];
    $Password = $_POST['password'];

   
    if (strlen($Phone) == 10 && ctype_digit($Phone)) {
     
        $passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).{8}$/";

        if (!empty($Name) && !empty($Phone) && !empty($Location) && !empty($Password) && preg_match($passwordPattern, $Password)) {
            $stmt = $con->prepare("SELECT * FROM register WHERE Name = ? AND Phone = ?");
            $stmt->bind_param("ss", $Name, $Phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
               
                echo "<script>alert('Account exists with the same name or phone number.');</script>";
                echo "<script>window.location.href = 'farmer.html';</script>";
            } else {
               
                $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

                $stmt = $con->prepare("INSERT INTO register (Name, Phone, Location, Password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $Name, $Phone, $Location, $hashedPassword);

                if ($stmt->execute()) {
                    echo "<script>alert('Register Successful!');</script>";
                    echo "<script>alert('Thank You!');</script>";
                    echo "<script>window.location.href = 'first.html';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
            }

            $stmt->close();
        } else {
            echo "<script>alert('Password must be 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
            echo "<script>window.location.href = 'farmer.html';</script>";
        }
    } else {
        echo "<script>alert('Phone field is incorrect. It should be exactly 10 digits.');</script>";
        echo "<script>window.location.href = 'farmer.html';</script>";
    }
}
$con->close();
?>
