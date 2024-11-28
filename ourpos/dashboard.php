<?php
require 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch low stock products
$low_stock_stmt = $conn->prepare("SELECT * FROM products WHERE stock_quantity <= minimum_stock");
$low_stock_stmt->execute();
$low_stock_products = $low_stock_stmt->fetchAll();

// Fetch products nearing expiry (7 days before expiry)
$expiry_stmt = $conn->prepare("SELECT * FROM products WHERE expiry_date IS NOT NULL AND expiry_date <= CURDATE() + INTERVAL 7 DAY");
$expiry_stmt->execute();
$near_expiry_products = $expiry_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            min-height: 100vh;
        }


        /* Main Content */
        .content {
            width: 100%;
            max-width: 1500px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
        }


        header {
            color: #333;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        h4 {
            color: #FF6347;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            background-color: #f4f4f9;
            margin: 8px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li p {
            margin: 0;
            font-size: 1rem;
        }

        .stock-alert {
            font-weight: bold;
            color: #f44336;
        }

        .expiry-alert {
            font-weight: bold;
            color: #FF6347;
        }

        .no-data {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
        }


    </style>
</head>
<body>
    <?php include 'navbar.php'; ?> <!-- Include navbar.php here -->

    <div class="content">
        <header>
            <h2>Dashboard</h2>
        </header>

        <div class="container">
            <h3>Notifications</h3>

            <!-- Low Stock Notifications -->
            <?php if (!empty($low_stock_products)): ?>
                <h4>Low Stock Products:</h4>
                <ul>
                    <?php foreach ($low_stock_products as $product): ?>
                        <li>
                            <p><?= htmlspecialchars($product['name']) ?> (Stock: <?= $product['stock_quantity'] ?>)</p>
                            <span class="stock-alert">Low Stock!</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-data">No low stock products.</p>
            <?php endif; ?>

            <!-- Expiry Date Notifications -->
            <?php if (!empty($near_expiry_products)): ?>
                <h4>Near Expiry Products:</h4>
                <ul>
                    <?php foreach ($near_expiry_products as $product): ?>
                        <li>
                            <p><?= htmlspecialchars($product['name']) ?> (Expiry Date: <?= $product['expiry_date'] ?>)</p>
                            <span class="expiry-alert">Expires Soon!</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-data">No products nearing expiry.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
