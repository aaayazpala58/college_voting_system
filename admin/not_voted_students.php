<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = isset($_SESSION["admin_name"]) ? $_SESSION["admin_name"] : "Admin";

// Database connection
include("../db_connect.php");

// Handle search
$search = ""; // Default is empty string
$search_query = ""; // Default is empty string for search query

// Check if search is set and not empty
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    // Add search query for name, college_id, and house
    $search_query = "AND (name LIKE '%$search%' OR college_id LIKE '%$search%' OR house LIKE '%$search%')";
}

// Query to get students who haven't voted with search functionality
$sql = "SELECT college_id, name, house FROM students WHERE has_voted = 0 $search_query";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Who Haven't Voted</title>
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
    position: relative;
    animation: pulseGlow 4s ease-in-out infinite;
    animation: zoomInOut 3s ease-in-out infinite;
}
@keyframes zoomInOut {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
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

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #003d80;
        }

        table {
            width: 85%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgb(80, 146, 92);
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0056b3;
            color: white;
            font-size: 28px;
        }

        td {
            background-color: rgb(129, 205, 220);
        }

        tr:hover {
            background-color: #e0f7fa;
        }

        a {
            display: block;
            width: 250px;
            margin: 40px auto;
            padding: 12px;
            text-align: center;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 26px;
            font-weight: bold;
        }

        a:hover {
            background-color: #003d80;
        }
    </style>
</head>
<body>

    <h1>Students Who Haven't Voted</h1>

    <!-- Search Bar -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name, college ID, or house" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Table to display students -->
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
                    <td colspan="3">No students found who haven't voted.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php">← Back to Dashboard</a>

</body>
</html>
