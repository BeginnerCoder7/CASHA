<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: login.html");
        exit();
    }
    if (isset($_POST['logout'])) {
        session_destroy();

        header("Location: login.html");
        exit();
    }

    $servername = "localhost"; 
    $username = "root"; 
    $password = "";
    $dbname = "audb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT firstName, lastName, balance, profile_picture,email FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['firstName'] . " " . $row['lastName'];
        $balance = $row['balance'];
        $email = $row['email'];
        $profilePicture = $row['profile_picture'] ? '' . $row['profile_picture'] : 'profile_pics/default.png';
    } else {
        header("Location: some_other_page.php");
        exit();
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <style>
            body,html {
                margin: 0;
                padding: 0;
                font-family: "Gill Sans", sans-serif;
                background-color: #f3f4f6;
                background-image: url("aueec.png");
            }

            .dashboard {
                margin: 0 auto;
                padding: 20px;
                background-color: #F3E7D7;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 100%;
                position: relative;
            }

            .profile-picture {
                text-align: center;
                margin-bottom: 20px;
            }

            .profile-picture h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #011948;
                text-transform:uppercase;
            }

            .profile-picture img {
                width: 100%;
                max-width: 150px;
                height: auto;
                border-radius: 50%;
                border: 5px solid #011948;
                padding: 2px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .balance-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
            }

            .balance {
                font-size: 24px;
                font-family: "Arial", sans-serif;
                text-align: center;
                margin: 0;
            }

            .balancebox {
                background-color: #000818;
                border: 2px solid #72817A;
                border-radius: 8px;
                padding: 10px 20px;
                max-width: 400px;
                width: 100%;
                margin: 0 auto;
            }

            .balance-info {
                margin-bottom: 10px;
            }
            .balance-info h2{
                color:#F5DC82;
                font-size:30px;
            }

            .balance-info p {
                margin: 0;
                font-size:15px;
                color: white;
                margin-bottom: 5px;
                float:left;
            }

            .user-choices-container {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                margin: 0 auto;
                max-width: 100%;
            }

            .user-choices-container a {
                text-decoration: none;
            }

            .operations {
                background-color: #14252C;
                padding: 40px;
                margin: 0 auto;
                max-width: 100%;
                font-weight: bold;
            }
            .user-choices {
                width: calc(50% - 20px);
                max-width: 170px;
                height: 150px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 10px;
                background-color: #f3e7d7;
                margin: 8px;
                border-radius: 10px;
            }

            .image-container {
                text-align: center;
            }

            .image-container img {
                width: 60px;
                height: 60px;
            }

            .image-container p {
                margin-top: 10px;
                font-size: 20px;
            }

            .column {
                padding: 0 5px;
                flex: 0 0 auto;
            }

            .column a:link,
            .column a:visited {
                color: inherit;
                text-decoration: none;
            }

            h2 {
                text-align: center;
                font-size: 24px;
                margin-bottom: 20px;
            }

            .user-choices-container {
                display: flex;
                flex-wrap: wrap;
            }

            .user-choices {
                width: 70%;
                padding: 20px;
                box-sizing: border-box;
            }

            @media (max-width: 768px) {
                .user-choices {
                    width: 100%; 
                }
            }

            footer {
                background-color: black;
                color: white;
                text-align: center;
                padding: 10px 0;
                bottom: 0;
                width: 100%;
                font-size: 10px;
                font-weight: bold;
                bottom:0;
                z-index: 999; 
            }

            .dashboard img[src="3dot.png"] {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 30px;
                cursor: pointer;
            }

            .dropdown-menu {
                display: none;
                position: absolute;
                top: calc(0% + 10px);
                right: 40px;
                background-color: #C6E0D3;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                padding: 0;
                border-radius: 8px;
                width: 200px; 
                border:2px solid black;
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-menu ul {
                list-style: none;
                padding: 0;
                
            }

            .dropdown-menu li {
                margin-left:20px;
                font-size: 20px; 
                display: flex;
                align-items: center;
            }
            .dropdown-menu img {
            margin-right:10px;
            }
            .dropdown-menu li:hover {
                font-weight:bold;
                letter-spacing:2px;
                cursor: pointer; 
                text-decoration: none; 
            }

            .dropdown-menu li a:link,
            .dropdown-menu li a:visited {
                color: inherit; 
                text-decoration: none; 
            }
            .dropdown-menu button {
                background-color: transparent;
                border: none;
                color: #333;
                margin-left: 10px;
                cursor: pointer;
                font-size: 20px; 
                display: flex; 
                align-items: center;
            }
            .dropdown-menu button:hover {
                font-weight:bold;
                color:red;
                text-decoration:underline;
            }

            @media (max-width: 768px) {
                .user-choices {
                    width: calc(50% - 16px); 
                }
                
                .dashboard{
                    padding: 10px; 
                }
                .operations {
                    padding: 40px;
                }
                
                .balancebox {
                    width: 90%; 
                }
                
                .dropdown-menu {
                    top: calc(0% + 5px);
                }
 
            }

            .responsive-image {
            max-width: 100%; 
            height: auto;
            display: block;
            margin: 0 auto;
            }

            h3 {
            margin-top: 30px;
            color: black;
            text-align: center;
            font-size: 25px;
            }


            .contact-form {
                width: 100%;
                margin: 0 auto;
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .input-field,
            .textarea-field,
            .unique-button-class {
                width: calc(50% - 32px);
                margin: 0 auto;
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                font-size: 16px;
            }

            .textarea-field {
                resize: vertical;
            }

            .unique-button-class {
                background-color: #13242a;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 18px;
                transition: background-color 0.3s;
            }

            .unique-button-class:hover {
                background-color: #ff0000;
            }

            .social-media {
                text-align: center;
                margin-top: 20px;
            }

            .social-media a {
                display: inline-block;
                margin: 0 10px;
            }

            .social-media a img {
                width: 30px;
                vertical-align: middle;
                transition: opacity 0.3s;
            }

            .social-media a img:hover {
                opacity: 0.8;
            }

            @media only screen and (max-width: 600px) {
                .input-field,
                .textarea-field
                {

                    width: calc(85% - 32px); 
                }
                .unique-button-class {

                    width: calc(50% - 32px); 
                }
            }

        </style>
    </head>
    <body>
    
        <div class="dashboard">
            <img src="3dot.png" >
            <div class="profile-picture"><br><br><br><br>
                <img src="<?php echo isset($profilePicture) ? $profilePicture : 'default.png'; ?>" alt="Profile Picture">
                <h2><?php echo $name; ?></h2><br>
                <div class="balancebox">
                    <div class="balance-info">
                        <p class="current-balance">Current Balance</p><br><br>
                        <h2 class="balance">₱ <?php echo $balance; ?></h2>
                    </div>
                </div>
            </div>
        </div>
            <div class="dropdown-menu">
                <ul>
                    <li><img src="edit.png" style="width:40px; height:40px;" ><a href="edit.php">Edit </a></li><hr>
                    <li><img src="devs.png" style="width:40px; height:40px;" ><a href="team.php">Devs</a></li><hr>
                    <li><img src="about.png" style="width:40px; height:40px;" ><a href="about.php">About</a></li><hr>
                    <li><img src="support.png" style="width:40px; height:40px;" ><a href="contact.php">Support</a></li><hr>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <button type="submit" name="logout"><img src="signout.png" style="width:40px; height:40px;" >Sign out</button>
                    </form>
                </ul>
            </div>

        <div class="operations">
            <div class="user-choices-container">
                    <div class="user-choices">
                        <a href="home.php" style="color:black;">
                            <div class="row">
                                <div class="column">
                                    <div class="image-container">
                                        <img src="payment.png" alt="Send">
                                        <p>Transfer</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="user-choices">
                        <a href="deposit.php" style="color:black;">
                            <div class="row">
                                <div class="column">
                                    <div class="image-container">
                                        <img src="deposit.png" alt="Deposit">
                                        <p>Deposit</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="user-choices">
                        <a href="widthraw.php" style="color:black;">
                            <div class="row">
                                <div class="column">
                                    <div class="image-container">
                                        <img src="widraw.png" alt="Deposit">
                                        <p>Widthdraw</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="user-choices">
                        <a href="history.php" style="color:black;">
                            <div class="row">
                                <div class="column">
                                    <div class="image-container">
                                    <img src="transaction.png" alt="Deposit">
                                        <p>Transactions</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
            </div>           
        </div>

        <img src="poster2.png" class="responsive-image" alt="Poster 1">

        <div class="contact-form">
        <br>
        <h3>Tell Us!</h3><br>
                <form id="contactForm" action="submit_feedback.php" method="post" onsubmit="submitForm(event)">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Name" required class="input-field" value="<?php echo isset($name) ? $name : ''; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email" required class="input-field" value="<?php echo isset($email) ? $email : ''; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="Message" rows="4" required class="input-field textarea-field"></textarea>
                    </div>
                    <button type="submit" class="unique-button-class">Submit</button>
                </form>
                <div class="social-media">
                    <a href="https://m.me/yourmessengerlink" target="_blank" ><img src="messenger.png" alt="Messenger icon" style="width:30px; margin-right:5px;"></a>
                    <a href="https://www.facebook.com/yourfacebooklink" target="_blank"><img src="facebook.png" alt="Facebook icon" style="width:30px; margin-right:5px;"></a>
                    <a href="https://twitter.com/yourtwitterlink" target="_blank"><img src="twitter.png" alt="Twitter icon" style="width:30px; margin-right:5px;"></a>
                </div><br>
            </div>
            <footer>
        S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var dots = document.querySelector('.dashboard img[src="3dot.png"]');
    var dropdown = document.querySelector('.dropdown-menu');

    dots.addEventListener('click', function(event) {
        event.stopPropagation(); 
        dropdown.classList.toggle('show');
    });

    window.addEventListener('click', function(event) {
        if (!event.target.closest('.dashboard') && !event.target.matches('.dashboard img[src="3dot.png"]')) {
            dropdown.classList.remove('show');
        }
    });
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
