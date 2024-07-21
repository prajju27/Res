<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT nursery_name, plant_name, photo_path, price, quantity, location, phone_number FROM nursery_info";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Nurseries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px; 
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-size: 18px; 
        }
        td {
            background-color: #fff;
        }
        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nurseries Information</h1>
        <table>
            <tr>
                <th>Nursery Name</th>
                <th>Plant Name</th>
                <th>Photo</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Phone Number</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nursery_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['plant_name']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['photo_path']) . "' style='max-width: 100px; max-height: 100px;'></td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No nurseries found.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
