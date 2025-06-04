<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION["admin_name"] ?? "Admin";

// Database connection
include("../db_connect.php");

// Handle Clear Votes
if (isset($_POST['clear_votes'])) {
    // Clear all student votes (set has_voted to 0)
    $clear_query = "UPDATE students SET has_voted = 0 WHERE has_voted = 1";
    mysqli_query($conn, $clear_query);

    // Optionally, clear the votes table (if necessary to fully reset vote counts)
    $clear_votes_query = "DELETE FROM votes";
    mysqli_query($conn, $clear_votes_query);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle search
$search = "";
$search_query = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "AND (name LIKE '%$search%' OR college_id LIKE '%$search%' OR house LIKE '%$search%')";
}

$sql = "SELECT college_id, name, house FROM students WHERE has_voted = 1 $search_query";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students Who Have Voted</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            background: linear-gradient(270deg, #ff5f6d, #ffc371, #24c6dc, #5433ff, #20bdff);
            background-size: 1000% 1000%;
            animation: gradientBG 20s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            text-align: center;
            font-size: 3rem;
            color: #fff;
            margin: 40px 0 20px;
            text-shadow: 0 0 10px #fff, 0 0 20px #00f, 0 0 30px #0ff;
            animation: pulseGlow 4s ease-in-out infinite, zoomInOut 3s ease-in-out infinite;
        }

        @keyframes zoomInOut {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        @keyframes pulseGlow {
            0%, 100% { text-shadow: 0 0 10px #fff, 0 0 20px #00f, 0 0 30px #0ff; }
            50% { text-shadow: 0 0 20px #fff, 0 0 30px #0ff, 0 0 40px #0ff; }
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 18px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        input[type="submit"], button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #003d80;
        }

        table {
            width: 85%;
            margin: 0 auto 40px;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.2);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        th {
            background-color: rgba(0, 86, 179, 0.8);
            font-size: 22px;
        }

        td {
            font-size: 18px;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.2);
        }

        a.back-btn {
            display: block;
            width: 260px;
            margin: 20px auto 40px;
            padding: 12px;
            text-align: center;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 0 10px #00f, 0 0 20px #0ff;
        }

        a.back-btn:hover {
            background-color: #003d80;
        }
    </style>
</head>
<body>

    <h1>Students Who Have Voted</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name, college ID, or house" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <form method="POST" action="" style="text-align: center; margin-bottom: 20px;">
        <button type="submit" name="clear_votes" onclick="return confirm('Are you sure you want to clear all votes?');">Clear All Votes</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>College ID</th>
                <th>Name</th>
                <th>House</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['house']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

</body>
</html>
