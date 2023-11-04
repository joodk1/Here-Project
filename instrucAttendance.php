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

if (isset($_GET['section'])) {
    $sectionID = $_GET['section'];

    $sectionQuery = "SELECT section.*, course.symbol, course.name FROM section
                    INNER JOIN course ON section.courseID = course.id
                    WHERE section.sectionNumber = $sectionID";
    $sectionResult = mysqli_query($connection, $sectionQuery);
    $sectionData = mysqli_fetch_assoc($sectionResult);
    
    ?>

    <html>
        <head>
            <meta charset="UTF-8">
            <title>Instructor Attendance</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <header><h2>Attendance</h2><h3><a href="clear.php">Log-out</a></h3></header>

            <?php
            echo "Course Code: " . $sectionData['symbol'] . "<br>";
            echo "Course Name: " . $sectionData['name'] . "<br>";
            echo "Section Number: " . $sectionData['sectionNumber'] . "<br>";
            echo "Section Type: " . $sectionData['type'] . "<br>";
            echo "Section Hours: " . $sectionData['hours'] . "<br><br>";

            //Retrieve dates of previously recorded classes and display attendance for the last class
            $datesQuery = "SELECT DISTINCT date FROM classattendancerecord WHERE sectionNumber = $sectionID ORDER BY date DESC";
            $datesResult = mysqli_query($connection, $datesQuery);

            echo "<form method='POST'>";
            echo "Select Date: <select name='attendanceDate'>";
            while ($dateRow = mysqli_fetch_assoc($datesResult)) {
                $date = $dateRow['date'];
                echo "<option value='$date'>$date</option>";
            }
            echo "</select>";
            echo "<button type='submit' name='displayAttendance'>Display</button>";
            echo "</form>";

            // Display attendance for the selected date or last class by default
            if (isset($_POST['attendanceDate']) || mysqli_num_rows($datesResult) > 0) {
                $selectedDate = isset($_POST['attendanceDate']) ? $_POST['attendanceDate'] : $date;
                $attendanceQuery = "SELECT student.*, studentattendanceinrecord.attendance, classattendancerecord.date
                FROM studentattendanceinrecord
                JOIN student ON studentattendanceinrecord.studentKSUID = student.KSUID
                JOIN classattendancerecord ON studentattendanceinrecord.attendanceRecordID = classattendancerecord.id
                WHERE classattendancerecord.sectionNumber = $sectionID
                AND classattendancerecord.date = '$selectedDate'";
                $attendanceResult = mysqli_query($connection, $attendanceQuery);

                echo "<table>";
                echo "<tr><th>KSU ID</th><th>Name</th><th>Attendance</th></tr>";
                while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
                    echo "<tr>";
                    echo "<td>" . $attendanceRow['KSUID'] . "</td>";
                    echo "<td>" . $attendanceRow['firstName'] . ' ' . $attendanceRow['lastName'] . "</td>";
                    echo "<td>" . $attendanceRow['attendance'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            echo '<br><hr><br>';

            // Display the form to enter attendance
            echo "<form method='POST'>";
                echo "Enter Date: <input type='date' name='newAttendanceDate' required><br><br>";

                // Retrieve section students
                $studentsQuery = "SELECT * FROM sectionstudents
                                  JOIN student ON sectionstudents.studentKSUID = student.KSUID
                                  WHERE sectionNumber = $sectionID";
                $studentsResult = mysqli_query($connection, $studentsQuery);
                if (!$studentsResult) {
                    die("Query failed: " . mysqli_error($connection));
                }

                echo "<table>";
                    echo "<tr><th>KSU ID</th><th>Name</th><th>Attendance</th></tr>";
                    while ($studentRow = mysqli_fetch_assoc($studentsResult)) {
                        $studentID = $studentRow['studentKSUID'];
                        $firstName = $studentRow['firstName'];
                        $lastName = $studentRow['lastName'];
                        echo "<tr>";
                            echo "<td>$studentID</td>";
                            echo "<td>$firstName $lastName</td>";
                            echo "<td><select name='attendance_$studentID'>
                                <option value='1' selected>attended</option>
                                <option value='0'>absent</option>
                            </select></td>";
                        echo "</tr>";
                    }
                echo "</table>";
                echo "<br>";
                echo '<button type="submit" name="saveAttendance" class="bButtons">Save</button>';
            echo "</form>";
        } else if (!isset($_GET['section'])){
            echo "Section ID not provided.";
        }
        mysqli_close($connection);
        ?>
    </body>
</html>