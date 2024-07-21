<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "payment_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $amount = htmlspecialchars($_POST['amount']);

    if (isset($_FILES['payment_screenshot']) && $_FILES['payment_screenshot']['error'] == 0) {
        $file_name = $_FILES['payment_screenshot']['name'];
        $file_tmp = $_FILES['payment_screenshot']['tmp_name'];
        $file_type = $_FILES['payment_screenshot']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
          
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file_name);

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            if (move_uploaded_file($file_tmp, $target_file)) {
          
                $sql = "INSERT INTO payments (amount, screenshot) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $amount, $target_file);

                if ($stmt->execute()) {
                    echo "<script>alert('Payment done.'); window.location.href = 'nurseries.php';</script>";
                } else {
                    echo "<script>alert('Failed to record payment. Please try again.'); window.history.back();</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Failed to upload file. Please try again.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error uploading file.'); window.history.back();</script>";
    }
}

$conn->close();
?>
