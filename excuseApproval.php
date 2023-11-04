<?php

if (isset($_GET["excuse"])) {
    $id = $_GET["excuse"];
    $approve = $_GET["ok"] == 1;

    if ($approve) {
        $sql = "Update uploadedexcuses set decision = 'approved' ";
    } else {
        $sql = "Update uploadedexcuses set decision = 'disapproved' ";
    }

    mysqli_query($con, $sql);
    header("Location: InstHomepage.php");
}

