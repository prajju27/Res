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

        // Insert payment record with nursery name
        $nurseryName = $item['nursery_name'];
        $amount = $item['price'] * $item['quantity'];
        $screenshot = 'path_to_screenshot'; // Replace with actual screenshot path
        $insertPaymentSql = "INSERT INTO payments (amount, screenshot, nursery_name) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertPaymentSql);
        $insertStmt->bind_param('dss', $amount, $screenshot, $nurseryName);
        $insertStmt->execute();
    }

    unset($_SESSION['cart']);

    header("Location: generator_qr.php?amount=" . urlencode($totalAmount));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .payment-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }
        .payment-container h1 {
            margin-bottom: 25px;
        }
        .payment-table-container {
            overflow-x: auto;
            display: flex;
            justify-content: center;
        }
        .payment-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .payment-table th, .payment-table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .payment-table th {
            background-color: #f2f2f2;
        }
        .total-amount {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .pay-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .pay-button:hover {
            background-color: #45a049;
        }
        .crop-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Payment</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="payment-table-container">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Crop</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Nursery Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($item['photo'] ?? 'default.png'); ?>" alt="Crop Image" class="crop-image">
                        </td>
                        <td><?php echo number_format($item['price'] ?? 0, 2); ?></td>
                        <td><?php echo intval($item['quantity'] ?? 0); ?></td>
                        <td><?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2); ?></td>
                        <td><?php echo htmlspecialchars($item['nursery_name'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="total-amount">Total Amount: <?php echo number_format($totalAmount, 2); ?></p>
        <form method="post" action="">
            <button type="submit" name="pay" class="pay-button">Pay Now</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
