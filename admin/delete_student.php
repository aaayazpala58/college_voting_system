<?php
include("../db_connect.php");

if (isset($_GET['college_id'])) {
    $college_id = $_GET['college_id'];
    $sql = "DELETE FROM students WHERE college_id='$college_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: view_students.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
