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

        $sql_user = "SELECT firstName, lastName, email, balance FROM users WHERE username = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $_SESSION['username']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            $name = $row_user['firstName'] . " " . $row_user['lastName'];
            $email = $row_user['email'];
            $balance = $row_user['balance']; 
        }

        $sql_bank = "SELECT bankName, accountNumber, bankbalance, expirationDate FROM users WHERE username = ?";
        $stmt_bank = $conn->prepare($sql_bank);
        $stmt_bank->bind_param("s", $_SESSION['username']);
        $stmt_bank->execute();
        $result_bank = $stmt_bank->get_result();

        if ($result_bank->num_rows > 0) {
            $row_bank = $result_bank->fetch_assoc();
            $bankName = $row_bank['bankName'];
            $accountNumber = $row_bank['accountNumber'];
            $bankbalance = $row_bank['bankbalance'];
            $expirationDate = $row_bank['expirationDate'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdrawalAmount'])) {
            $withdrawalAmount = $_POST['withdrawalAmount'];

            if ($balance >= $withdrawalAmount) {
                $sql_update = "UPDATE users SET balance = balance - ?, bankbalance = bankbalance + ? WHERE username = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("dds", $withdrawalAmount, $withdrawalAmount, $_SESSION['username']);

                if ($stmt_update->execute()) {
                    $transaction_id = generateRandomString(8);

                    $sql_transaction = "INSERT INTO transactions (transaction_id, sender_username, recipient_username, amount) VALUES (?, ?, ?, ?)";
                    $stmt_transaction = $conn->prepare($sql_transaction);
                    $stmt_transaction->bind_param("sssd", $transaction_id, $_SESSION['username'], $_SESSION['username'], $withdrawalAmount);
                    $stmt_transaction->execute();

                    echo '<script>alert("Successfully withdrawn from your account");</script>';

                    $bankbalance += $withdrawalAmount;
                    $balance -= $withdrawalAmount;
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo '<script>alert("Insufficient balance");</script>';
            }
        }

        $conn->close();
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Money</title>
    <style>
        body,html {
            margin: 0;
            padding: 0;
            font-family: "Gill Sans", sans-serif;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            background-color: #4a686a;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;

        }

        .bank-details {
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .bank1 {
            position: relative;
            background-color: #feeaa5;
            border-radius: 10px;
            height: 180px;
            border: 1px solid black;
            transition: all 0.3s ease;
        }

        .bank1:hover {
            background-color: #ffd57a;
            border: 1px solid black;
        }

        .expiration {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 15px;
            color: black;
            font-weight: bold;
        }

        .balance {
            font-size: 15px;
            margin-top: -5px;
        }

        .addbank {
            margin-top: 20px;
            padding: 20px;
            background-color: #e6f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        .addbank h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .addbank button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .addbank button:hover {
            background-color: #45a049;
        }

        .return-image {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
            width: 30px;
            cursor: pointer;
            margin: 15px;
            background-color: #c1e8ff;
            padding: 10px;
            border-radius: 100px;
        }

        @media screen and (max-width: 768px) {
            .container {
                max-width: 90%;
            }

            .expiration {
                font-size: 10px;
                top: 10px;
                right: 10px;
            }

            .balance {
                font-size: 10px;
                margin-top: -2px;
            }
            .container{
                margin:20px;
            }
        }
        footer {
                    background-color: black;
                    position:absolute;
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
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 20% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
    <div class="container">
        <h2 style="text-align:center; color:#f9f8f3;">Select Bank</h2>
        <div class="bank1">
            <div class="bank-details" onclick="showWithdrawalForm()">
                <?php if (isset($bankName) && isset($accountNumber) && isset($bankbalance) && isset($expirationDate)): ?>
                    <p><?php echo $bankName; ?></p>
                    <p style="font-size:20px; margin:-10px 0px 10px 0px; letter-spacing:2px;"><?php echo $accountNumber; ?></p>
                    <p style="font-size:30px; margin:40px 0px 0px 0px;">₱<?php echo $bankbalance; ?></p>
                    <p class="balance">Current Balance</p>
                    <p class="expiration">Expiration Date: <?php echo $expirationDate; ?></p>
                <?php else: ?>
                    <p>No bank account details found.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="withdrawalModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeWithdrawalForm()">&times;</span>
                <h2>Enter Withdrawal Amount</h2>
                <form method="post" action="widthraw.php">
                    <input type="text" name="withdrawalAmount" id="withdrawalAmount" placeholder="Enter amount">
                    <button type="submit">OK</button>
                </form>
            </div>
        </div>

        <div class="addbank">
            <h2>Add Bank Account</h2>
            <button onclick="location.href='#';">+</button>
        </div>
    </div>
    <footer>
    S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>
    <script>
        function showWithdrawalForm() {
            var modal = document.getElementById('withdrawalModal');
            modal.style.display = 'block';
        }

        function closeWithdrawalForm() {
            var modal = document.getElementById('withdrawalModal');
            modal.style.display = 'none';
        }

        function withdrawFromAccount() {
            var amount = document.getElementById('withdrawalAmount').value;
            if (amount !== '') {
                alert('Successfully withdrawn from your account');
                closeWithdrawalForm();
                
            } else {
                alert('Please enter a valid amount');
            }
        }
                document.querySelector('.return-image').addEventListener('click', function() {
                    window.location.href = 'base.php';
                });

    </script>
</body>
</html>
