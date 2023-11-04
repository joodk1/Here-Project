<?php
$connection = mysqli_connect("localhost", "root", "root", "heredb");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['saveAttendance'])) {
    $sectionID = $_POST['sectionID'];
    $newAttendanceDate = $_POST['newAttendanceDate'];

    // Insert new record in ClassAttendanceRecord
    $insertClassAttendanceQuery = "INSERT INTO classattendancerecord (sectionNumber, date) VALUES ($sectionID, '$newAttendanceDate')";
    mysqli_query($connection, $insertClassAttendanceQuery);

    // Retrieve section students
    $studentsQuery = "SELECT studentKSUID FROM sectionstudents WHERE sectionNumber = $sectionID";
    $studentsResult = mysqli_query($connection, $studentsQuery);

    // Insert new records in StudentAttendanceInRecord
    while ($studentRow = mysqli_fetch_assoc($studentsResult)) {
        $studentID = $studentRow['studentKSUID'];
        $attendance = isset($_POST['attendance_' . $studentID]) ? $_POST['attendance_' . $studentID] : 'absent';
        $insertAttendanceQuery = "INSERT INTO studentattendanceinrecord (sectionNumber, studentKSUID, attendance)
                        VALUES ($sectionID, $studentID, '$attendance')";
        mysqli_query($connection, $insertAttendanceQuery);
    }

    header("Location: instrucAttendance.php?section=$sectionID");
}

mysqli_close($connection);
