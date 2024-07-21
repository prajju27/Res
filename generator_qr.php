<?php
session_start();

if (!isset($_GET['amount'])) {
    die("Amount not specified.");
}

$amount = htmlspecialchars($_GET['amount']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Verification</title>
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
        .qr-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 500px;
        }
        .qr-container img {
            margin-bottom: 20px;
            width: 80%;
            height: auto;
        }
        .confirm-button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .confirm-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <h1>Scan to Pay</h1>
        <img src="q1.jpeg" alt="QR Code">
        <p>Amount: <?php echo number_format($amount, 2); ?></p>
        <form method="post" action="afterpayment.php" enctype="multipart/form-data">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">
            <input type="file" name="payment_screenshot" accept="image/*" required>
            <button type="submit" name="confirm" class="confirm-button">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
