<?php
// Start the session at the very top
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['user'])) {
    echo "<script>
            alert('Please login first!');
            window.location.href = 'login.php';
          </script>";
    exit;
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use a prepared statement to fetch the users
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .action-links a {
            margin-right: 10px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Log Out Button with confirmation -->
    <form method="POST" action="logout.php" class="logout-btn" id="logoutForm">
        <button type="button" class="btn btn-danger" onclick="confirmLogout()">Log Out</button>
    </form>

    <div class="container">
        <h2>View Users</h2>
        <?php
        // Check if any users are returned from the database
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Matric</th><th>Name</th><th>Role</th><th>Actions</th></tr>";

            // Loop through each user and display in table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['matric']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['role']}</td>";
                echo "<td class='action-links'>
                        <a href='update.php?matric={$row['matric']}'>Update</a>
                        <a href='delete.php?matric={$row['matric']}' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
        
        // Close the database connection
        $conn->close();
        ?>
    </div>

    <script>
        function confirmLogout() {
            // Show a confirmation dialog before logging out
            if (confirm("Are you sure you want to log out?")) {
                // If confirmed, submit the form to log out
                document.getElementById('logoutForm').submit();
            }
        }
    </script>
</body>
</html>
