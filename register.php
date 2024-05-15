<?php
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

// Check if all required fields are present in the POST data
$requiredFields = ['firstName', 'lastName', 'email', 'phoneNumber', 'birthdate', 'address', 'username', 'password', 'gender'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        die("Error: Required field '$field' is missing or empty.");
    }
}

// Generate bank-related information
$bankName = generateBankName();
$expirationDate = generateExpirationDate();
$cvv = generateCVV();
$accountNumber = generateAccountNumber();
$bankbalance = generateBankBalance();

// Get form data and sanitize
$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
$middleName = isset($_POST['middleName']) ? mysqli_real_escape_string($conn, $_POST['middleName']) : '';
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
$birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']); // Use the password as-is
$gender = mysqli_real_escape_string($conn, $_POST['gender']); // Gender from the form

// Set default profile picture filename
$defaultProfilePicture = "default.png";

// Prepare and bind SQL statement
$sql = "INSERT INTO users (firstName, lastName, middleName, email, phoneNumber, birthdate, address, username, password, gender, bankName, expirationDate, cvv, accountNumber, bankbalance, profile_picture)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssssss", $firstName, $lastName, $middleName, $email, $phoneNumber, $birthdate, $address, $username, $password, $gender, $bankName, $expirationDate, $cvv, $accountNumber, $bankbalance, $defaultProfilePicture);

// Execute the statement
if ($stmt->execute()) {
    // Registration successful, display success message and redirect to login page
    echo "<script>alert('Successfully registered!')</script>";
    header("Location: login.html");
    exit();
} else {
    // Registration failed
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();

// Function to generate a random bank name
function generateBankName() {
    // You can implement logic to generate a random bank name here
    // For example, you can have an array of bank names and randomly select one
    $bankNames = ['Bank A', 'Bank B', 'Bank C'];
    return $bankNames[array_rand($bankNames)];
}

// Function to generate a random expiration date
function generateExpirationDate() {
    // You can implement logic to generate an expiration date here
    // For example, you can generate a date a certain number of years in the future
    $yearsToAdd = 5; // Example: expiration date is set to 5 years from now
    return date('Y-m-d', strtotime("+$yearsToAdd years"));
}

// Function to generate a random CVV
function generateCVV() {
    // You can implement logic to generate a random CVV here
    // For example, you can generate a 3 or 4-digit random number
    return str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Function to generate a random account number
function generateAccountNumber() {
    // You can implement logic to generate a random account number here
    // For example, you can generate a random 10-digit number
    return str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
}

// Function to generate a random bank balance
function generateBankBalance() {
    // You can implement logic to generate a random bank balance here
    // For example, you can generate a random amount between 0 and 999999.99
    return number_format(mt_rand(0, 99999999) / 100, 2);
}
?>
