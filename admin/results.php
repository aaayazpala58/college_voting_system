<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all candidates with their vote count
$sql = "SELECT c.candidate_id, c.college_id, s.name, c.position, c.house, c.photo, COUNT(v.vote_id) AS vote_count
        FROM candidates c
        LEFT JOIN votes v ON c.candidate_id = v.candidate_id
        LEFT JOIN students s ON c.college_id = s.college_id
        GROUP BY c.candidate_id, c.position, c.house
        ORDER BY c.house, c.position, vote_count DESC";

$result = mysqli_query($conn, $sql);

$candidates = [];

while ($row = mysqli_fetch_assoc($result)) {
    $candidates[$row['house']][$row['position']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated Background */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(255, 0, 150, 0.2) 0%, transparent 60%), 
                        radial-gradient(circle at 80% 80%, rgba(0, 128, 255, 0.2) 0%, transparent 60%),
                        radial-gradient(circle at 50% 50%, rgba(0, 255, 128, 0.2) 0%, transparent 60%);
            animation: animateBg 20s linear infinite;
            z-index: -1;
        }

        @keyframes animateBg {
            0% { background-position: 0% 0%, 100% 100%, 50% 50%; }
            50% { background-position: 50% 50%, 50% 50%, 0% 100%; }
            100% { background-position: 0% 0%, 100% 100%, 50% 50%; }
        }

        
        h2 {
            text-align: center;
            font-size: 50px;
            color: #fff;
            margin: 30px 0;
            animation: glow 2s ease-in-out infinite alternate, zoom 5s infinite;
            letter-spacing: 2px;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px #ff0080, 0 0 20px #ff0080, 0 0 30px #ff0080; }
            to { text-shadow: 0 0 20px #00ffff, 0 0 30px #00ffff, 0 0 40px #00ffff; }
        }

        @keyframes zoom {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .house-section {
            margin-bottom: 50px;
            text-align: center;
        }

        .house-title {
            background-color:rgb(23, 96, 165);
            color:rgb(241, 232, 236);
            padding: 10px;
            font-size: 35px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 50px;
        }

        .position-title {
            background-color:rgb(212, 75, 75);
            color: #fff;
            padding: 7px;
            margin-top: 20px;
            font-size: 25px;
            border-radius: 3px;
            text-align: center;
        }

        .candidate-card {
            display: inline-block;
            background: #fff;
            margin: 10px;
            padding: 15px;
            width: 200px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(153, 139, 234, 0.2);
            transition: 0.3s;
            vertical-align: top;
        }

        .candidate-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 15px rgba(209, 51, 127, 0.3);
        }

        .candidate-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .vote-count {
            font-weight: bold;
            color: green;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #222;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #555;
        }

    </style>
</head>
<body>

<h2>Election Results</h2>

<?php foreach ($candidates as $house => $positions) { ?>
    <div class="house-section">
        <div class="house-title"><?php echo htmlspecialchars($house); ?></div>

        <?php foreach ($positions as $position => $cands) { ?>
            <div class="position-title"><?php echo htmlspecialchars($position); ?></div>

            <?php foreach ($cands as $cand) { ?>
                <div class="candidate-card">
                    <img src="../uploads/<?php echo htmlspecialchars($cand['photo']); ?>" alt="Candidate Photo">
                    <div><strong><?php echo htmlspecialchars($cand['name']); ?></strong></div>
                    <div class="vote-count"><?php echo $cand['vote_count']; ?> Votes</div>
                </div>
            <?php } ?>
        <?php } ?>

    </div>
<?php } ?>

<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
