<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = isset($_SESSION["admin_name"]) ? $_SESSION["admin_name"] : "Admin"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    overflow-x: hidden;
}

.background-image {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d');
    background-size: cover;
    background-position: center;
    filter: blur(4px);
    z-index: -2;
}

#particles-js {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.dashboard-container {
    max-width: 1000px;
    margin: 100px auto;
    text-align: center;
    color: #fff;
    position: relative;
    padding: 20px;
}

h2 {
    font-family: 'Pacifico', cursive;
    font-size: 3rem;
    margin-bottom: 60px;
    background: linear-gradient(90deg, blue, black);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.button-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 50px;
}

.dashboard-link {
    padding: 15px 30px;
    background-color: rgba(255, 255, 255, 0.2);
    color: black;
    text-decoration: none;
    font-weight: bold;
    font-size: 17px;
    border-radius: 30px;
    border: 2px solid #fff;
    transition: 0.3s;
    flex: 1 1 200px;
    max-width: 250px;
    box-sizing: border-box;
}

.dashboard-link:hover {
    background-color: #fff;
    color: #000;
}

.logout-button {
    display: inline-block;
    padding: 15px 40px;
    background-color: #ff4c4c;
    color: #fff;
    text-decoration: none;
    border-radius: 30px;
    font-weight: bold;
    border: none;
    transition: 0.3s;
    margin-top: 30px;
}

.logout-button:hover {
    background-color: #e60000;
}

.voted-button,
.not-voted-button {
    padding: 15px 30px;
    background-color: 
    color: black;
    text-decoration: none;
    font-weight: bold;
    border-radius: 30px;
    border: 2px solid #fff;
    transition: 0.3s;
}

.voted-button:hover,
.not-voted-button:hover {
    background-color:rgb(197, 221, 226);
    color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        margin: 50px 10px;
    }

    h2 {
        font-size: 2rem;
        margin-bottom: 40px;
    }

    .dashboard-link {
        font-size: 15px;
        padding: 12px 20px;
        flex: 1 1 100%;
    }

    .logout-button {
        width: 100%;
        padding: 15px 0;
    }
}
</style>
</head>

<body>

<div class="background-image"></div>
<div id="particles-js"></div>

<div class="dashboard-container">
    <h2>Welcome, <?php echo $admin_name; ?>!</h2>

    <div class="button-container">
        <a href="add_coordinator.php" class="dashboard-link">Add Coordinator</a>
        <a href="add_candidate.php" class="dashboard-link">Add Candidates</a>
        <a href="view_candidates.php" class="dashboard-link">View Candidates</a>
        <a href="view_students.php" class="dashboard-link">View Registered Students</a> 
        <a href="results.php" class="dashboard-link">View Results</a>
        <a href="winners.php" class="dashboard-link">Winner</a>
    </div>

    <!-- Add Buttons for Voted and Not Voted Students -->
    <div class="button-container">
        <a href="voted_students.php" class="dashboard-link">Students Who Have Voted</a>
        <a href="not_voted_students.php" class="dashboard-link">Students Who Haven't Voted</a>
    </div>

    <a href="admin_logout.php" class="logout-button">Logout</a>
</div>

<script>
particlesJS.load('particles-js', 'https://raw.githubusercontent.com/VincentGarreau/particles.js/master/demo/particles.json', function() {
  console.log('particles.js loaded');
});
</script>

</body>
</html>
