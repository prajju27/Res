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
    
    $sql_delete = "DELETE FROM nursery_info WHERE nursery_name = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param('s', $nursery_name);
    if ($stmt->execute()) {
        echo "<script>alert('Nursery deleted successfully');</script>";
        echo "<script>window.location.href='afteradmin.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting nursery');</script>";
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'approve' && isset($_GET['nursery_name'])) {
    $nursery_name = $_GET['nursery_name'];

    $sql_select = "SELECT photo_path, location, quantity, price FROM nursery_info WHERE nursery_name = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param('s', $nursery_name);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $photo = $row['photo_path'];
        $location = $row['location'];
        $quantity = $row['quantity'];
        $price = $row['price'];

        $sql_insert = "INSERT INTO approved_nurseries (nursery_name, photo, location, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param('sssss', $nursery_name, $photo, $location, $quantity, $price);
        if ($stmt_insert->execute()) {

            $sql_delete = "DELETE FROM nursery_info WHERE nursery_name = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param('s', $nursery_name);
            if ($stmt_delete->execute()) {
                echo "<script>alert('Nursery approved successfully');</script>";
            
                echo "<script>window.location.href='afteradmin.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error approving nursery');</script>";
            }
        } else {
            echo "<script>alert('Error approving nursery');</script>";
        }
    } else {
        echo "<script>alert('Nursery not found in nursery_info');</script>";
    }
}

$sql = "SELECT DISTINCT nursery_name FROM nursery_info";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Available Nurseries</title>
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
        .delete-link, .approve-link {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-link {
            color: red;
            border: 1px solid red;
        }
        .approve-link {
            color: green;
            border: 1px solid green;
        }
        .delete-link:hover, .approve-link:hover {
            background-color: #ddd;
        }
        /* New CSS for header and navigation */
        .header {
            background-color: #4CAF50;
            padding: 20px; /* Increased padding */
            color: white;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 24px; /* Increased font size */
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 20px; /* Increased font size */
        }
        .header a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Header with navigation links -->
    <div class="header">
        <a href="first.html">Home</a>
        <a href="approved_nursery.php">Approved Crops</a>
        <a href="view1.php">View Transactions</a>
        <a href="view.php">Available Nurseries</a>
    </div>

    <div class="container">
        <h1>Our Available Nurseries</h1>
        <table>
            <tr>
                <th>Nursery Name</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='nursery_details.php?nursery_name=" . urlencode($row['nursery_name']) . "'>" . htmlspecialchars($row['nursery_name']) . "</a></td>";
                    echo "<td>";
                    echo "<a href='afteradmin.php?action=delete&nursery_name=" . urlencode($row['nursery_name']) . "' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this nursery?\")'>Delete</a>";
                    echo "&nbsp; &nbsp; ";
                    echo "<a href='afteradmin.php?action=approve&nursery_name=" . urlencode($row['nursery_name']) . "' class='approve-link' onclick='return confirm(\"Are you sure you want to approve this nursery?\")'>Approve</a>";
                    echo "</td>";
                    echo "</tr>";                    
                }
            } else {
                echo "<tr><td colspan='2'>No nurseries available.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
