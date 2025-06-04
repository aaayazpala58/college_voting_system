<?php
session_start();
if(!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include '../db_connect.php'; 

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = $_POST['college_id'];
    $position = $_POST['position'];
    $house = $_POST['house'];

    $name_query = "SELECT name FROM students WHERE college_id = '$college_id'";
    $name_result = mysqli_query($conn, $name_query);

    if (mysqli_num_rows($name_result) > 0) {
        $row = mysqli_fetch_assoc($name_result);
        $name = $row['name'];
    } else {
        $name = "Unknown";
    }

    $photo_name = $_FILES['photo']['name'];
    $photo_tmp_name = $_FILES['photo']['tmp_name'];
    $photo_folder = "../uploads/" . $photo_name;

    if (move_uploaded_file($photo_tmp_name, $photo_folder)) {
        $sql = "INSERT INTO candidates (college_id, position, house, photo, name) 
                VALUES ('$college_id', '$position', '$house', '$photo_name', '$name')";

        if (mysqli_query($conn, $sql)) {
            $message = "🎉Candidate Added Successfully!🎉";
            $success = true;
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Failed to upload photo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Candidate</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(-45deg, #ff512f, #dd2476, #00c6ff, #0072ff);
            background-size: 400% 400%;
            animation: gradient 10s ease infinite;
            color: #fff;
        }

        @keyframes gradient {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .container {
            max-width: 500px;
            margin: 80px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
        }

        h2 {
            text-align: center;
            font-size: 35px;
            color: #ffc107;
            text-shadow: 0 0 10px #ffc107, 0 0 20px #ffc107;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 5px #ffc107, 0 0 10px #ffc107; }
            to { text-shadow: 0 0 20px #ffc107, 0 0 40px #ffc107; }
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 18px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        select option {
            background-color: #333;
            color: #fff;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #ffc107;
            border: none;
            color: #000;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #ff9800;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            animation: scaleUp 0.5s ease forwards;
        }

        @keyframes scaleUp {
            0% {transform: scale(0);}
            100% {transform: scale(1);}
        }

        .success { color: yellow; }
        .error { color: red; }

        .emoji {
            font-size: 50px;
            text-align: center;
            margin-top: 10px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Add Candidate</h2>

    <?php
    if (!empty($message)) {
        echo "<div class='message ".($success ? "success" : "error")."'>$message</div>";
      
    }
    ?>

    <form action="add_candidate.php" method="POST" enctype="multipart/form-data">
        <label>College ID:</label>
        <input type="text" name="college_id" required style="font-size: 18px;">

        <label>Position:</label>
        <select name="position" required style="font-size: 18px;">
            <option value="House Captain">House Captain</option>
            <option value="Vice Captain">Vice Captain</option>
            <option value="Sports Captain">Sports Captain</option>
            <option value="Sports Vice Captain">Sports Vice Captain</option>
            <option value="Cultural Captain">Cultural Captain</option>
            <option value="Cultural Vice Captain">Cultural Vice Captain</option>
            <option value="Discipline Captain">Discipline Captain</option>
            <option value="Discipline Vice Captain">Discipline Vice Captain</option>
        </select>

        <label>House:</label>
        <select name="house" required style="font-size: 18px;">
            <option value="Ignis">Ignis</option>
            <option value="Aqua">Aqua</option>
            <option value="Aer">Aer</option>
            <option value="Terra">Terra</option>
        </select>

        <label>Upload Photo:</label>
        <input type="file" name="photo" required style="font-size: 18px;">

        <button type="submit">Add Candidate</button>
    </form>

    <a class="back" href="admin_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
