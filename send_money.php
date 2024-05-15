<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_money'])) {
    $recipient_username = $_POST['recipient_username'];
    $amount = $_POST['amount'];

    // Database connection
    $servername = "localhost"; // Change this to your MySQL server address
    $username = "root"; // Change this to your MySQL username
    $password = ""; // Change this to your MySQL password, if set
    $dbname = "audb"; // Change this to your MySQL database name

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if recipient username exists and is not the same as the sender's username
    $sql = "SELECT * FROM users WHERE username = ? AND username != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $recipient_username, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Recipient username does not exist or is the same as the sender's username
        $conn->close();
        header("Location: invaliduser.html");
        exit();
    }

    // Fetch sender's balance
    $sql = "SELECT balance FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sender_balance = $row['balance'];

        // Check if sender has sufficient balance
        if ($amount > $sender_balance) {
            $conn->close();
            header("Location: home.php?error=insufficient_balance");
            exit();
        }
    }

    // Fetch recipient's balance
    $sql = "SELECT balance FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $recipient_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recipient_balance = $row['balance'];
    }

    // Update sender's balance
    $new_sender_balance = $sender_balance - $amount;
    $sql = "UPDATE users SET balance = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $new_sender_balance, $_SESSION['username']);
    $stmt->execute();

    // Update recipient's balance
    $new_recipient_balance = $recipient_balance + $amount;
    $sql = "UPDATE users SET balance = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $new_recipient_balance, $recipient_username);
    $stmt->execute();

    // Generate random transaction ID
    $transaction_id = generateRandomString(8);

    // Insert transaction record into transactions table
    $sql = "INSERT INTO transactions (transaction_id, sender_username, recipient_username, amount) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $transaction_id, $_SESSION['username'], $recipient_username, $amount);
    $stmt->execute();

    // Close connection
    $conn->close();

    // Redirect back to home page with success message
    header("Location: success.html");
    exit();
} else {
    // If the form is not submitted, redirect to some other page
    header("Location: some_other_page.php");
    exit();
}

// Function to generate random string
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
