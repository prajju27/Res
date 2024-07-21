<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT nursery_name FROM approved_nurseries";
$result = $conn->query($sql);

$nursery_details = [];
$show_details = false;

if (isset($_GET['nursery_name'])) {
    $nursery_name = $_GET['nursery_name'];

    $sql_details = "SELECT nursery_name, photo, location, quantity, price FROM approved_nurseries WHERE nursery_name = ?";
    $stmt_details = $conn->prepare($sql_details);
    $stmt_details->bind_param('s', $nursery_name);
    $stmt_details->execute();
    $result_details = $stmt_details->get_result();

    if ($result_details->num_rows > 0) {
        $nursery_details = $result_details->fetch_assoc();
        $show_details = true; 
    } else {
        echo "No details found for this nursery.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Nurseries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .nursery-list {
            width: 30%;
            float: left;
        }
        .nursery-details {
            width: 70%;
            float: left;
            padding-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-size: 18px;
        }
        td {
            background-color: #fff;
        }
        .nursery-name {
            font-size: 16px;
            cursor: pointer;
            color: #007bff;
        }
        .nursery-name:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Approved Nurseries</h1>
        <div class="nursery-list">
            <table>
                <tr>
                    <th>Approved Nursery Names</th>
                </tr>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a class='nursery-name' href='nurseries.php?nursery_name=" . urlencode($row['nursery_name']) . "'>" . htmlspecialchars($row['nursery_name']) . "</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td>No approved nurseries available.</td></tr>";
                }
                ?>
            </table>
        </div>
        <?php if ($show_details && !empty($nursery_details)) : ?>
        <div class="nursery-details">
            <h2>Nursery Details</h2>
            <table>
                <tr>
                    <th>Photo</th>
                    <td><img src="<?php echo htmlspecialchars($nursery_details['photo']); ?>" alt="Nursery Photo" style="max-width: 300px;"></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td><?php echo htmlspecialchars($nursery_details['location']); ?></td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td><?php echo htmlspecialchars($nursery_details['quantity']); ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td><?php echo htmlspecialchars($nursery_details['price']); ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
