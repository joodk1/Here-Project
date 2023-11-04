<?php
session_start();
$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: ".mysqli_error($connection));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input data
    $email = $_POST['email'];
    $password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
    $fname=$_POST['fName'];
    $lname=$_POST['lName'];

    // Check if the email exists in the Instructor table
    $checkInstructorQuery = "SELECT * FROM instructor WHERE email_address = '$email'";
    $checkInstructorResult = mysqli_query($connection, $checkInstructorQuery);

    if (mysqli_num_rows($checkInstructorResult) > 0) {
        // Display an alert if the email already exists
        echo "<script>alert('An account already exists with this email address');</script>";
        echo "<script>window.location = 'instLogin.php';</script>";
        exit();
    } else {
        // Insert the new instructor into the database
        $insertQuery = "INSERT INTO instructor (first_name,last_name,email_address, password) VALUES ('$fname','$lname','$email','$password')";
        if (mysqli_query($connection, $insertQuery) === TRUE) {
            // Display a success alert if the sign-up is successful
            echo "<script>alert('You have signed up successfully!');</script>";
            $_SESSION['id'] = $instructorData['id'];
            $_SESSION['role'] = 'instructor';
            echo "<script>window.location = 'instHomepage.php';</script>"; // Redirect to the appropriate page
            exit();
        } else {
            echo "Error: ".$insertQuery."<br>".$connection->error;
        }
    }
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title> 
    <link rel="stylesheet" href="style.css">
    <script src="javascript.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><span>Sign Up Form</span></div>
        <form name="signupForm" method="POST">
            <div class="row2">
                <p><strong>Please fill in this form to create a new account</strong></p>
            <hr>
            </div>
            
            <div class="row2">
            <label for="fName"><b>First Name</b></label>
            <input type="text" placeholder="Enter First Name" name="fName" required>
            </div>

            <div class="row2">
            <label for="lName"><b>Last Name</b></label>
            <input type="text" placeholder="Enter Last Name" name="lName" required>
            </div>
            
            <div class="row2">
            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>
            </div>

            <div class="row2">
            <label for="pwd"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="pwd" required>
            </div>

            <div class="pass">
                <Button type="Submit" value="Sign Up" class="row button">Sign up</button>
            </div>
            <a href="homepage.php">Back</a>
          
        </form>
      </div>
    </div>
  </body>
</html>
