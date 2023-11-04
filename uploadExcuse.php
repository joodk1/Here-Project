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
    header("Location: stuLogin.php");
    exit();
}

$studentKSUID = $_SESSION['userID'];

$sql = "SELECT c.symbol, s.type, car.date, car.sectionNumber, car.id
        FROM section s
        INNER JOIN course c ON s.courseID = c.id
        INNER JOIN classattendancerecord car ON s.sectionNumber = car.sectionNumber
        INNER JOIN sectionstudents ss ON car.sectionNumber = ss.sectionNumber
        WHERE ss.studentKSUID = '$studentKSUID'";
$result = mysqli_query($connection, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
$excuse = mysqli_fetch_assoc($result);

$accSql = "SELECT id
          FROM studentaccount
          WHERE KSUID = $studentKSUID";
$accResult = mysqli_query($connection, $accSql);
if (!$accResult) {
    die("Query failed: " . mysqli_error($connection));
}
$account = mysqli_fetch_assoc($accResult);   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input data
    $absenceReason = $_POST['absenceReason'];
    $carID = $excuse['id'];
    $uploadedFile = $_FILES['excuseFile'];
    $acc = $account['id'];

    // Process the file and store it with a unique name
    $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $fileName = $excuse['sectionNumber'] . '_' . $studentKSUID . '_' . $excuse['date'] . '.' . $fileExtension;
    $fileDestination = 'excuse_files/' . $fileName;
    move_uploaded_file($uploadedFile['tmp_name'], $fileDestination);

    // Check if the excuse already exists
    $checkQuery = "SELECT * FROM uploadedexcuses WHERE studentAccountID = '$studentKSUID' AND attendanceRecordID = '$carID'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Excuse already exists
        echo "Excuse for this absence already exists.";
    } else {
        // Excuse doesn't exist, insert it into the database
        $insertQuery = "INSERT INTO uploadedexcuses (studentAccountID, attendanceRecordID, absenceReason, uploadedExcuseFileName)"
                . " VALUES ('$acc', '$carID', '$absenceReason', '$fileName')";
        if (mysqli_query($connection, $insertQuery)) {
            // Redirect the user to Student homepage
            header("Location: stuHomepage.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Upload Excuse</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h2>Upload Excuse</h2>
            <h3><a href="clear.php">Log-out</a></h3>
        </header>

        <div>
            <p>Course: <?php echo $excuse['symbol']; ?></p>
            <p>Type: <?php echo $excuse['type']; ?></p>
            <p>Date: <?php echo $excuse['date']; ?></p>
        </div>

        <!-- Excuse Form -->
        <br>
        <form action="uploadExcuse.php" method="POST" enctype="multipart/form-data">
            <label for="absenceReason">Absence Reason:</label><br>
            <textarea name="absenceReason" rows='3' cols='25' required></textarea><br><br>

            <label for="excuseFile">Excuse Document (PDF):</label><br>
            <input type="file" name="excuseFile" accept="application/pdf" required><br><br>

            <input type="submit" name="submit" value="Send" class="iButtons">
        </form>
    </body>
</html>
