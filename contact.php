<?php
session_start();
if (!isset($_SESSION['username']) && basename($_SERVER["PHP_SELF"]) != 'login.html') {
    header("Location: login.html");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

if (isset($_SESSION['username'])) {
    $servername = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "audb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT firstName, lastName, email FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['firstName'] . " " . $row['lastName'];
        $email = $row['email'];
    }
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_name = $_POST['name'];
    $feedback_email = $_POST['email'];
    $feedback_message = $_POST['message'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO feedbacks (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $feedback_name, $feedback_email, $feedback_message);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully.')</script>";
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<script>alert('Error submitting feedback.')</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
    <style>
       body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Gill Sans", sans-serif;
            background-color: #f3e7d7;
            background-image: url("aueec.png");
        }
        .contact-form {
            width: 90%; 
            max-width: 500px;
            margin: 0 auto;
            text-align: center;

        }

        .input-field,
        .textarea-field {
            width: calc(100% - 30px);
            margin: 0 auto; 
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .textarea-field {
            resize: vertical;
            height: 150px;
        }

        .unique-button-class {
            width: calc(100% - 30px);
            padding: 10px;
            background-color: #13242a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }

        @media only screen and (max-width: 600px) {
            .input-field,
            .textarea-field,
            .unique-button-class {
                width: 100%;
            }
        }

        h3 {
            margin-top: 30px;
            color: black;
            text-align: center;
            font-size: 25px;
        }

        .social-media {
            text-align: center;
            margin-top: 50px; 
        }

        .social-media a {
            margin: 0 5px;
        }

        .social-media a img:hover {
            opacity: 0.8;
        }

        footer {
            background-color: black;
            color: white; 
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: -30px;
            width: 100%;
            font-size:10px;
            font-weight:bold;
        }

        .return-image {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
            width: 30px;
            cursor: pointer;
            margin:5px;
            background-color:#c1e8ff;
            padding:10px;
            border-radius:100px;
        }
    </style>
</head>
<body>
    <div class="contact-form">
        <a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
        <h3>Get in touch</h3>
        <form id="contactForm" action="submit_feedback.php" method="post" onsubmit="submitForm(event)">
            <input type="text" id="name" name="name" placeholder="Name" required class="input-field" value="<?php echo isset($name) ? $name : ''; ?>" readonly>
            <input type="email" id="email" name="email" placeholder="Email" required class="input-field" value="<?php echo isset($email) ? $email : ''; ?>" readonly>
            <textarea id="message" name="message" placeholder="Message" rows="4" required class="input-field textarea-field"></textarea>
            <button type="submit" class="unique-button-class">Submit</button>
        </form>
        <div class="social-media">
            <a href="https://m.me/yourmessengerlink" target="_blank"><img src="messenger.png" alt="Messenger icon" style="width:30px; margin-right:5px;"></a>
            <a href="https://www.facebook.com/yourfacebooklink" target="_blank"><img src="facebook.png" alt="Facebook icon" style="width:30px; margin-right:5px;"></a>
            <a href="https://twitter.com/yourtwitterlink" target="_blank"><img src="twitter.png" alt="Twitter icon" style="width:30px; margin-right:5px;"></a>
        </div>
    </div>

    <footer>
         S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>

    <script>
        document.querySelector('.return-image').addEventListener('click', function() {
            window.location.href = 'base.php';
        });

        function submitForm(event) {
        event.preventDefault();
        var formData = new FormData(document.getElementById('contactForm'));

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'submit_feedback.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Feedback submitted successfully.');
                window.location.href = 'base.php';
            } else {
                alert('Error submitting feedback.');
            }
        };
        xhr.send(formData);
        }
    </script>
</body>
</html>
