<?php
session_start();

$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error()); // Fixed the function name
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: homepage.php");
    exit();
}

if (!isset($_SESSION['userID'])) {
    header("Location: stuLogin.php"); // Redirect to the login page
    exit();
}

if (isset($_GET['course'])) {
    // Retrieve student ID from the session
    $studentId = $_SESSION['userID'];

    // Retrieve the student's attendance records for both lecture and lab
    //the date, the attendance status, and a link to Upload excuse page
    $query = "SELECT car.date, sar.attendance, sar.attendanceRecordID
                FROM studentattendanceinrecord as sar 
                INNER JOIN classattendancerecord AS car ON sar.attendanceRecordID = car.id
                    WHERE sar.studentKSUID = $studentId";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Retrieve previous absence excuses
    //class type, date of absence, reason of absence, uploaded excuse file, and the instructor’s decision.
    $queryExcuses = "SELECT ue.*, s.*, car.date
                    FROM uploadedexcuses AS ue
                    INNER JOIN classattendancerecord AS car ON ue.attendanceRecordID = car.id
                    INNER JOIN section AS s ON car.sectionNumber = s.sectionNumber
                    INNER JOIN studentaccount ON ue.studentAccountID = studentaccount.id
                    INNER JOIN course AS c ON s.courseID = c.id
                    WHERE studentaccount.KSUID = '$studentId'
                    ORDER BY car.date";
    $resultExcuses = mysqli_query($connection, $queryExcuses); // Fixed the variable name
    if (!$resultExcuses) {
        die("Query failed: " . mysqli_error($connection));
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Student Attendance Record</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header><h2>Student Attendance Record</h2><h3><a href="clear.php">Log-out</a></h3></header>

        <table>
            <caption><h3>Attendance</h3></caption>
            <tr>
                <th>Date</th>
                <th>Attendance Status</th>
                <th>Upload Excuse</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php 
                    if ($row['attendance'] == '0'){
                        echo 'Absent'; 
                    }else if ($row['attendance'] == '1') {
                        echo 'Attended'; 
                    }
                    ?></td>
                    <td>
                        <?php if ($row['attendance'] == '0') { ?>
                            <a href="uploadExcuse.php?attendance_id=<?php echo $row['attendanceRecordID']; ?>">Upload Excuse</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
                
        </table>
        <br><hr><br>
        <table>
            <caption><h3>Previous Absence Excuses</h3></caption>
            <tr>
                <th>Class Type</th>
                <th>Date of Absence</th>
                <th>Reason of Absence</th>
                <th>Uploaded Excuse File</th>
                <th>Instructor's Decision</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resultExcuses)) { ?>
                <tr>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['absenceReason']; ?></td>
                    <td>
                        <?php if (!empty($row['uploadedExcuseFileName'])) { ?>
                            <a href="view_excuse.php?filename=<?php echo $row['uploadedExcuseFileName']; ?>">View Excuse</a>
                        <?php } ?>
                    </td>
                    <td><?php echo $row['decision']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>
