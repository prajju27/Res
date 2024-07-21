<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['nursery_name'])) {
    $nursery_name = $_GET['nursery_name'];
    
    $sql_delete = "DELETE FROM approved_nurseries WHERE nursery_name = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param('s', $nursery_name);
    if ($stmt->execute()) {
        echo "<script>alert('Nursery deleted successfully');</script>";
        echo "<script>window.location.href='approved_nurseries.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting nursery');</script>";
    }
}

$sql = "SELECT nursery_name FROM approved_nurseries";
$result = $conn->query($sql);

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
            border: 2px solid #007bff; 
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
        .link {
            text-decoration: none;
            color: #007bff;
            padding: 5px 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
        }
        .link:hover {
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
        }
        .delete-link {
            color: red;
            border: 1px solid red;
            padding: 5px 10px; /* Adjusted padding */
            border-radius: 5px;
        }
        .delete-link:hover {
            background-color: red;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Approved Nurseries</h1>
        <table>
            <tr>
                <th>Nursery Name</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='nursery_details1.php?nursery_name=" . urlencode($row['nursery_name']) . "' target='_blank' class='link'>" . htmlspecialchars($row['nursery_name']) . "</a></td>";
                    echo "<td><a href='approved_nursery.php?action=delete&nursery_name=" . urlencode($row['nursery_name']) . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this nursery?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No approved nurseries available.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
