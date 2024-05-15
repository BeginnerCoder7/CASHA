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

    $sql = "SELECT firstName, lastName, balance, profile_picture FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['firstName'] . " " . $row['lastName'];
        $balance = $row['balance'];
        $profilePicture = $row['profile_picture'] ? '' . $row['profile_picture'] : 'profile_pics/default.png';
    } else {
        header("Location: some_other_page.php");
        exit();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send </title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Gill Sans", sans-serif;
            box-sizing: border-box;
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
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height:950px;
            background-color: #90a4b0;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .dashboard1, .dashboard2 {
            width: calc(50% - 40px);
            max-width: 400px;
            background-color: #dad5cf;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0px 20px 20px 20px;
        }
        .dashboard1{
            margin: -60px 20px 20px 20px;
        }

        .profile-picture {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #90a4b0 ;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding:5px;
        }

        .profile-info {
            text-align: center;
        }

        .profile-info h2 {
            font-size: 24px;
            margin-top: 20px;
        }

        .current-balance {
            font-size: 14px;
            color:white;
        }

        .balance {
            font-size: 36px;
            margin-top: 5px;
            color:yellow;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 95%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button.sendmoney {
            background-color: #0e2038;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .balancebox{
            background-color: black;
            padding: 20px;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            width:300px;
        }

        button.sendmoney:hover {
            background-color: #dad5cf;
        }

        @media screen and (max-width: 768px) {
            .dashboard1, .dashboard2 {
                width: calc(100% - 40px);
            }

            .profile-picture img {
                width: 100px;
                height: 100px;
            }

            .profile-info h2 {
                font-size: 20px;
            }
        }
        .return-image {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
            width: 30px;
            cursor: pointer;
            margin:10px 10px ;
            background-color:#c1e8ff;
            padding:10px;
            border-radius:100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="dashboard1">
            <a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
                <div class="profile-picture">
                    <img src="<?php echo isset($profilePicture) ? $profilePicture : 'default.png'; ?>" alt="Profile Picture">
                    <h2><?php echo $name; ?></h2>
                    <div class="balancebox">
                            <p class="current-balance">Current Balance</p>
                            <h2 style=" font-size: 40px; letter-spacing:1px;"class="balance">₱ <?php echo $balance; ?></h2>
                    </div>
                </div>
            </div>

            <div class="dashboard2">
                <form id="sendMoneyForm" method="post" action="send_money.php">
                    <h2 style="text-align:center;"> Payment Form </h2>
                    <div class="form-group">
                        <label for="payment_title">Title of Payment</label>
                        <input type="text" id="payment_title" name="payment_title" required>
                    </div>
                
                    <div class="form-group">
                        <label for="recipient_username">Recipient's Username:</label>
                        <input type="text" id="recipient_username" name="recipient_username" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount to Send (₱):</label>
                        <input type="number" id="amount" name="amount" min="0" step="0.01" required>
                    </div>

                    <button class="sendmoney" type="submit" name="send_money" id="sendMoneyButton">Send Money</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>
    <script>
        document.getElementById('sendMoneyForm').addEventListener('submit', function (event) {
                var amount = parseFloat(document.getElementById('amount').value);
                if (amount > <?php echo $balance; ?>) {
                    alert('Insufficient balance!');
                    event.preventDefault();
                }
            });
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sendMoneyForm').addEventListener('submit', function (event) {
                var amount = parseFloat(document.getElementById('amount').value);
                var balance = <?php echo $balance; ?>;
                if (amount > balance) {
                    alert('Insufficient balance!');
                    event.preventDefault();
                } else if (document.getElementById('recipient_username').value.trim() === '<?php echo $_SESSION['username']; ?>') {
                    alert('Cannot send money to yourself!');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
