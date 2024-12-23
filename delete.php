<?php
$conn = new mysqli("localhost", "root", "", "Lab_5b");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete operation
$message = "";
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        $message = "User deleted successfully!";
        header("Location: view_users.php");
        exit;
    } else {
        $message = "Error deleting user: " . $conn->error;
    }
} else {
    $message = "No user specified.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>Delete User</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="view_users.php">Go Back to Users List</a>
    </div>
</body>
</html>
