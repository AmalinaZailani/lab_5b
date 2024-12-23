<?php
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the matric parameter is set in the URL (i.e., user wants to update a specific user)
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Fetch user data from the database
    $stmt = $conn->prepare("SELECT matric, name, role FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any user is found with the given matric
    if ($result->num_rows > 0) {
        // Fetch the row data (this is where the $row variable is set)
        $row = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit();
    }
} else {
    echo "Matric number not specified!";
    exit();
}

$successMessage = "";
$errorMessage = "";

// Handle form submission (if the form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update the user data in the database
    $stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
    $stmt->bind_param("sss", $name, $role, $matric);
    if ($stmt->execute()) {
        $successMessage = "User updated successfully!";
    } else {
        $errorMessage = "Error updating user!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Add JavaScript to show the popup -->
    <script type="text/javascript">
        <?php if ($successMessage): ?>
            alert("<?php echo $successMessage; ?>");
            window.location.href = "view_users.php";  // Redirect to the view_users page
        <?php elseif ($errorMessage): ?>
            alert("<?php echo $errorMessage; ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>

        <!-- Check if we have the row data -->
        <?php if (isset($row)): ?>
            <form method="POST" action="update.php?matric=<?php echo $row['matric']; ?>">
                <div class="form-group">
                    <label for="matric">Matric No</label>
                    <input type="text" id="matric" name="matric" class="form-control" value="<?php echo $row['matric']; ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="Lecturer" <?php echo ($row['role'] == 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                        <option value="Student" <?php echo ($row['role'] == 'Student') ? 'selected' : ''; ?>>Student</option>
                        <option value="Staff" <?php echo ($row['role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>

                <!-- Buttons in a row using Bootstrap's flex utility -->
                <div class="form-group d-inline-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="view_users.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        <?php else: ?>
            <p>No user data available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
