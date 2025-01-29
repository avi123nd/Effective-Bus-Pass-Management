<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registerdetails";

// Create connection
$conn = new mysqli('loalhost', 'root', '', 'registerdetails');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data based on session or request parameter
session_start();
$studentId = $_SESSION['studentId'];

$sql = "SELECT name, email, phone, studentId, image FROM registerdetails WHERE studentId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$userData = array();
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($userData);
?>
