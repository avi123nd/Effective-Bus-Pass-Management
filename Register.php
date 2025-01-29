<?php
session_start(); // Start the session
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root"; // Adjust if your MySQL username is different
$password = ""; // Adjust if your MySQL password is different
$dbname = "registerdetails"; // Adjust to your correct database name

// Create connection
$conn = new mysqli('localhost','root', '', 'registerdetails');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pic_uploaded = 0;

// Debug: Display submitted form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo '<pre>';
    var_dump($_POST);
    var_dump($_FILES);
    echo '</pre>';

    // File upload error handling
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk.";
                break;
            default:
                echo "Unknown upload error.";
                break;
        }
        exit;
    }

    // File upload handling
    $image = time() . "_" . basename($_FILES["image"]["name"]);
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
    $target_file = $target_dir . $image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file type and size
    $uploadOk = 1;
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
        echo "<script>alert('Please upload a photo with extension .jpg/.jpeg/.png'); window.history.back();</script>";
        $uploadOk = 0;
    } elseif ($_FILES["image"]["size"] > 2000000) { // 2MB limit (2000000 bytes)
        echo "<script>alert('Your photo exceeds the size limit of 2MB'); window.history.back();</script>";
        $uploadOk = 0;
    }

    // Check if file upload is OK
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<script>alert('The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.');</script>";
            $pic_uploaded = 1;
             // Store image filename in session variable
             $_SESSION['userProfile']['image'] = $images;
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.'); window.history.back();</script>";
            exit;
        }
    }

    // If image uploaded successfully, proceed with form data insertion
    if ($pic_uploaded == 1) {
        // Retrieve other form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $studentId = $_POST["studentId"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        // Check if passwords match
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
            exit;
        }

        // Hash password for security
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into database using prepared statement
        $stmt = $conn->prepare("INSERT INTO registerdetails (image, name, email, phone, studentId, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $image, $name, $email, $phone, $studentId, $passwordHash);

        if ($stmt->execute()) {
             // Store user data in session
            $_SESSION['studentId'] = $studentId;
            // Close statement and connection
            $stmt->close();
            $conn->close();
             
            // Redirect to login page
            header("Location: login.php");
            exit;
        } else {
            echo "<script>alert('Error: Could not register. Please try again.'); window.history.back();</script>";
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Pass Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
                    <a class="nav-link" href="#">Register</a>
                </li>
                <li class="nav-item">
                   <!-- <a class="nav-link" href="login.php">Login</a>-->
                </li>
            </ul>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="container mt-5">
        <h2>Register for Bus Pass</h2>
        <form id="registrationForm" action="Register.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
                <label for="image">Upload photo</label>
                <input type="file" class="form-control" id="image" name="image" placeholder="Upload Image" required>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                <div class="invalid-feedback">Please enter a valid name (letters only).</div>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
                <div class="email-feedback" style="display:none;color:rgb( 119 , 119 , 119);">Email already exists.</div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                <div class="invalid-feedback">Please enter a valid phone number.</div>
            </div>
            <div class="form-group">
                <label for="studentId">Student ID</label>
                <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter your student ID" required>
                <div class="invalid-feedback">Please enter a valid student ID (capital letters and numbers only).</div>
            </div>
            <div class="form-group position-relative">
    <label for="password">Set Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        <span class="input-group-append">
            <span class="toggle-password input-group-text" toggle="#password"><i class="fas fa-eye" aria-hidden="true"></i></span>
        </span>
    </div>
    <div class="invalid-feedback">Please enter a strong password.</div>
    <small id="passwordHelpBlock" class="form-text text-muted">
        Your password should be at least 8 characters long, contain upper and lower case letters, numbers, and special characters.
    </small>
</div>
<div class="form-group position-relative">
    <label for="confirmPassword">Re-enter Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Reenter your password" required>
        <span class="input-group-append">
            <span class="toggle-password input-group-text" toggle="#confirmPassword"><i class="fas fa-eye" aria-hidden="true"></i></span>
        </span>
    </div>
    <div class="invalid-feedback">Passwords do not match.</div>
</div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
     </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let emailList = [];

            document.getElementById('registrationForm').addEventListener('submit', function (e) {
                // Get form values
                let name = document.getElementById('name').value.trim();
                let email = document.getElementById('email').value.trim();
                let phone = document.getElementById('phone').value.trim();
                let studentId = document.getElementById('studentId').value.trim();
                let password = document.getElementById('password').value.trim();
                let confirmPassword = document.getElementById('confirmPassword').value.trim();

                // Name validation
                let namePattern = /^[A-Za-z\s]+$/;
                if (!namePattern.test(name)) {
                    document.getElementById('name').classList.add('is-invalid');
                    return false;
                } else {
                    document.getElementById('name').classList.remove('is-invalid');
                }

                // Email validation
                if (!email) {
                    document.getElementById('email').classList.add('is-invalid');
                    return false;
                } else if (emailList.includes(email)) {
                    document.getElementById('email').classList.add('is-invalid');
                    document.querySelector('.email-feedback').style.display = 'block';
                    return false;
                } else {
                    document.getElementById('email').classList.remove('is-invalid');
                    document.querySelector('.email-feedback').style.display = 'none';
                }

                // Phone validation
                let phonePattern = /^\d{10}$/;
                if (!phonePattern.test(phone)) {
                    document.getElementById('phone').classList.add('is-invalid');
                    return false;
                } else {
                    document.getElementById('phone').classList.remove('is-invalid');
                }

                // Student ID validation
                let studentIdPattern = /^[A-Z0-9]+$/;
                if (!studentIdPattern.test(studentId)) {
                    document.getElementById('studentId').classList.add('is-invalid');
                    return false;
                } else {
                    document.getElementById('studentId').classList.remove('is-invalid');
                }

                // Password validation
                let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
                if (!passwordPattern.test(password)) {
                    document.getElementById('password').classList.add('is-invalid');
                    return false;
                } else {
                    document.getElementById('password').classList.remove('is-invalid');
                }

                // Confirm Password validation
                if (password !== confirmPassword) {
                    document.getElementById('confirmPassword').classList.add('is-invalid');
                    return false;
                } else {
                    document.getElementById('confirmPassword').classList.remove('is-invalid');
                }

                // Add email to the list
                emailList.push(email);

                return true; // Allow form submission to proceed
            });

            // Toggle password visibility
            // Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(item => {
    item.addEventListener('click', function () {
        let input = document.querySelector(this.getAttribute('toggle'));
        if (input.getAttribute('type') === 'password') {
            input.setAttribute('type', 'text');
            this.querySelector('i').classList.add('fa-eye-slash');
            this.querySelector('i').classList.remove('fa-eye');
        } else {
            input.setAttribute('type', 'password');
            this.querySelector('i').classList.add('fa-eye');
            this.querySelector('i').classList.remove('fa-eye-slash');
        }
    });
});
        });
    </script>
</body>
</html>