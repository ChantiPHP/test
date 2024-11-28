<?php
require 'db.php';

// Handle Dispose Action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['add_stock'])) {
        // Handle Add Stock
        $productId = intval($_POST['product_id']);
        $addStock = intval($_POST['add_stock']);

        // Ensure valid stock quantity input
        if ($addStock > 0) {
            // Prepare the SQL statement to update stock
            $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?");
            
            // Execute the statement with the values
            if ($stmt->execute([$addStock, $productId])) {
                echo "<script>alert('Stock updated successfully!');</script>";
            } else {
                echo "<script>alert('Failed to update stock. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid stock quantity.');</script>";
        }
    } elseif (isset($_POST['product_id']) && !isset($_POST['add_stock'])) {
        // Handle Dispose Action (if no stock to add, then dispose the product)
        $productId = intval($_POST['product_id']);

        // Prepare SQL to delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        if ($stmt->execute([$productId])) {
            // Redirect to refresh the product list
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Failed to delete the product. Please try again.');</script>";
        }
    }
}

// Fetch All Products
try {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = []; // Default to empty array if the query fails
    echo "<script>console.error('Error fetching products: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
            flex-direction: column;
            align-items: center;
            padding: 20px;
            min-height: 50vh;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .main-content {
            padding: 20px;
            width: 100%;
            max-width: 1250px;
        }

        table {
            width: 1250px;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            font-size: 0.9rem;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            color: #555;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .low-stock {
            background-color: #ffcccc;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .dispose-btn {
            background-color: #f44336;
            color: white;
        }

        .dispose-btn:hover {
            background-color: #e53935;
        }

        .add-stock-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .add-stock-btn:hover {
            background-color: #45a049;
        }

        .add-stock-btn:active {
            transform: scale(0.98);
        }

        .view-btn {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 10px;
            }

            .action-btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            table {
                font-size: 0.75rem;
            }

            th, td {
                padding: 8px;
            }

            .action-btn {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?> 

    <div class="main-content">
        <h2>All Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Expiry Date</th>
                    <th>Minimum Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): 
                        $currentDate = date('Y-m-d');
                        $expiryDate = $product['expiry_date'] ?? null;

                        // Determine product status
                        if ($expiryDate === null) {
                            $status = "No Expiry Date";
                        } elseif ($expiryDate < $currentDate) {
                            $status = "Expired";
                        } elseif ($expiryDate <= date('Y-m-d', strtotime('+30 days'))) {
                            $status = "Expiring Soon";
                        } else {
                            $status = "Not Expired";
                        }

                        // Determine button visibility
                        $actionButton = ($status === "Expired") 
                            ? "<form method='POST' action='' style='display:inline'>
                                   <input type='hidden' name='product_id' value='{$product['id']}'>
                                   <button type='submit' class='action-btn dispose-btn'>Dispose</button>
                               </form>"
                            : "<button class='action-btn view-btn' disabled>Not Applicable</button>";
                    ?>
                        <tr class="<?= $product['stock_quantity'] <= $product['minimum_stock'] ? 'low-stock' : '' ?>">
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= $product['price'] ?></td>
                            <td><?= $product['stock_quantity'] ?></td>
                            <td><?= $product['expiry_date'] ?? 'N/A' ?></td>
                            <td><?= $product['minimum_stock'] ?></td>
                            <td><?= $status ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline; margin-bottom: 10px;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="add_stock" placeholder="Add Stock" min="1" required style="width: 80px; padding: 5px; font-size: 0.9rem;">
                                    <button type="submit" class="action-btn add-stock-btn">Add</button>
                                </form>
                                <?= $actionButton ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
