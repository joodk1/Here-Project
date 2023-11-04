<?php
session_start();
$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: homepage.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Retrieve form input data
        $sectionNumber = $_GET['sectionNumber'];
        $type = $_GET['type'];
        $hours = $_GET['hours'];
        $courseID = $_GET['course'];
        $instructorID = $_SESSION['id'];
        
        // Check if the section number already exists in the Section table
        $checkQuery = "SELECT * FROM section WHERE sectionNumber ='$sectionNumber'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Section already exists
            echo "Section already exists. Please choose a different section number.";
        } else {
            // Section doesn't exist
            $insertQuery = "INSERT INTO section (sectionNumber,courseID,type,hours,instructorID) VALUES ('$sectionNumber', '$courseID', '$type', '$hours', '$instructorID')";
            if (mysqli_query($connection, $insertQuery)) {
                // Redirect the user to the second step page "AddSection_Step2"
               $url = "AddSection_Step2.php?sectionNumber=".$sectionNumber;
                header("Location: " . $url);
                exit();
            } else {
                echo '';
            }
        }
    }
//var_dump($_GET);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Add New Section</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header><h1>Add New Section</h1></header>
        <h2>Step 1: Section Details</h2>
        <div class="divborder">
            <form method="GET">
                <fieldset>
                    
                    <label> Section: </label>
                    <input type="text" name="sectionNumber" width="200pt" id="sectionNumber"><br>
                    <label>Course: </label>
                    <select name="course" id="course">
                        <?php
                        $sql = "SELECT * FROM course";
                        $result = mysqli_query($connection, $sql);
                        // Display options fetched from the database
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='".$row['id']."'>".$row['id']."-".$row['name']."</option>";
                        }
                        ?>
                    </select><br>
                    <label>Type: </label>
                    <input type="radio" name="type" value="Lecture" checked="checked"> Lecture
                    <input type="radio" name="type" value="Lab"> Lab<br>
                    <label>Hours: </label>
                    <input type="text" name="hours" width="200pt" id="hours">
                </fieldset>
                <br>
                <button type="submit" class="button">Next</button>
            </form>
        </div>
        <?php $connection->close(); ?>
    </body>
</html>