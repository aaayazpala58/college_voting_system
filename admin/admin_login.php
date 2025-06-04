<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user = trim($_POST["admin_user"]);
    $admin_pass = trim($_POST["admin_pass"]);

    if (($admin_user === "admin" && $admin_pass === "admin123") || ($admin_user === "admin1" && $admin_pass === "admin123")) {
        $_SESSION["admin_id"] = 1;
        $_SESSION["admin_username"] = $admin_user;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION["error"] = "Invalid username or password!";
        header("Location: admin_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>

<!-- Particle JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
}

#particles-js {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #0f2027;
    background: linear-gradient(to right, #2c5364, #203a43, #0f2027);
}

.login-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.15);
    padding: 50px;
    border-radius: 20px;
    text-align: center;
    color: white;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 20px rgba(0,0,0,0.6);
    width: 350px;
}

h1 {
    font-size: 45px;
    margin-bottom: 30px;
}

label {
    font-size: 20px;
    margin-top: 10px;
    display: block;
}

input {
    width: 80%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 8px;
    outline: none;
    font-size: 18px;
}

button {
    background: linear-gradient(90deg, #ff416c, #ff4b2b);
    border: none;
    padding: 12px 35px;
    border-radius: 50px;
    font-size: 20px;
    color: white;
    cursor: pointer;
    transition: 0.4s;
    margin-top: 20px;
}

button:hover {
    transform: scale(1.1);
    background: linear-gradient(90deg, #24c6dc, #514a9d);
}

.error {
    color: yellow;
    margin-top: 20px;
    font-size: 18px;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    background-color: #2c80b4;
    color: #fff;
    padding: 12px 25px;
    border-radius: 10px;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
}

.back-btn:hover {
    background-color: #3498db;
}
</style>
</head>

<body>

<div id="particles-js"></div>

<div class="login-container">
    <h1>Admin Login</h1>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="admin_user" required>

        <label>Password:</label>
        <input type="password" name="admin_pass" required>

        <button type="submit">Login</button>
    </form>

    <?php
    if (isset($_SESSION["error"])) {
        echo "<div class='error'>" . $_SESSION["error"] . "</div>";
        unset($_SESSION["error"]);
    }
    ?>

    <!-- Go Back to Homepage Button -->
    <a class="back-btn" href="../index.php">← Go Back to Homepage</a>

</div>

<script>
particlesJS('particles-js', {
    "particles": {
        "number": {
            "value": 80
        },
        "color": {
            "value": "#ffffff"
        },
        "shape": {
            "type": "circle"
        },
        "opacity": {
            "value": 0.5
        },
        "size": {
            "value": 3
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 2
        }
    }
});
</script>

</body>
</html>
