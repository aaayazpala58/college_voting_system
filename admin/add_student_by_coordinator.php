<?php
session_start();
include '../db_connect.php'; 

if (!isset($_SESSION['coordinator_id'])) {
    header("Location: coordinator_login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = $_POST["college_id"];
    $name = $_POST["name"];
    $password = $_POST["password"];
    $house = $_POST["house"];

    // Check for duplicate college_id
    $check = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
    $check->bind_param("s", $college_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $message = "Student with this College ID already exists.";
    } else {
        $sql = "INSERT INTO students (college_id, name, password, house, has_voted) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $college_id, $name, $password, $house);

        if ($stmt->execute()) {
            $message = "Student added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 480px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #4CAF50, #45A049);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: linear-gradient(45deg, #45A049, #4CAF50);
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
            color: green;
            text-align: center;
        }
        .error {
            color: red;
        }
        .back-link,
        .back-button {
            display: block;
            margin: 20px auto 0;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            color: #007BFF;
            cursor: pointer;
            max-width: 200px;
            padding: 10px 0;
            border-radius: 25px;
            border: 2px solid #007BFF;
            background-color: white;
            font-size: 1rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .back-link:hover,
        .back-button:hover {
            background-color: #007BFF;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h2>Add Student</h2>

    <!-- Form hides only if student added successfully -->
    <form method="POST" <?php if ($message === "Student added successfully!") echo 'style="display:none;"'; ?>>
        <label>College ID:</label>
        <input type="text" name="college_id" required />

        <label>Name:</label>
        <input type="text" name="name" required />

        <label>Password:</label>
        <input type="text" name="password" required />

        <label>House:</label>
        <select name="house" required>
            <option value="">--Select House--</option>
            <option value="Ignis">Ignis</option>
            <option value="Aer">Aer</option>
            <option value="Terra">Terra</option>
            <option value="Aqua">Aqua</option>
        </select>

        <button type="submit">Add Student</button>
    </form>

    <?php if ($message): ?>
        <p class="message <?php echo ($message !== "Student added successfully!") ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <!-- Always show the back link and button -->
    
    <button class="back-button" onclick="window.location.href='coordinator_dashboard.php'">
        Go back to Coordinator Dashboard
    </button>

</body>
</html>
