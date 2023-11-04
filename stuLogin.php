<?php

session_start();
$connection = mysqli_connect("localhost", "root", "root", "heredb");

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input data
    $KSUID = $_POST['ksuID'];
    $password = $_POST['pass'];

    // Check if the KSUID and password are correct
    $checkStudentQuery = "SELECT * FROM studentaccount WHERE KSUID = '$KSUID'";
    $checkStudentResult = mysqli_query($connection, $checkStudentQuery);

    if (mysqli_num_rows($checkStudentResult) > 0) {
        $row = mysqli_fetch_assoc($checkStudentResult);
        if (password_verify($password, $row['password'])) {
            // Set session variables and redirect to the student's home page
            $_SESSION['userID'] = $row['KSUID'];
            $_SESSION['role'] = 'student';
            header("Location: stuHomepage.php");
            exit();
        } else {
            // Redirect back to the login page with an error message
             echo "<script>alert('Invalid Password');</script>";
              echo "<script>window.location = 'stuLogin.php';</script>";
    }
    
        }else{
        echo "<script>alert('Invalid credintials');</script>";
        echo "<script>window.location = 'stuLogin.php';</script>";
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
                <input type="number" name="ksuID" placeholder="KSU ID" required>
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" name="pass" placeholder="Password" required>
            </div>
            
            <div class="pass">
                <button type='Submit' class="row button">Login</button>
            </div>
            <div class="signup-link">New student? <a href="stuSignup.php">Sign up</a>
            <br><br> <a href="homepage.php">Back</a> </div>
        </form>  
      </div>
    </div>
  </body>
</html>
