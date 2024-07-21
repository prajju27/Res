<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT nursery_name FROM nursery_info";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Available Nurseries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: left;
            position: relative;
        }
        h1 {
            background-color: aquamarine;
            padding-bottom: 45px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            border: 2px solid #ddd;
            background-image: url(cu1.jpeg);
            background-position: 100%;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 20px;
        }
        th {
            background-color: #0066cc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: black;
        }
        a:hover {
            text-decoration: underline;
        }
        .upload {
            display: inline-block;
            font-size: 20px;
            color: #fff;
            background-color: #0066cc;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 10px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .upload span {
            margin-right: 8px;
        }
        .upload img {
            vertical-align: middle;
        }
        .plant-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin: 5px;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Nurseries</h1>
        <a class="upload" href="AfterRegister.html">
            <span><img src="up.jpeg" alt="Upload Icon" width="20" height="20"></span> Upload crop
        </a>
        <table>
            <tr>
                <th>Nursery Name</th>
                <th>Crop Photos</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nurseryName = htmlspecialchars($row['nursery_name']);
                    $photoSql = "SELECT photo_path FROM nursery_info WHERE nursery_name = ?";
                    $photoStmt = $conn->prepare($photoSql);
                    $photoStmt->bind_param('s', $nurseryName);
                    $photoStmt->execute();
                    $photoResult = $photoStmt->get_result();
                    echo "<tr><td><a href='#?nursery_name=" . urlencode($nurseryName) . "'>" . $nurseryName . "</a></td><td>";
                    while ($photoRow = $photoResult->fetch_assoc()) {
                        echo "<img class='plant-image' src='" . htmlspecialchars($photoRow['photo_path']) . "' alt='Crop Photo'>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No nurseries available.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
