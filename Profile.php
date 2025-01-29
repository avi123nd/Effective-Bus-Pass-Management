<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['userProfile'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Retrieve user data from session
$userProfile = $_SESSION['userProfile'];
$name = isset($userProfile['name']) ? $userProfile['name'] : '';
$email = isset($userProfile['email']) ? $userProfile['email'] : '';
$phone = isset($userProfile['phone']) ? $userProfile['phone'] : '';
$studentId = isset($userProfile['studentId']) ? $userProfile['studentId'] : '';
$image = isset($userProfile['image']) ? $userProfile['image'] : ''; // Initialize $image variable
$imagePath = !empty($image) ? 'C:\xampp\htdocs\images' . $image : ''; // Adjust accordingly, provide a default image path if none exists
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style to fit map container */
        #map-container {
            height: 400px;
            width: 100%;
            margin-top: 20px; /* Adjust margin as needed */
        }
        .card-body{
            display:flex;
            gap: 2em;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">EBPMS</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>User Profile</h2>
        <div id="profileInfo" class="card">
            <div class="card-body">
                <?php if (!empty($image)): ?>
                    <img src="<?php echo $imagePath; ?>" class="img-fluid rounded-circle mb-3" alt="Profile Image" style="width: 150px; height: 150px;">
                <?php else: ?>
                    <img src="a1.jpg" class="img-fluid rounded-circle mb-3" alt="Default Profile Image" style="width: 150px; height: 150px;">
                <?php endif; ?>
                <div><p class="card-text"><strong>Name:</strong> <?php echo $name; ?></p>
                <p class="card-text"><strong>Email:</strong> <?php echo $email; ?></p>
                <p class="card-text"><strong>Phone:</strong> <?php echo $phone; ?></p>
                <p class="card-text"><strong>Student ID:</strong> <?php echo $studentId; ?></p></div>
                <!-- Barcode image with link -->
                <div class="mt-3">
                    <a href="profile.php">
                        <img src="barcode_123456789012.png" width="230px" height="69px" alt="Scan to go to profile">
                    </a>
                </div>
            </div>
        </div>

        <!-- Map section -->
         <div style="font-size: 30px;">Map</div>
        <div id="map-container">
            <iframe
              width="100%"
              height="400"
              frameborder="0"
              style="border:0"
              src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15276.742276716558!2d78.5846648966214!3d17.416470794703005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e3!4m5!1s0x3bcb8b8f91e8cc9f%3A0x14d5c5227c78f8b0!2sUppal%2C%20Hyderabad%2C%20Telangana%2C%20India!3m2!1d17.4020649!2d78.5597855!4m5!1s0x3bcb8c4bf28a72a7%3A0x3a106881d44668f2!2sACE%20Engineering%20College%2C%20Ankushapur%2C%20Telangana%20501301!3m2!1d17.4234346!2d78.5926749!5e0!3m2!1sen!2sin!4v1626093222090!5m2!1sen!2sin"
             allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
