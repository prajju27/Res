<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nurseryName = $_POST['nursery_name'];
$plantName = $_POST['plant_name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$location = $_POST['location'];
$phone_number = $_POST['phone_number'];

$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["photo"]["name"]);

$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check === false) {
    die("File is not an image.");
}

if ($_FILES["photo"]["size"] > 5000000) {
    die("Sorry, your file is too large.");
}

$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
if (!in_array($imageFileType, $allowedTypes)) {
    die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
}

if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
    die("Sorry, there was an error uploading your file.");
}

$sql = "INSERT INTO nursery_info (nursery_name, plant_name, photo_path, price, quantity, location,phone_number) 
        VALUES (?, ?, ?, ?, ?, ?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdsss", $nurseryName, $plantName, $targetFile, $price, $quantity, $location,$phone_number);

if ($stmt->execute()) {
    echo "<script>alert('Thank You For Your Support!');</script>";
    echo "<script>alert('We Will Call You Shortly!For Verification');</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
