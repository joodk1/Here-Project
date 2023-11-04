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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $KSUID = $_POST['KSUID'];
        $sectionNumber = $_GET['sectionNumber']; // Retrieve sectionNumber using $_GET
$alertMessage='student added successfuly!';
        // Check if the student is already in the section
        $checkQuery = "SELECT * FROM sectionstudents WHERE studentKSUID='$KSUID' AND sectionNumber='$sectionNumber'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "This student is already added to the section!";
        } else {
            // Add the student to the SectionStudents table
            $insertQuery = "INSERT INTO sectionstudents (studentKSUID, sectionNumber) VALUES ('$KSUID', '$sectionNumber')";
            if (mysqli_query($connection, $insertQuery)) {
                // Redirect the user back to the AddSection_Step2 page
                echo '<script>alert("' . $alertMessage . '")</script>';
                header("Location: AddSection_Step2.php"."?sectionNumber=".$sectionNumber);
                exit();
            } else {
                echo "";
            }
        }
    }

// Fetch student information from the database
$studentQuery = "SELECT s.KSUID, s.firstName, s.lastName
        FROM student s
        JOIN sectionstudents ss ON s.KSUID = ss.studentKSUID
        WHERE ss.sectionNumber = '$sectionNumber'";

$studentResult = mysqli_query($connection, $studentQuery);
?>



<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Section</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header><h1>Add New Section</h1></header>
    <h2>Step 2: Add Students to Section</h2>
    <div class="divborder">
        <form id="addstu_info" class="form" method="POST">
            <input type="hidden" name="sectionNumber" value="<?php echo $sectionNumber; ?>">
            <label> KSU ID: </label>
            <input type="number" name="KSUID" width="200pt" id="KSUID">
            <label>Name: </label>
            <input type="text" name="name" width="200pt" id="name" disabled>
            <input type="submit" value="Add" class="add">
        </form>
    </div>
    <br>
    
    <div class="divborder">
        <table id="addStudent">
            <caption>Student List: </caption>
            <tr>
                <th>KSU ID</th>
                <th>Name</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($studentResult)) {
                echo "<tr>";
                echo "<td>" . $row['KSUID']."</td>";
                echo "<td>" . $row['firstName']." ".$row['lastName']."</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <br>
    <a href="instHomepage.php" class="button">Done</a>
</body>
</html>