<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nurseryName = isset($_GET['nursery_name']) ? urldecode($_GET['nursery_name']) : '';

$sql = "SELECT id, plant_name, photo_path, price, quantity, location, phone_number FROM nursery_info WHERE nursery_name = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param('s', $nurseryName);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($nurseryName); ?> - Nursery Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            font-weight: 600;
        }
        th {
            background-color: #0066cc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 180px;
            height: 150px;
            border-radius: 8px;
        }
        .details {
            text-align: left;
        }
        .delete {
            color: red;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($nurseryName); ?> - Nursery Details</h1>
    <table>
        <tr>
            <th>Plant Image</th>
            <th>Plant Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Location</th>
            <th>Phone Number</th>
        </tr>
        <?php

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='" . htmlspecialchars($row['photo_path']) . "' alt='" . htmlspecialchars($row['plant_name']) . "'></td>";
                echo "<td class='details'>" . htmlspecialchars($row['plant_name']) . "</td>";
                echo "<td class='details'><span>&#8377;</span>" . htmlspecialchars($row['price']) . "</td>";
                echo "<td class='details'>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td class='details'>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td class='details'>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No plants available in this nursery.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
