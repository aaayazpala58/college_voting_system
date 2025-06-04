<?php
session_start();
include '../db_connect.php'; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coordinator_name = $_POST['coordinator_name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM coordinators WHERE coordinator_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $coordinator_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['password'] === $password) {
            $_SESSION['coordinator_id'] = $row['id'];
            $_SESSION['coordinator_name'] = $row['coordinator_name'];
            header("Location: coordinator_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Coordinator not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coordinator Login</title>
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            color: #333;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 6px;
            font-weight: 600;
            color: #444;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1.8px solid #ccc;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2575fc;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px 0;
            font-size: 1.1rem;
            font-weight: 700;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: white;
            transition: background 0.4s ease;
            margin-bottom: 15px;
        }
        button:hover {
            background: linear-gradient(45deg, #2575fc, #6a11cb);
        }
        .error-message {
            color: #d93025;
            margin-top: 10px;
            font-weight: 600;
        }
        .back-button {
            background: #f0f0f0;
            color: #333;
            font-weight: 600;
            border: 2px solid #ccc;
            transition: all 0.3s ease;
        }
        .back-button:hover {
            background: #2575fc;
            color: white;
            border-color: #2575fc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Coordinator Login</h2>
        <form method="POST">
            <label for="coordinator_name">Coordinator Name:</label>
            <input type="text" id="coordinator_name" name="coordinator_name" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Login</button>
        </form>

        <form action="../index.php" method="get">
            <button type="submit" class="back-button">Back to Home</button>
        </form>

        <?php if($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
