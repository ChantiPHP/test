<?php
require 'db.php';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get product data from form
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $minimum_stock = $_POST['minimum_stock'];

    // Check if an expiry date was provided; set to NULL if not
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;

    // Prepare the SQL statement to insert data into the products table
    $stmt = $conn->prepare(
        "INSERT INTO products (name, category, price, stock_quantity, expiry_date, minimum_stock) VALUES (?, ?, ?, ?, ?, ?)"
    );
    
    // Execute the prepared statement
    if ($stmt->execute([$name, $category, $price, $stock_quantity, $expiry_date, $minimum_stock])) {
        echo "<p>Product added successfully!</p>";
    } else {
        echo "<p>Failed to add product. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        /* Main Content Styling */
        .main-content {
            padding: 20px;
            width: 100%;
            max-width: 800px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            color: #4CAF50;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Media Query for smaller screens */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }

            form {
                padding: 15px;
                gap: 15px;
            }

            input[type="text"],
            input[type="number"],
            input[type="date"] {
                padding: 8px;
            }

            button {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?> 

    <div class="main-content">
        <h2>Add New Product</h2>

        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" name="stock_quantity" id="stock_quantity" required>

            <label for="expiry_date">Expiry Date (optional for non-medicine):</label>
            <input type="date" name="expiry_date" id="expiry_date">

            <label for="minimum_stock">Minimum Stock (for notifications):</label>
            <input type="number" name="minimum_stock" id="minimum_stock" value="10" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
