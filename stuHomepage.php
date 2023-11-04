<?php
session_start();

$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: homepage.php");
    exit();
}

if (!isset($_SESSION['userID'])) {
    header("Location: stuLogin.php"); // Redirect to the login page
    exit();
}

/*IFNULL(
        ROUND(
            SUM(CASE
                WHEN s.type = 'lecture' THEN s.hours
                WHEN s.type = 'lab' THEN s.hours / 2
                ELSE 0
            END) / GREATEST(SUM(s.hours), 1) * 100
        , 2), 'N/A') AS absencePercentage*/

$studentKSUID = $_SESSION['userID'];
$sql = "SELECT * FROM student WHERE KSUID = $studentKSUID";
$result = mysqli_query($connection, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
$student = mysqli_fetch_assoc($result);

$ssql = "SELECT c.symbol AS CourseSymbol, c.name AS CourseName, car.id AS AttendanceRecordID, COUNT(car.id) AS carCount, car.date AS AttendanceDate
        FROM sectionstudents SS
        INNER JOIN section sec ON SS.sectionNumber = sec.sectionNumber
        INNER JOIN course c ON sec.courseID = c.id
        LEFT JOIN classattendancerecord car ON sec.sectionNumber = car.sectionNumber
        WHERE SS.studentKSUID = $studentKSUID
        GROUP BY CourseSymbol, CourseName, AttendanceRecordID, AttendanceDate";
$rresult = mysqli_query($connection, $ssql);
if (!$rresult) {
    die("Query failed: " . mysqli_error($connection));
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Homepage</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h4>Welcome <?php echo $student['firstName']; ?> <a href="clear.php">Log-out</a></h4>
        </header>
        <div>
            <p>First Name: <?php echo $student['firstName']; ?></p>
            <p>Last Name: <?php echo $student['lastName']; ?></p>
            <p>KSU ID: <?php echo $student['KSUID']; ?></p>
        </div>

        <br>

        
        <form method="GET">
        <table id="coursesTable">
            <caption>Registered Courses</caption>
            <tr>
                <th>Course</th>
                <th>Attendance Record</th>
                <th>Absence Percentage</th>
            </tr>
            <?php
            while ($course = mysqli_fetch_assoc($rresult)) {
                if (mysqli_num_rows($rresult) == 0) {
                    echo '<td colspan="3">Student is not registered in any courses</td>';
                } else {
                    echo '<tr>';
                    echo '<td><strong>' . $course['CourseSymbol'] . '</strong><br>' . $course['CourseName'] . '</td>';
                    echo '<td><a href="studentAttendence.php?course=' . $course['AttendanceRecordID'] . '">Attendance</a></td>';
                    //if there are no recorded classes, not sure here
                    if($course['carCount' == 0]){
                        '<td>There are no recorded classes</td>';
                    } else {
                        echo '<td>' . $course['absencePercentage'] . '%</td>';
                    }
                    echo '</tr>';
                }
            }
            ?>
        </table>
        </form>    
    </body>
</html>