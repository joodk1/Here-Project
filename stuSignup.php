<?php
session_start();
$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_error($connection));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input data
    $KSUID = $_POST['fName'];
    $password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    // Check if the KSUID exists in the Student table
    $checkStudentQuery = "SELECT * FROM student WHERE KSUID = '$KSUID'";
    $checkStudentResult = mysqli_query($connection, $checkStudentQuery);

    // Check if the KSUID exists in the StudentAccount table
    $checkStudentAccountQuery = "SELECT * FROM studentaccount WHERE KSUID = '$KSUID'";
    $checkStudentAccountResult = mysqli_query($connection, $checkStudentAccountQuery);

    if (mysqli_num_rows($checkStudentAccountResult) > 0) {
        // Display an alert if the student account already exists
        echo "<script>alert('An account already exists with these credentials');</script>";
        echo "<script>window.location = 'stuLogin.php';</script>";
        exit();
    } else if (mysqli_num_rows($checkStudentResult) > 0) {
        // Insert the data into the studentAccount table if the KSUID doesn't exist
        $insertQuery = "INSERT INTO studentaccount(password, KSUID) VALUES ('$password','$KSUID')";
        if (mysqli_query($connection, $insertQuery) === TRUE) {
            // Display a success alert if the sign-up is successful
            $_SESSION['userID'] = $row['KSUID'];
            $_SESSION['role'] = 'student';
            echo "<script>alert('You have signed up successfully!');</script>";
            echo "<script>window.location = 'stuLogin.php';</script>"; // Redirect to the login page or appropriate page
            exit();
        }
    } else {
        echo "<script>alert('Invalid, KSUID does not exist');</script>";
    }
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title> 
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        <script src="javascript.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="wrapper">
                <div class="title"><span>Sign Up Form</span></div>
                <!-- 
                
                unfortunately onclick() method didn't work here, we've tried different solutions 
                but they didn't work as well so we ended up using action="" to redirect to another page 
                and patterns for validation
                
                
                <form name="signupForm" action="#" method="get">
                    <div class="row2">
                        <p><strong>Please fill in this form to create a new account</strong></p>
                    <hr>
                    </div>
                    
                    <div class="row2">
                    <label for="ksuID"><b>KSU ID</b></label>
                    <input type="number" placeholder="Enter KSU ID" name="fName">
                    </div>
        
                    <div class="row2">
                    <label for="pwd"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="pwd">
                    </div>
                    
                    <div class="pass">
                        <input type="button" value="Sign Up" class="row button" onclick="stuSignup();">
                    </div>
                
                    <a href="homepage.php">Back</a>
                  
                </form>
                
                -->
                <form name="signupForm" method="POST">
                    <div class="row2">
                        <p><strong>Please fill in this form to create a new account</strong></p>
                        <hr>
                    </div>

                    <div class="row2">
                        <label for="ksuID"><b>KSU ID</b></label>
                        <input type="number" placeholder="Enter KSU ID" name="fName" required>
                    </div>

                    <div class="row2">
                        <label for="pwd"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="pwd" pattern=".{8,}" required>
                    </div>
                    <div class="pass">
                        <input type="submit" value="Sign Up" class="row button">
                    </div>
                    <a href="homepage.php">Back</a>
                </form>
            </div>
        </div>
    </body>
</html>
