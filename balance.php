<?php
session_start();

// Check if the session variable is not set and if the current page is not the login page
if (!isset($_SESSION['username']) && basename($_SERVER["PHP_SELF"]) != 'login.html') {
    header("Location: login.html");
    exit();
}

// Check if logout button is clicked
if (isset($_POST['logout'])) {
    // Destroy session
    session_destroy();
    // Redirect to login page
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CASH-A | Announcements </title>
    <style>
        /* Your existing CSS styles */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Gill Sans", sans-serif;
            background-color: #F3F4F6;
            background-image: url("aueec.png");

   
        }
        /* Add new styles for the vertical menu */
        .vertical-menu {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background-color: #f0f0f0;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px; /* Adjust this value as needed */
        }

        .vertical-menu a {
            padding: 10px;
            text-decoration: none;
            color: #333;
            display: block;
            transition: 0.3s;
            font-size: 15px;
            font-weight: bold;
            text-align:left;
            display: flex;
            align-items: center;
        }
        .vertical-menu a.active {
            background-color: #FFFFB2;
            color: black; /* Change text color on hover */
        }

        .vertical-menu a:hover {
            background-color: #ddd;
            color: #fff; /* Change text color on hover */
        }

        .vertical-menu h1 {
            padding: 10px;
            margin: 0;
            font-size: 20px;
            background-color: #ccc;
            color: #333; /* Header text color */
        }

        /* Your existing CSS styles */
        /* Responsive adjustments */
        @media only screen and (max-width: 768px) {
            .vertical-menu {
                padding-top: 30px;
            }

            .vertical-menu a {
                padding: 10px; /* Adjust padding for smaller screens */
                font-size: 15px;
            }

            .vertical-menu h1 {
                font-size: 18px; /* Adjust header font size for smaller screens */
            }
        }

        /* Arrow style */
        #menu-arrow {
            position: absolute;
            top: 20px;
            right: 10px;
            width: 20px;
            cursor: pointer;
        }

        /* Add new styles for the logout button */


.logout-button button {
    text-decoration: none;
    color: #333;
    display: block;
    transition: 0.3s;
    font-size: 15px;
    font-weight: bold;
    background-color: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.logout-button button:hover {
    background-color: #ddd;
    color: #fff; /* Change text color on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <!-- Add a menu image to toggle the vertical menu -->
            <img src="menu.png" alt="Menu" id="menu-icon" style="width:25px; margin:5px;">

            <!-- Add the vertical menu -->
            <div class="vertical-menu" id="vertical-menu">
                <h1>Menu</h1>
                <a href="home.php" ><img src="home.png" alt="Home icon" style="width:20px; margin-right:5px;"> Dashboard</a>
                <a href="edit.php"><img src="edit.png" alt="Edit icon" style="width:20px; margin-right:5px;"> Edit Information</a>
                <a href="history.php"><img src="transaction.png" alt="History icon" style="width:20px; margin-right:5px;"> Transaction History</a>
                <a href="team.php"><img src="team.png" alt="Team icon" style="width:20px; margin-right:5px;"> Team</a>
                <a href="about.php"><img src="about.png" alt="About icon" style="width:20px; margin-right:5px;"> About Us</a>
                <a href="contact.php"><img src="help.png" alt="Contact icon" style="width:20px; margin-right:5px;"> Contact Support</a>
                <hr>
                <div class="logout-button">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <button type="submit" name="logout">
            <img src="logout.png" alt="Logout icon" style="width:20px; margin-right:5px; margin-left:5px; margin-top:5px;">
            Logout
        </button>
    </form>
</div>



                <!-- Add arrow image for closing the menu -->
                <img src="close.png" alt="Close menu" id="menu-arrow" style="padding:20px;">
            </div>
            <!-- Your existing left section -->
            <div class="left-section">
                <!-- Your existing content -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the menu icon, arrow icon, and the vertical menu
            var menuIcon = document.getElementById('menu-icon');
            var menuArrow = document.getElementById('menu-arrow');
            var verticalMenu = document.getElementById('vertical-menu');

            // Add click event listener to the menu icon
            menuIcon.addEventListener('click', function() {
                // Toggle the width of the vertical menu to show/hide it
                if (verticalMenu.style.width === '250px') {
                    verticalMenu.style.width = '0';
                    menuArrow.style.transform = 'rotate(0deg)';
                } else {
                    verticalMenu.style.width = '250px';
                    menuArrow.style.transform = 'rotate(180deg)';
                }
            });

            // Add click event listener to the arrow icon to close the menu
            menuArrow.addEventListener('click', function() {
                verticalMenu.style.width = '0';
                menuArrow.style.transform = 'rotate(0deg)';
            });
        });
    </script>
</body>
</html>
