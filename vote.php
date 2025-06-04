<?php
session_start();
include("db_connect.php");

// Redirect to login if student is not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$college_id = $_SESSION['student_id'];

$query = "SELECT house FROM students WHERE college_id = '$college_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("❌ SQL Error: Student record not found. <a href='login.php'>Login</a>");
}

$row = mysqli_fetch_assoc($result);
$student_house = $row['house'];

// Do not destroy session here; only after vote submission

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vote Now</title>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        overflow-x: hidden;
        background: linear-gradient(270deg, #ff6ec4, #7873f5, #00c9ff, #92fe9d);
        background-size: 800% 800%;
        animation: gradientBG 20s ease infinite;
        position: relative;
    }

    @keyframes gradientBG {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
    }

    h2 {
        text-align: center;
        font-size: 60px;
        text-shadow: 0 0 10px #fff, 0 0 20px #ff00cc;
        animation: glow 2s infinite alternate;
    }

    @keyframes glow {
        from {text-shadow: 0 0 10px #ff00cc, 0 0 20px #ff00cc;}
        to {text-shadow: 0 0 20px #00ffff, 0 0 40px #00ffff;}
    }

    form {
        max-width: 800px;
        margin: 50px auto;
        padding: 25px;
        background: rgba(0,0,0,0.5);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
    }
    input[type="radio"] {
    transform: scale(1.8); /* Increase size to 1.5 times */
    margin-right: 10px; /* Optional: Add some space between the radio button and label */
    }

    h3 {
        text-align: center;
        font-size: 28px;
        color: yellow;
        text-shadow: 0 0 10px yellow;
        margin-top: 40px;
    }

    .candidate-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
        padding: 12px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        transition: 0.4s;
    }

    .candidate-container:hover {
        transform: scale(1.05);
        background: rgba(255,255,255,0.2);
    }

    .candidate-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #fff;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {transform: scale(1);}
        50% {transform: scale(1.1);}
        100% {transform: scale(1);}
    }

    .candidate-name {
        font-size: 22px;
        color: #fff;
    }

    #btn2 {
        display: block;
        margin: 30px auto;
        padding: 15px 40px;
        font-size: 22px;
        background: linear-gradient(45deg, #ff6ec4, #7873f5);
        border: none;
        border-radius: 50px;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 0 15px #ff6ec4;
        transition: all 0.4s ease;
    }

    #btn2:hover {
        background: linear-gradient(45deg, #7873f5, #ff6ec4);
        box-shadow: 0 0 30px #ff6ec4, 0 0 50px #7873f5;
        transform: scale(1.1);
    }

    a {
        color: yellow;
    }

    /* Floating particles */
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .particles span {
        position: absolute;
        display: block;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.8);
        animation: moveParticles 15s linear infinite;
        border-radius: 50%;
    }

    @keyframes moveParticles {
        0% {transform: translateY(0) rotate(0deg);}
        100% {transform: translateY(-100vh) rotate(720deg);}
    }

</style>
</head>

<body>

<div class="particles">
    <?php for($i=0; $i<100; $i++): ?>
        <span style="left:<?=rand(0,100)?>%; top:<?=rand(0,100)?>%; animation-duration:<?=rand(5,15)?>s;"></span>
    <?php endfor; ?>
</div>

<h2>Vote for Your House Representatives</h2>

<form method="POST" action="submit_vote.php">
<?php 
foreach ($positions as $form_name => $position_name) {
    $query = "SELECT * FROM candidates WHERE house = '$student_house' AND position = '$position_name'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p style='color:red;'>❌ Error fetching candidates: " . mysqli_error($conn) . "</p>";
        continue;
    }

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Select your $position_name</h3>";

        while ($candidate = mysqli_fetch_assoc($result)) {
            echo '<div class="candidate-container">';
            echo '<input type="radio" name="'.$form_name.'" value="'.$candidate['candidate_id'].'" required>';
            echo '<span class="candidate-name">'.htmlspecialchars($candidate['name']).'</span>';
            $photo = !empty($candidate['photo']) ? $candidate['photo'] : 'default.jpg';
            echo '<img src="uploads/'.htmlspecialchars($photo).'" alt="'.htmlspecialchars($candidate['name']).'" class="candidate-photo">';
            echo '</div>';
        }
    } else {
        echo "<h3>No candidates available for $position_name...</h3>";
    }
}
?>

<button type="submit" id="btn2">Submit Vote</button>
</form>

</body>
</html>
