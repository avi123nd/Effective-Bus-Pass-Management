

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Management System - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">EBPMS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="Register.html">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Profile.html">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Admin</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container mt-5">
        <h2>Login</h2>
        <form id="loginForm" action="login.php" method="POST">
            <div class="form-group">
                <label for="studentId">Student ID</label>
                <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter your student ID" required>
                <div class="invalid-feedback">Please enter your student ID.</div>
            </div>
            <div class="form-group position-relative">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-password" toggle="#password">&#128065;</span>
                <div class="invalid-feedback">Please enter your password.</div>
            </div>
            <button type="submit" class="btn btn-primary" name="submit" value="Login">Login</button>
            <div class="login-feedback" style="display:none;color:red;">Invalid student ID or password.</div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function () {
                let input = document.querySelector(this.getAttribute('toggle'));
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    this.innerHTML = '&#128064;'; // Eye slash icon
                } else {
                    input.setAttribute('type', 'password');
                    this.innerHTML = '&#128065;'; // Eye icon
                }
            });
        });
    </script>
</body>
</html>
<?php
session_start();
// Your existing login validation code here
// If login is successful:
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root"; // Adjust if your MySQL username is different
$password = ""; // Adjust if your MySQL password is different
$dbname = "registerdetails"; // Adjust to your correct database name

// Establish connection
$conn = new mysqli('localhost', 'root', '', 'registerdetails');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $studentId = $_POST['studentId'];
    $password = $_POST['password'];

    // Check if studentId and password are not empty
    if (!empty($studentId) && !empty($password)) {
        // SQL query to fetch user details based on studentId
        $stmt = $conn->prepare("SELECT * FROM registerdetails WHERE studentId = ?");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists and verify password
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPasswordFromDB = $row['password'];

            // Verify password hash
            if (password_verify($password, $hashedPasswordFromDB)) {
                  // Password correct, set session data
                  $_SESSION['userProfile'] = [
                    'studentId' => $studentId,
                    'name' => $row['name'], // Adjust as per your database structure
                    'email' => $row['email'], // Adjust as per your database structure
                    'phone' => $row['phone'], // Adjust as per your database structure
                    // Add other user details as needed
                ];

                // Redirect to home page or any secure page

                header('Location: home.html');
                exit;
            } else {
                // Password incorrect
                echo '<div class="alert alert-danger" role="alert">Invalid student ID or password.</div>';
            }
        } else {
            // User not found
            echo '<div class="alert alert-danger" role="alert">Invalid student ID or password.</div>';
        }

        // Close prepared statement
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">Please enter student ID and password.</div>';
    }
}

// Close database connection
$conn->close();
?>

