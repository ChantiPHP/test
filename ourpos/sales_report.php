<?php
require 'db.php';

// Fetch sales data
try {
    $stmt = $conn->prepare("SELECT sales.id, products.name AS product_name, sales.quantity_sold, sales.total_price, sales.date 
                            FROM sales
                            JOIN products ON sales.product_id = products.id
                            ORDER BY sales.date DESC");
    $stmt->execute();
    $sales = $stmt->fetchAll();
} catch (Exception $e) {
    $sales = [];
    echo "<script>console.error('Error fetching sales data: " . $e->getMessage() . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
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
            width: 100%;
            max-width: 1250px;
        }

        table {
            width: 1250px;
            border-collapse: collapse;
            text-align: center;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-sales {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #888;
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <h2>Sales Report</h2>

        <?php if (!empty($sales)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Product</th>
                        <th>Quantity Sold</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= $sale['id'] ?></td>
                            <td><?= htmlspecialchars($sale['product_name']) ?></td>
                            <td><?= $sale['quantity_sold'] ?></td>
                            <td>PHP <?= number_format($sale['total_price'], 2) ?></td>
                            <td><?= date('Y-m-d H:i:s', strtotime($sale['date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-sales">No sales have been made yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
