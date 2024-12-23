<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: view_users.php");
    } else {
        $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
        .error-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 20px;
            display: inline-block;
            background-color: #f8d7da;
            color: #721c24;
            font-family: Arial, sans-serif;
        }
        .error-box a {
            color: #004085;
            text-decoration: underline;
        }
        p {
            text-align: center;
            margin-top: 15px;
            color: #555;
        }
        p a {
            color: #007bff;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login Page</h2>
        <form method="POST" action="login.php">
            <label for="matric">Matric No</label>
            <input type="text" id="matric" name="matric" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <?php if ($error_message): ?>
            <div class="error-box">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
