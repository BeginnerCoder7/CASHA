<?php
session_start();

// Establish connection to your MySQL database
$servername = "localhost"; // Change this to your MySQL server address
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password, if set
$dbname = "audb"; // Change this to your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if username and password are provided
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("Error: Username or password is missing.");
}

// Get username and password from the login form
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Query to fetch user data from the database
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username and password match, set session variable
    $_SESSION['username'] = $username;
    // Redirect to dashboard or welcome page
    // Instead of directly redirecting, echo a response
    echo "success";
} else {
    // Username or password is incorrect
    // Instead of directly echoing, return an error response
    echo "error";
}

$conn->close();
?>
