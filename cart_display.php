<?php
session_start();

if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
    }
    header("Location: payment.php");
    exit();
}

$delivery_charge = 150; 
$commission = 0.10; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0066cc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.2em;
        }
        .total-row td {
            background-color: #e6e6e6;
        }
        input[type="number"] {
            width: 60px;
            padding: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #004d99;
        }
        .highlight {
            background-color: #ffeb3b;
        }
    </style>
</head>
<body>
    <h1>Your Cart</h1>
    <form method="post" action="">
        <table>
            <tr>
                <th>Plant Image</th>
                <th>Nursery Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                    echo "<tr>";
                    echo "<td><img src='" . htmlspecialchars($item['photo']) . "' alt='" . htmlspecialchars($item['nursery_name']) . "'></td>";
                    echo "<td>" . htmlspecialchars($item['nursery_name']) . "</td>";
                    echo "<td><span>&#8377;</span>" . htmlspecialchars($item['price']) . "</td>";
                    echo "<td><input type='number' name='quantity[" . htmlspecialchars($id) . "]' value='" . htmlspecialchars($item['quantity']) . "' min='1'></td>";
                    echo "<td><span>&#8377;</span>" . htmlspecialchars($itemTotal) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
            }
            ?>
            <tr class="total-row highlight">
                <td colspan="4">Total</td>
                <td><span>&#8377;</span><?php echo htmlspecialchars($total); ?></td>
            </tr>
        </table>
        <div style="text-align: center;">
            <input type="submit" name="update" value="Proceed to Payment">
        </div>
    </form>

    <?php
    if (!empty($_SESSION['cart'])) {
        $delivery_total = $total + $delivery_charge + ($total * $commission);
    ?>
    <h1>Home Delivery Option</h1>
    <form method="post" action="paymenthome.php?home_delivery=true">
        <table>
            <tr class="total-row">
                <td colspan="4">Original Total</td>
                <td><span>&#8377;</span><?php echo htmlspecialchars($total); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="4">Delivery Charge (include all tax)</td>
                <td><span>&#8377;</span><?php echo htmlspecialchars($delivery_charge); ?></td>
            </tr>
            <tr class="total-row highlight">
                <td colspan="4">Total Amount</td>
                <td><span>&#8377;</span><?php echo htmlspecialchars($delivery_total); ?></td>
            </tr>
        </table>
        <div style="text-align: center;">
            <input type="submit" name="home_delivery" value="Proceed to pay with Home Delivery">
        </div>
    </form>
    <?php
    }
    ?>
</body>
</html>
