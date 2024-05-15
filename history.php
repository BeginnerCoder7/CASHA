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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Gill Sans", sans-serif;
            background-color: #F3F4F6;
            background-image: url("aueec.png");
        }
     
        footer {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
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

        .transaction-section {
        background-color: #efefef;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0px auto;
        }

        .transaction-heading {
        text-align: center;
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
        }

        .transaction-table {
        width: 100%;
        border-collapse: collapse;
        }

        .transaction-table th,
        .transaction-table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        }

        .transaction-table th {
        background-color: black;
        color:white;
        font-weight: bold;
        }

        .transaction-table td {
        color: #666;
        }


        .transaction-table tbody tr:hover {
        background-color: #f5f5f5;
        }


        .transaction-table tbody tr.no-transaction td {
        text-align: center;
        color: #888;
        }

        h3{
            margin-top:20px;
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
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
        background-color: #fefefe;
        margin: 25% auto;
        padding: 20px;
        border: 1px solid #888;
        border-radius:20px;
        width: 80%;
        }

        .close {
        color: red;
        float: right;
        font-size: 40px;
        font-weight: bold;

        }

        .close:hover,
        .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
        }

        #modalAmount {
        float: right; 
        display: inline-block;
        margin-left: 10px;
        color: green; 
        }

        #modalAmount.deduction {
        color: red;
        }
        #modalTransactionId{
            float:right;
        }
        #modalTimestamp{
        float:right;
        }
        #modalRecipientUsername{
            float:right;
        }

    </style>
</head>
<body>
<a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
    <div id="transactions" class="transaction-section">
    <h3 class="transaction-heading">Transaction History</h3>
        <div class="table-container">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Reference ID</th>
                        <th>Amount</th>
                        <th>Time Sent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "audb";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM transactions WHERE sender_username = ? OR recipient_username = ?";
                    $stmt = $conn->prepare($sql);
                    $current_username = $_SESSION['username'];
                    $stmt->bind_param("ss", $current_username, $current_username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick='showPaymentDetails(\"" . htmlspecialchars($row["transaction_id"]) . "\", \"" . htmlspecialchars($row["recipient_username"]) . "\", \"" . htmlspecialchars($row["amount"]) . "\", \"" . htmlspecialchars($row["timestamp"]) . "\")'>";
                            echo "<td>" . htmlspecialchars($row["transaction_id"]) . "</td>";
                            $amount = htmlspecialchars($row["amount"]);
                            if ($row["sender_username"] === $_SESSION['username']) {
                                echo "<td>-$amount</td>";
                            } else {
                                echo "<td>+$amount</td>";
                            }
                            $timestamp = date("Y-m-d h:i A", strtotime($row["timestamp"]));
                            echo "<td>" . htmlspecialchars($timestamp) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No transactions found.</td></tr>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="transactionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Payment Title</h2>
            <p>To: <span id="modalRecipientUsername"></span></p>
            <hr>
            <p>Amount Pay: <span id="modalAmount"></span></p>
            <hr>
            <p>Transaction #: <span id="modalTransactionId"></span></p>
            <span id="modalTimestamp"></span><br><br>
            <div style="text-align: center;">
                <img src="check.png" alt="check image" style="width:100px; margin-left:0px;">
            </div>
            <div style="text-align: center; margin-top: 10px;">
                <p style="font-style:italic; font-weight:bold;">Transaction complete using CASHA</p>
            </div>
        </div>
    </div>
    <footer>
        S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>            
    <script> 
        document.querySelector('.return-image').addEventListener('click', function() {
            window.location.href = 'base.php';
        });
        var modal = document.getElementById("transactionModal");

        function showPaymentDetails(transactionId, recipientUsername, amount, timestamp) {
            document.getElementById("modalRecipientUsername").innerText = recipientUsername;
            document.getElementById("modalAmount").innerText = amount;
            document.getElementById("modalTransactionId").innerText = transactionId;
            document.getElementById("modalTimestamp").innerText = timestamp;
            modal.style.display = "block";
        }

        var close = document.querySelector(".close");
        close.onclick = function () {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
            modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
