<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = $_POST["college_id"];
    $name = $_POST["name"];
    $house = $_POST["house"];
    $password = $_POST["password"];

    $sql = "INSERT INTO students (college_id, name, house, password) 
            VALUES ('$college_id', '$name', '$house', '$password')";

    if (mysqli_query($conn, $sql)) {
        $message = "🎉 Student Added Successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(-45deg, #ff6b6b, #f5af19, #4facfe, #43e97b);
            background-size: 400% 400%;
            animation: gradient 10s ease infinite;
        }

        @keyframes gradient {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            text-align: center;
            width: 360px;
        }

        h2 {
            font-size: 30px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px #fff, 0 0 20px #4facfe, 0 0 30px #4facfe; }
            to   { text-shadow: 0 0 20px #fff, 0 0 30px #43e97b, 0 0 40px #43e97b; }
        }

        label {
            display: block;
            color: #fff;
            margin: 10px 0 5px;
            text-align: left;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4facfe;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #43e97b;
            transform: scale(1.05);
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
        }

        .message {
            color: yellow;
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            animation: blast 0.8s ease;
        }

        @keyframes blast {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.4); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Add Student</h2>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
            
    <form action="add_student.php" method="POST">
        <label>College ID:</label>
        <input type="text" name="college_id" required>

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>House:</label>
        <select name="house" required>
            <option value="Ignis">Ignis</option>
            <option value="Aqua">Aqua</option>
            <option value="Aer">Aer</option>
            <option value="Terra">Terra</option>
        </select>

        <label>Password:</label>
        <input type="text" name="password" required>

        <button type="submit">Add Student</button>
    </form>

    <a href="admin_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
