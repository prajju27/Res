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

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$totalAmount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price'], $item['quantity'])) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
    }
}

if (isset($_POST['pay'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        $quantityBought = $item['quantity'];
        
        $sql = "UPDATE approved_nurseries SET quantity = quantity - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $quantityBought, $id);
        $stmt->execute();
    }

    unset($_SESSION['cart']);

    header("Location: generator_qr.php?amount=" . urlencode($totalAmount));
    exit();
}

if (isset($_POST['home_delivery'])) {
    $delivery_charge = 150; 
    $commission = 0.15;
    $delivery_total = $totalAmount + $delivery_charge + ($totalAmount * $commission);

    foreach ($_SESSION['cart'] as $id => $item) {
        $quantityBought = $item['quantity'];
        
        $sql = "UPDATE approved_nurseries SET quantity = quantity - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $quantityBought, $id);
        $stmt->execute();
    }

    unset($_SESSION['cart']);

    header("Location: generator_qr.php?amount=" . urlencode($delivery_total));
    exit();
}
?>
