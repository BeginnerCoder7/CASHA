<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $servername = "localhost"; 
        $username = "root"; 
        $password = ""; 
        $dbname = "audb"; 

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO feedbacks (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Feedback submitted successfully.')</script>";
        } else {
            echo "<script>alert('Error submitting feedback.')</script>";
        }

        $conn->close();
    }
?>
