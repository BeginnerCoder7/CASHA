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
    <title>CASH-A | Team</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Gill Sans", sans-serif;
            background-color: #949ab1;
            background-image: url("aueec.png");
        }
        .content {
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .team-member {
            width: 43%;
            height: 250px;
            margin-bottom: 2%;
            background-color: #fffdf6;
            color:#4c5372;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: -10px;
            padding: 20px;
            margin-top:-10px;
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid black;
            padding: 2px;
            margin-bottom: 10px;
        }
        .team-title {
            text-align: center;
            margin-top:20px;
            color:white;
            margin:20px;
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
    </style>
</head>
<body>
<a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a>
    <h2 class="team-title">Our Team</h2>
    <div class="content">
        <div class="team-member">
            <div class="profile-picture">
                <img src="cyrill.jpg" alt="Profile Picture" >
            </div>
            <div class="profile-info">
                <h3>Cyrill Kate Camacho</h3>
                <p>Malabon City</p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="ellysa.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info"><br>
                <h3>Ellysa S. Maglinte</h3>
                <p>Navotas City</p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="orench.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>Orench Struezel Manicad </h3>
                <p>Malabon City </p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="ryza.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>Ryza Mhay Topacio </h3>
                <p>Malabon City </p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="lyra.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>Lyraalthea Pascual </h3>
                <p>Navotas City </p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="hazel.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>Hazel G. Baltazar</h3>
                <p>Malabon City </p>
            </div>
        </div>

        <div class="team-member">
            <div class="profile-picture">
                <img src="april.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>April Mae Caguicla</h3>
                <p>Malabon City </p>
            </div>
        </div>

        <div class="team-member"><br>
            <div class="profile-picture">
                <img src="userr.png" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h3>Aprian May Roquite</h3>
                <p>Quezon City</p>
            </div>
        </div>

      
    </div><br><br>

    <script>
         document.querySelector('.return-image').addEventListener('click', function() {
            window.location.href = 'base.php';
        });

    </script>
    <footer>
        S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>
</body>
</html>
