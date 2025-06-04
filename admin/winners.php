<?php
session_start();
require '../db_connect.php';

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT c.position, c.house, c.name, c.photo, COUNT(v.vote_id) AS vote_count
          FROM candidates c
          JOIN votes v ON c.candidate_id = v.candidate_id
          GROUP BY c.position, c.house, c.candidate_id
          HAVING vote_count = (
              SELECT MAX(vc.vote_count)
              FROM (
                  SELECT v2.position, v2.house, v2.candidate_id, COUNT(v2.vote_id) AS vote_count
                  FROM votes v2
                  GROUP BY v2.position, v2.house, v2.candidate_id
              ) AS vc
              WHERE vc.position = c.position AND vc.house = c.house
          )
          ORDER BY c.house, c.position";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Winners Celebration</title>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        overflow-x: hidden;
        background: linear-gradient(-45deg, #ff512f, #dd2476,rgb(26, 89, 236),rgb(20, 5, 59));
        color: white;
        background-size: 400% 400%;
            animation: bgAnimation 15s ease infinite;
            
        }

        @keyframes bgAnimation {
            0%{background-position: 0 50%;}
            50%{background-position: 100% 50%;}
            100%{background-position: 0 50%;}
        }

    
    

    .confetti {
        position: fixed;
        width: 100vw;
        height: 100vh;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 999;
    }

    .container {
        padding: 40px 20px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    h1 {
        font-size: 3rem;
        color: #FFD700;
        text-shadow: 0 0 15px #FF4500;
        animation: glow 2s ease-in-out infinite alternate;
        -webkit-background-clip: text;
            animation: textMove 5s infinite alternate;
        }

        @keyframes textMove {
            0%{letter-spacing: 2px;}
            100%{letter-spacing: 8px;}
        
    }

    @keyframes glow {
        from { text-shadow: 0 0 10px #FF6347; }
        to { text-shadow: 0 0 20px #FF4500, 0 0 30px #FFA500; }
    }

    .congrats-line {
        font-size: 1.5rem;
        white-space: nowrap;
        overflow: hidden;
        width: 100%;
        margin: 20px 0;
        position: relative;
        height: 2.5rem;
    }

    .marquee {
        display: inline-block;
        position: absolute;
        white-space: nowrap;
        animation: scrollLeft 15s linear infinite;
    }

    @keyframes scrollLeft {
        from { transform: translateX(100%); }
        to { transform: translateX(-100%); }
    }

    table {
        width: 90%;
        margin: 60px auto;
        border-collapse: collapse;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 12px;
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
    }

    th {
        background: rgba(255,215,0,0.2);
        color: #FFD700;
    }

    td img {
        border-radius: 50%;
        border: 2px solid #fff;
    }

    a.back-btn {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 25px;
        background: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1rem;
        transition: background 0.3s;
    }

    a.back-btn:hover {
        background: #c0392b;
    }
</style>
</head>

<body onload="launchCelebration()">
<canvas class="confetti" id="confetti-canvas"></canvas>

<div class="container">
    <h1>🎊 Winners Announcement 🎉</h1>

    <?php
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['position'] === 'House Captain') {
            $name = htmlspecialchars($row['name']);
            $house = htmlspecialchars($row['house']);
            echo "<div class='congrats-line'>
                    <div class='marquee'>🎉 Congratulations House Captain $name - From $house House 🏆</div>
                  </div>";
        }
    }
    ?>

    <table>
        <tr>
            <th>Position</th>
            <th>House</th>
            <th>Name</th>
            <th>Photo</th>
            <th>Votes</th>
        </tr>
        <?php mysqli_data_seek($result, 0); while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['house']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?><?= $row['position'] == 'House Captain' ? ' 🏆' : '' ?></td>
            <td><img src="../uploads/<?= htmlspecialchars($row['photo']) ?>" width="60"></td>
            <td><?= $row['vote_count'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<script>
function launchCelebration() {
    const duration = 3 * 1000;
    const animationEnd = Date.now() + duration;
    const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 999 };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(() => {
        const timeLeft = animationEnd - Date.now();

        if (timeLeft <= 0) {
            return clearInterval(interval);
        }

        const particleCount = 50 * (timeLeft / duration);
        confetti(Object.assign({}, defaults, {
            particleCount,
            origin: {
                x: randomInRange(0.1, 0.9),
                y: Math.random() - 0.2
            }
        }));
    }, 250);
}
</script>

</body>
</html>
