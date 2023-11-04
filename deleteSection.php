<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['section'])) {
    $sectionNumber = $_GET['section'];

    $connection = mysqli_connect("localhost", "root", "root", "heredb");

    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Perform the section and associated records deletion
    $deleteSectionQuery = "DELETE FROM section WHERE sectionNumber = '$sectionNumber'";
    $result = mysqli_query($connection, $deleteSectionQuery);

    if ($result) {
        $deleteAttendanceRecordQuery = "DELETE FROM classattendancerecord WHERE sectionNumber = '$sectionNumber'";
        $deleteCAR = mysqli_query($connection, $deleteAttendanceRecordQuery);
        if (!$deleteCAR) {
            die("Query failed (CAR): " . mysqli_error($connection));
        }

        $deleteUploadedExcusesQuery = "DELETE FROM uploadedexcuses WHERE attendanceRecordID IN (
            SELECT id FROM classattendancerecord WHERE sectionNumber = '$sectionNumber')";
        $deleteUE = mysqli_query($connection, $deleteUploadedExcusesQuery);
        if (!$deleteUE) {
            die("Query failed (UE): " . mysqli_error($connection));
        }

        $deleteStudentAttendanceQuery = "DELETE FROM studentattendanceinrecord WHERE attendanceRecordID IN (
            SELECT id FROM classattendancerecord WHERE sectionNumber = '$sectionNumber')";
        $deleteSAR = mysqli_query($connection, $deleteStudentAttendanceQuery);
        if (!$deleteSAR) {
            die("Query failed (SAR): " . mysqli_error($connection));
        }

        header("Location: instHomepage.php");
        exit();
    } else {
        // Error handling: display an error message or redirect to an error page
        echo "Failed to delete section. Please try again.";
        echo "Error: " . mysqli_error($connection);
    }
}
