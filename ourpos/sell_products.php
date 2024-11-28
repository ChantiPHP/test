<?php
require 'db.php';

// Handle Sale Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sell_product_id'])) {
    $productId = intval($_POST['sell_product_id']);
    $sellQuantity = intval($_POST['sell_quantity']);

    try {
        // Fetch the current stock quantity
        $stmt = $conn->prepare("SELECT stock_quantity, name, price FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if ($product && $product['stock_quantity'] >= $sellQuantity) {
            // Calculate the total sale price
            $totalPrice = $product['price'] * $sellQuantity;

            // Update the stock quantity
            $newStock = $product['stock_quantity'] - $sellQuantity;
            $updateStmt = $conn->prepare("UPDATE products SET stock_quantity = ? WHERE id = ?");
            $updateStmt->execute([$newStock, $productId]);

            // Insert the sale into the sales table
            $saleStmt = $conn->prepare("INSERT INTO sales (product_id, quantity_sold, total_price, date) VALUES (?, ?, ?, ?)");
            $saleStmt->execute([$productId, $sellQuantity, $totalPrice, date('Y-m-d H:i:s')]);

            // Generate the receipt
            $receipt = [
                'product_name' => $product['name'],
                'quantity_sold' => $sellQuantity,
                'total_price' => $totalPrice,
                'date' => date('Y-m-d H:i:s')
            ];

            echo "<script>alert('Sale successful! Stock updated.');</script>";
        } else {
            echo "<script>alert('Not enough stock available for this sale.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>console.error('Error during sale: " . $e->getMessage() . "');</script>";
    }
}

// Fetch All Products for Selling
try {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
    echo "<script>console.error('Error fetching products: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Products</title>
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

        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .main-content {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        form {
            display: flex;
            padding: 10px;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
        }
        select, input[type="number"], button {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .receipt {
            margin-top: 20px;
            background-color: #e2f0cb;
            padding: 10px;
            border: 1px solid #c1e5a8;
            border-radius: 4px;
        }
        .receipt p {
            margin: 5px 0;
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            nav ul {
                display: block;
            }
            nav ul li {
                display: block;
                margin: 10px 0;
            }
            nav ul li a {
                font-size: 14px;
            }
            button {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <h2>Sell Products</h2>
        <form method="POST" action="">
            <label for="product">Select Product:</label>
            <select name="sell_product_id" id="product" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> - PHP <?= $product['price'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Quantity to Sell:</label>
            <input type="number" name="sell_quantity" id="quantity" min="1" required>

            <button type="submit" class="action-btn dispose-btn">Sell</button>
        </form>

        <?php if (isset($receipt)): ?>
            <div class="receipt">
                <h3>Receipt</h3>
                <p>Product: <?= htmlspecialchars($receipt['product_name']) ?></p>
                <p>Quantity Sold: <?= $receipt['quantity_sold'] ?></p>
                <p>Total Price: PHP<?= number_format($receipt['total_price'], 2) ?></p>
                <p>Date: <?= $receipt['date'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
