<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = trim($_POST['college_id']);
    $password = trim($_POST['password']);

    $college_id = mysqli_real_escape_string($conn, $college_id);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM students WHERE college_id = '$college_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['password']) {
            session_regenerate_id(true);
            $_SESSION['student_id'] = $row['college_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['has_voted'] = $row['has_voted'];
            header("Location: vote.php");
            exit();
        } else {
            $_SESSION["error"] = "Invalid Password!";
        }
    } else {
        $_SESSION["error"] = "No account found with this College ID!";
    }
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Login</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap');

body {
    margin: 0;
    padding: 0;
    font-family: 'Orbitron', sans-serif;
    background: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
    background-size: cover;
    position: relative;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}


body::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0,0,0,0.7);
    backdrop-filter: blur(2px);
    z-index: 0;
}

.login-container_std {
    max-width: 400px;
    width: 90%;
    padding: 40px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    text-align: center;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
    animation: fadeIn 2s ease forwards;
    transform: translateY(-50px);
    opacity: 0;
    z-index: 1;
}

@keyframes fadeIn {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

h1 {
    color: #fff;
    font-size: 45px;
    margin-bottom: 20px;
    text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff;
}

label {
    color: #fff;
    font-size: 20px;
}

input {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 10px;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 16px;
    outline: none;
}

input::placeholder {
    color: #ccc;
}

button {
    padding: 12px 30px;
    margin-top: 10px;
    font-size: 18px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    background: linear-gradient(45deg, #00b4db, #0083b0);
    color: white;
    transition: 0.4s;
    box-shadow: 0 0 10px #fff, 0 0 20px #00b4db inset;
}

button:hover {
    background: linear-gradient(45deg, #0083b0, #00b4db);
    transform: scale(1.1);
    box-shadow: 0 0 20px #fff, 0 0 30px #00b4db inset;
}

p {
    color: yellow;
    font-size: 18px;
    margin-top: 10px;
    text-shadow: 0 0 5px red;
}
.back-btn {
    display: block;
    margin-top: 20px;
    background-color: #2c80b4;
    color: #fff;
    padding: 12px 25px;
    border-radius: 10px;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
}
</style>

</head>

<body>

<div class="login-container_std">
    <h1>Student Login</h1>

    <?php
    if (isset($_SESSION["error"])) {
        echo "<p>" . $_SESSION["error"] . "</p>";
        unset($_SESSION["error"]);
    }
    ?>

    <form method="POST" action="login.php">
        <label>College ID:</label><br>
        <input type="text" name="college_id" placeholder="Enter College ID" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>

        <button type="submit">Login</button>
        <!-- Go Back to Homepage Button -->
    <a class="back-btn" href="index.php">← Go Back to Homepage</a>

    </form>
</div>

</body>
</html>
