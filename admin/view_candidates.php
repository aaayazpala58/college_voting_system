<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include '../db_connect.php';

// Handle delete all request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_all"])) {
    $delete_query = "DELETE FROM candidates";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('All candidates have been deleted successfully.'); window.location.href='view_candidates.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting candidates.');</script>";
    }
}

$query = "SELECT * FROM candidates";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Candidates</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #ff6b6b, #f5af19, #4facfe, #43e97b);
            padding: 40px 20px;
        }

        h2 {
            text-align: center;
            font-size: 55px;
            margin-bottom: 20px;
            background: linear-gradient(-45deg, rgb(97, 27, 13), rgb(238, 77, 150), rgb(120, 30, 199), rgb(12, 66, 132));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textMove 5s infinite alternate;
        }

        @keyframes textMove {
            0% { letter-spacing: 2px; }
            100% { letter-spacing: 8px; }
        }

        .candidate-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .candidate-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 25px 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .candidate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        .candidate-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #3498db;
            margin-bottom: 15px;
        }

        .candidate-card h3 {
            color: #34495e;
            margin-bottom: 10px;
        }

        .candidate-card p {
            font-size: 0.95em;
            color: #555;
            margin: 5px 0;
        }

        .back-btn {
            display: inline-block;
            margin: 30px auto 10px;
            background-color: #3498db;
            color: #fff;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #2c80b4;
        }

        .house-name {
            text-align: center;
            font-size: 40px;
            font-weight: bold;
            margin: 40px auto 20px;
            padding: 15px 25px;
            width: fit-content;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: #333;
            border: 3px solid #fff;
            transition: transform 0.3s ease;
        }

        .house-name:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #f5af19, #ff6b6b, #4facfe, #43e97b);
            color: white;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border: 3px solid #4facfe;
        }

        form button {
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #c0392b;
        }

        .bottom-controls {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<h2>Registered Candidates</h2>

<?php
$houses = ['Aer', 'Aqua', 'Ignis', 'Terra'];
$positions = [
    'House Captain',
    'Vice Captain',
    'Sports Captain',
    'Sports Vice Captain',
    'Cultural Captain',
    'Cultural Vice Captain',
    'Discipline Captain',
    'Discipline Vice Captain'
];

$candidates_by_house = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $house = $row["house"];
        $position = $row["position"];

        if (!isset($candidates_by_house[$house])) {
            $candidates_by_house[$house] = [];
        }
        $candidates_by_house[$house][$position][] = $row;
    }
}

foreach ($houses as $house) {
    if (isset($candidates_by_house[$house])) {
        echo "<div class='house-name'>$house</div>";
        echo "<div class='candidate-container'>";

        foreach ($positions as $position) {
            if (isset($candidates_by_house[$house][$position])) {
                foreach ($candidates_by_house[$house][$position] as $row) {
                    echo '<div class="candidate-card">';
                    echo '<img src="../uploads/' . htmlspecialchars($row["photo"]) . '" alt="Candidate Photo">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p><strong>Position:</strong> ' . htmlspecialchars($row["position"]) . '</p>';
                    echo '<p><strong>House:</strong> ' . htmlspecialchars($row["house"]) . '</p>';
                    echo '</div>';
                }
            }
        }

        echo "</div>";
    }
}
?>

<div class="bottom-controls">
    <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL candidates? This action cannot be undone.');">
        <button type="submit" name="delete_all">🗑️ Delete All Candidates</button>
    </form>
    <br>
    <a class="back-btn" href="admin_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
