<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "viewcrops_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch nursery details
$sql = "SELECT photo_path, location, nursery_name FROM nursery_info";
$result = $conn->query($sql);

$nurseries = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nurseries[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nursery Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            text-align: center;
        }
        .nursery-item {
            display: none;
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.5s, opacity 0.5s;
        }
        .nursery-item img {
            width: 100%;
            max-width: 600px;
            height: auto;
            border-radius: 10px;
        }
        .nursery-item h2 {
            margin: 10px 0;
            font-size: 24px;
        }
        .nursery-item p {
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (empty($nurseries)): ?>
            <p>No nursery details found.</p>
        <?php else: ?>
            <?php foreach ($nurseries as $nursery): ?>
                <div class="nursery-item">
                    <img src="<?php echo htmlspecialchars($nursery['photo_path']); ?>" alt="<?php echo htmlspecialchars($nursery['nursery_name']); ?>">
                    <h2><?php echo htmlspecialchars($nursery['nursery_name']); ?></h2>
                    <p><?php echo htmlspecialchars($nursery['location']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script>
        const items = document.querySelectorAll('.nursery-item');
        let currentItem = 0;

        function showItem() {
            items.forEach((item, index) => {
                item.style.display = 'none';
                item.style.opacity = '0';
                item.style.transform = 'translateY(50px)';
            });

            items[currentItem].style.display = 'block';
            setTimeout(() => {
                items[currentItem].style.opacity = '1';
                items[currentItem].style.transform = 'translateY(0)';
            }, 50);

            currentItem = (currentItem + 1) % items.length;
        }

        if (items.length > 0) {
            showItem();
            setInterval(showItem, 3000); // Change item every 3 seconds
        }
    </script>
</body>
</html>
