<?php
$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_error($connection));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input data
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Check if the email and password match in the Instructor table
    $checkInstructorQuery = "SELECT * FROM instructor WHERE email_address = '$email'";
    $checkInstructorResult = mysqli_query($connection, $checkInstructorQuery);

    if (mysqli_num_rows($checkInstructorResult) > 0) {
        $instructorData = mysqli_fetch_assoc($checkInstructorResult);
        if (password_verify($password, $instructorData['password'])) {
            // Set session variables for the logged-in instructor
            session_start();
            $_SESSION['id'] = $instructorData['id'];
            $_SESSION['role'] = 'instructor';
            // Redirect to the instructor's home page
            header("Location: instHomepage.php");
            exit();
        } else {
            // Display an alert if the password is incorrect
            echo "<script>alert('Incorrect password');</script>";
            echo "<script>window.location = 'instLogin.php';</script>";
            exit();
        }
    } else {
        // Display an alert if the email is not found
        echo "<script>alert('Email not found');</script>";
        echo "<script>window.location = 'instLogin.php';</script>";
        exit();
    }
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title> 
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <script src="javascript.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="wrapper">
                <div class="title"><span>Login Form</span></div>
                <form name="loginForm" method="POST">
                    <div class="row">
                        <i class="fas fa-user"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="row">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" placeholder="Password" required>
                    </div>

                    <div class="pass">
                        <button type="Submit" value="Login" class="row button">Login</button>
                    </div>

                    <div class="signup-link">New member? <a href="instSignup.php">Sign up</a>
                        <br><br> <a href="homepage.php">Back</a> </div>
                </form>
            </div>
        </div>
    </body>
</html>
