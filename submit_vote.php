<?php
session_start();
include 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Session expired! Please log in again.'); window.location.href='login.php';</script>";
    exit();
}

$college_id = $_SESSION['student_id'];

// Debugging: Check if POST data is received
if (empty($_POST)) {
    echo "<script>alert('No vote data received. Please try again.'); window.location.href='vote.php';</script>";
    exit();
}

// Check if the student has already voted
$check_vote = "SELECT has_voted FROM students WHERE college_id = '$college_id'";
$result = mysqli_query($conn, $check_vote);
$row = mysqli_fetch_assoc($result);

if ($row['has_voted'] == 1) {
    echo "<script>alert('You have already voted!'); window.location.href='login.php';</script>";
    exit();
}

// Get student house
$house_query = "SELECT house FROM students WHERE college_id = '$college_id'";
$house_result = mysqli_query($conn, $house_query);
$house_row = mysqli_fetch_assoc($house_result);
$house = $house_row['house'];

// Define positions
$positions = [
    "house_captain" => "House Captain",
    "vice_captain" => "Vice Captain",
    "cultural_captain" => "Cultural Captain",
    "cultural_vice_captain" => "Cultural Vice Captain",
    "sports_captain" => "Sports Captain",
    "sports_vice_captain" => "Sports Vice Captain",
    "discipline_captain" => "Discipline Captain",
    "discipline_vice_captain" => "Discipline Vice Captain"
];

// Start transaction
mysqli_begin_transaction($conn);

try {
    foreach ($positions as $form_name => $position_name) {
        if (!isset($_POST[$form_name]) || empty($_POST[$form_name])) {
            throw new Exception("Please select a candidate for $position_name.");
        }

        $candidate_id = mysqli_real_escape_string($conn, $_POST[$form_name]);

        $query = "INSERT INTO votes (college_id, candidate_id, position, house) 
                  VALUES ('$college_id', '$candidate_id', '$position_name', '$house')";

        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error submitting vote for $position_name: " . mysqli_error($conn));
        }
    }

    // Update the student's 'has_voted' status to 1
    $update_student = "UPDATE students SET has_voted = 1 WHERE college_id = '$college_id'";
    if (!mysqli_query($conn, $update_student)) {
        throw new Exception("Error updating student vote status.");
    }

    // Commit the transaction
    mysqli_commit($conn);
    // After successful vote submission (before redirection)
session_unset();
session_destroy();  // Destroy the session
    // Redirect to the results or thank you page after successful voting
    echo "<script>alert('Vote submitted successfully!'); window.location.href = 'login.php';</script>";
} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($conn);
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='vote.php';</script>";
}
?>
