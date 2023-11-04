<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page if not logged in.
    header("Location: instLogin.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "root", "heredb");

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: homepage.php");
    exit();
}

// Retrieve instructor's information
$instructorId = $_SESSION['id'];
$sql = "SELECT * FROM instructor WHERE id = $instructorId";
$result = mysqli_query($connection, $sql);
$instructor = mysqli_fetch_assoc($result);

// Retrieve sections that the instructor is teaching
$sql1 = "SELECT * FROM section WHERE instructorID = $instructorId";
$sectionsResult = mysqli_query($connection, $sql1);

// Retrieve uploaded absence excuses under consideration
$sql2 = "SELECT ue.*, car.date, car.sectionNumber, SS.studentKSUID, stu.firstName, stu.lastName
    FROM uploadedexcuses ue
    JOIN classattendancerecord car ON ue.attendanceRecordID = car.id
    JOIN section S ON car.sectionNumber = S.sectionNumber
    JOIN sectionstudents SS ON S.sectionNumber = SS.sectionNumber
    JOIN student stu ON SS.studentKSUID = stu.KSUID
    JOIN studentaccount acc ON stu.KSUID = acc.KSUID
    WHERE S.instructorID = $instructorId";
$excusesResult = mysqli_query($connection, $sql2);
if (!$excusesResult) {
    die("Query failed: " . mysqli_error($connection));
}

$sql3 = "SELECT c.symbol, c.name FROM section s JOIN course c ON s.courseID = c.id";
$courseResult = mysqli_query($connection, $sql3);

$approve = "";

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Instructor Homepage</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h4>Welcome <?php echo $instructor['first_name']; ?> <a href="clear.php">Log-out</a></h4>
        </header>
        <div class="info">
            <p>First Name: <?php echo $instructor['first_name']; ?></p>
            <p>Last Name: <?php echo $instructor['last_name']; ?></p>
            <p>Email Address: <?php echo $instructor['email_address']; ?></p>
        </div>

        <br>

        <table class="instTables" id="sectionsTable">
            <caption>Sections Teaching <a href="AddSection_Step1.php">Add New Section</a></caption>
            <tr>
                <th>Section</th>
                <th>Course</th>
                <th>Type</th>
                <th>Hours</th>
                <th>Attendance Record</th>
            </tr>
            <?php
            while ($section = mysqli_fetch_assoc($sectionsResult)) {
                echo '<tr>';
                echo '<td>' . $section['sectionNumber'] . '</td>';
                if ($course1 = mysqli_fetch_assoc($courseResult)) {
                    echo '<td><strong>' . $course1['symbol'] . '</strong><br>' . $course1['name'] . '</td>';
                }
                echo '<td>' . $section['type'] . '</td>';
                echo '<td>' . $section['hours'] . '</td>';
                echo '<td><a href="instrucAttendance.php?section=' . $section['sectionNumber'] . '">Attendance</a></td>';
                echo '<td><a class="deleteRow" href="deleteSection.php?section='.$section['sectionNumber'].'">Delete</a></td>';
                echo '</tr>';
            }
            ?>
        </table>

        <br>

        <table class="instTables" id="excusesTable">
            <caption>Uploaded Excuses for Absences</caption>
            <tr>
                <th>Section</th>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Absence Reason</th>
                <th>Uploaded Excuse</th>
                <th>Date of Absence</th>
            </tr>
            <?php
            while ($excuse = mysqli_fetch_assoc($excusesResult)) {
                echo '<tr>';
                echo '<td>' . $excuse['sectionNumber'] . '</td>';
                echo '<td>' . $excuse['firstName'] . ' ' . $excuse['lastName'] . '</td>';
                echo '<td>' . $excuse['studentKSUID'] . '</td>';
                echo '<td>' . $excuse['absenceReason'] . '</td>';
                echo '<td><a href="excuseFiles/' . $excuse['uploadedExcuseFileName'] . '">Excuse</a></td>';
                echo '<td>' . $excuse['date'] . '</td>';
                echo "<td><a href='excuseApproval.php?excuse={$excuse["studentKSUID"]}&ok=1'>Approve</a></td>";
                echo "<td><a href='excuseApproval.php?excuse={$excuse["studentKSUID"]}&ok=0'>Disapprove</a></td>";//doesnt work
                echo '</tr>';
            }
            ?>
        </table>
    </body>
</html>