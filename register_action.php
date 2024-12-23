<?php
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO users (matric, name, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $matric, $name, $role, $password);

    if ($stmt->execute()) {
        // Registration success
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Registration failed
        echo "<script>
                alert('Registration failed. Please try again.');
                window.history.back();
              </script>";
    }
}
?>
