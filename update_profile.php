<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];

        if (!empty($newUsername) && !empty($newPassword)) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "audb";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "UPDATE users SET username = ?, password = ?, firstName = ?, lastName = ?, middleName = ?, email = ?, phoneNumber = ?, birthdate = ?, address = ?, gender = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssss", $newUsername, $newPassword, $_POST['firstName'], $_POST['lastName'], $_POST['middleName'], $_POST['email'], $_POST['phoneNumber'], $_POST['birthdate'], $_POST['address'], $_POST['gender'], $_SESSION['username']);
            $stmt->execute();

            $_SESSION['username'] = $newUsername;

            if ($_FILES["profilePicture"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = "profile_pictures/";
                $target_file = $target_dir . uniqid() . '_' . basename($_FILES["profilePicture"]["name"]);
                if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                    $sql_update_picture = "UPDATE users SET profile_picture = ? WHERE username = ?";
                    $stmt_update_picture = $conn->prepare($sql_update_picture);
                    if ($stmt_update_picture) {
                        $stmt_update_picture->bind_param("ss", $target_file, $newUsername);
                        $stmt_update_picture->execute();
                        $stmt_update_picture->close();
                    } else {
                        echo "Database error: " . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            $stmt->close();
            $conn->close(); 

            header("Location: edit.php?success=1");
            exit();
        } else {
            header("Location: edit.php?error=1");
            exit();
        }
    } else {
        header("Location: some_other_page.php");
        exit();
    }
?>
