<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't been started already
}

require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>


<style>
    /* Sidebar Styling */
    .sidebar {
        height: 100%;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #4CAF50;
        padding-top: 20px;
        color: white;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Align items at the top */
        padding-bottom: 20px; /* Add bottom padding for better spacing */
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 1.8rem;
    }

    .sidebar a {
        display: block;
        padding: 15px;
        text-decoration: none;
        color: white;
        text-align: center;
        font-size: 1.1rem;
        transition: background-color 0.3s;
        margin-bottom: 5px; /* Add spacing between links */
    }

    .sidebar a:hover {
        background-color: #45a049;
    }

    /* Adjust the logout button to stay at the bottom */
    .logout-btn-container {
        margin-top: auto; /* This pushes the logout button to the bottom */
        padding: 10px;
    }

    .logout-btn {
        background-color: #f44336;
        padding: 10px 20px;
        color: white;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: center;
        font-size: 1.1rem;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .logout-btn:hover {
        background-color: #e53935;
    }

    /* Main Content Styling (adjust layout when sidebar is fixed) */
    .main-content {
        margin-left: 200px; /* Prevent content from being overlapped by sidebar */
        padding: 20px;
    }
</style>

<div class="sidebar">
    <h2>E&K System</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="products.php">View Products</a>
    <a href="sell_products.php">Sell Products</a>
    <a href="sales_report.php">Sales Report</a>
    <div class="logout-btn-container">
        <form action="logout.php" method="POST" >
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<!-- Main content (will be on the right side of the sidebar) -->
<div class="main-content">
    <!-- Your page content goes here -->
</div>
