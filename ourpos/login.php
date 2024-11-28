<?php
// Include database connection file
require 'db.php';

// Start session to manage login state
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input to prevent malicious code injection
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Passwords should not be sanitized, but validated later

    // Prepare SQL query to find user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Check if user exists and if password matches
    if ($user && password_verify($password, $user['password'])) {
        // Store user ID in session if login is successful
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php'); // Redirect to dashboard
        exit;
    } else {
        // Display error message if login fails
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('store.jpg') no-repeat center center/cover;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            align-items: center;
            justify-content: space-between;
        }


        .form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            opacity: 1;
        }

        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid black;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px; /* Matches button padding */
    margin: 10px 0; /* Matches button's spacing */
    border-radius: 4px; /* Matches button's rounded corners */
    border: 1px solid #ccc; /* Retains the border for inputs */
    font-size: 1.2rem; /* Matches button font size */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Add hover effect for consistency */
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none; /* Remove outline for focus */
    border-color: #4CAF50; /* Highlight border on focus */
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5); /* Subtle glow */
}

input[type="text"]:hover,
input[type="email"]:hover,
input[type="password"]:hover {
    background-color: #f9f9f9; /* Subtle background change on hover */
}


        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        button:active {
            transform: scale(0.98);
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Form Section -->
        <div class="form-container">
            <form method="POST">
                <h2>Login</h2>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Login</button>

                <div class="register-link">
                    <span>Don't have an account? <a href="register.php">Register</a></span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
