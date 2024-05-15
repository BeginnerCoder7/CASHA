<?php
    session_start();

    $editable = false;
    $oldUsername = "";
    $oldPassword = ""; 


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

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $firstName = $row['firstName'];
            $lastName = $row['lastName'];
            $middleName = $row['middleName'];
            $email = $row['email'];
            $phoneNumber = $row['phoneNumber'];
            $birthdate = $row['birthdate'];
            $address = $row['address'];
            $oldUsername = $row['username'];
            $oldPassword = $row['password'];
            $gender = $row['gender'];
            $profilePicturePath = $row['profile_picture']; 
            $editable = true;
        }

        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <style>
        * {
        box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;

            background: linear-gradient(135deg, #74ebd5 0%, #acb6e5 100%);
        }

        .content {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .return-image {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 999;
            width: 50px;
            cursor: pointer;
            margin:5px;
            background-color:#c1e8ff;
            padding:10px;
            border-radius:100px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        form {
            width: 100%;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button[type="submit"],
        button.cancel-button {
            width: 100%;
            padding: 12px;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"] {
            background-color: #f1f1f1;
            color: black;
        }

        button[type="submit"]:hover,
        button[type="submit"]:active {
            background-color: #74ebd5;
            color: black;
        }

        button.cancel-button {
            background-color: #dc3545;
            color: #fff;
        }

        button.cancel-button:hover {
            background-color: #c82333;
        }

        .gender-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
        }

        .gender-container button {
            width: 40%;
            padding: 10px;
            margin: 0 5%;
            background-color: #f1f1f1;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        .gender-container button.selected,
        button[type="submit"]:active {
            background-color: #acb6e5 ;
            color: black;
        }

        .profile-picture-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
            text-align: left;
        }

        .profile-picture-container label {
            flex: 1;
            font-weight: bold;
            margin-right: 10px;
        }

        #profilePicturePreview {
            margin-right: 15px;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border:5px solid #90cfdd;
            padding:5px;
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

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            .profile-picture-container {
                flex-direction: column;
                align-items: center;
            }
            #profilePicturePreview {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 480px) {
            button[type="submit"],
            button.cancel-button {
                font-size: 14px;
                padding: 10px;
            }

            .gender-container button {
                font-size: 14px;
                padding: 8px;
            }
        }

        .cancelb{
            width: 100%;
            padding: 12px;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .cancelb:hover{
        background-color: #74ebd5;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="left-section">
                <a href="base.php"><img src="return.png" class="return-image" alt="Return to base"></a><br><br>
                <div class="profile-picture-container">
                    <label for="profilePicture">Profile Picture:</label>
                    <?php if (!empty($profilePicturePath)) : ?>
                        <img id="profilePicturePreview" src="<?php echo $profilePicturePath; ?>" alt="Profile Picture">
                    <?php else : ?>
                        <img id="profilePicturePreview" src="placeholder.jpg" alt="Profile Picture">
                    <?php endif; ?>
                    <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
                </div>

                <form id="profileForm" action="update_profile.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="text" name="oldUsername" id="oldUsername" placeholder="Old Username" value="<?php echo $oldUsername; ?>" readonly><br>
                    <input type="text" name="newUsername" id="newUsername" placeholder="New Username (12 digits)" pattern="\d{12}" title="Username must be 12 digits"><br>
                    <input type="password" name="oldPassword" id="oldPassword" placeholder="Old Password" value="<?php echo $oldPassword; ?>" readonly><br>
                    <input type="password" name="newPassword" id="newPassword" placeholder="New Password (At least 8 characters)" pattern=".{8,}" title="Password must be at least 8 characters"><br>
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"><br>
                    <input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" required pattern="[A-Za-z ']{2,}" title="First name must be at least 2 letters"><br>
                    <input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>" required pattern="[A-Za-z ']{2,}" title="Last name must be at least 2 letters"><br>
                    <input type="text" name="middleName" id="middleName" value="<?php echo $middleName; ?>" pattern="[A-Za-z ']{2,}" title="Middle name must be at least 2 letters"><br>
                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" required><br>
                    <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" required pattern="[0-9]{11}" title="Phone number must be 11 digits"><br>
                    <input type="date" name="birthdate" id="birthdate" value="<?php echo $birthdate; ?>" max="<?php echo date('Y-m-d'); ?>" required><br>
                    <input type="text" name="address" id="address" value="<?php echo $address; ?>" required pattern=".{15,}" title="Address must be at least 15 characters"><br>

                    <div class="gender-container">
                        <button type="button" name="gender" value="male" <?php if($gender == 'male') echo 'class="selected"'; ?>>Male</button>
                        <button type="button" name="gender" value="female" <?php if($gender == 'female') echo 'class="selected"'; ?>>Female</button>
                        <input type="hidden" name="gender" id="gender_hidden" value="<?php echo $gender; ?>">
                    </div>

                    <div class="buttons">
                        <button type="submit">Save</button>
                        <button class="cancelb" type="button" onclick="window.location.href='edit.php'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        S.Y 2023-2024 ARELLANO UNIVERSITY ELISA ESGUERRA CAMPUS | CASHA
    </footer>

<script>
    document.getElementById("newUsername").addEventListener("input", function(event) {
        let inputValue = event.target.value.trim(); 
        let numericValue = inputValue.replace(/\D/g, '');

        if (numericValue.length <= 12) {
            event.target.value = numericValue;
        } else {
            let limitedValue = numericValue.slice(0, 12);
            event.target.value = limitedValue;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const genderButtons = document.querySelectorAll('.gender-container button');

        genderButtons.forEach(button => {
            button.addEventListener('click', function() {
                genderButtons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('gender_hidden').value = this.value;
            });
        });

        document.getElementById('profilePicture').addEventListener('change', function(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePicturePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            alert('Profile updated successfully!');
        } else if (urlParams.has('error')) {
            alert('There was an error updating your profile. Please try again.');
        }
    });

    function validateForm() {
        var newUsername = document.getElementById('newUsername').value;
        var newPassword = document.getElementById('newPassword').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (newUsername.trim() === '' || newPassword.trim() === '') {
            alert('Please enter both username and password.');
            return false;
        }

        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return false;
        }

        return true;
    }
</script>
</body>
</html>
