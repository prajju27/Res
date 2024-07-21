<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $buyId = isset($_GET['buy_id']) ? intval($_GET['buy_id']) : 0;
    $nurseryName = isset($_GET['nursery_name']) ? urldecode($_GET['nursery_name']) : '';

    $sql = "SELECT id, nursery_name, photo, price, quantity FROM approved_nurseries WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param('i', $buyId);

    $stmt->execute();

    $result = $stmt->get_result();
    $plant = $result->fetch_assoc();

    if ($plant) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$buyId])) {
            $_SESSION['cart'][$buyId]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$buyId] = array(
                'nursery_name' => $plant['nursery_name'],
                'photo' => $plant['photo'],
                'price' => $plant['price'],
                'quantity' => 1,
            );
        }
    }

    $stmt->close();
    $conn->close();

    header("Location: cart_display.php");
    exit();
}
?>
