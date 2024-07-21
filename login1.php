<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cus_database";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Name = $_POST['name'];
    $Phone = $_POST['number'];

    if (!empty($Name) && !empty($Phone)) {
        $stmt = $con->prepare("SELECT Phone FROM register WHERE Name = ?");
        if (!$stmt) {
            echo "Prepare failed: (" . $con->errno . ") " . $con->error;
            exit();
        }
        $stmt->bind_param("s", $Name);

        if ($stmt->execute()) {
            $stmt->bind_result($dbPhone);
            if ($stmt->fetch()) {
                if ($dbPhone === $Phone) {
                    echo "<script>
                            alert('Login Successful! Welcome, $Name!');
                            window.location.href = 'nurseries.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Login Failed: Invalid Name or Phone number.');
                            window.location.href = 'aftercus.html';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Login Failed: No user found with this Name.');
                        window.location.href = 'aftercus.html';
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
