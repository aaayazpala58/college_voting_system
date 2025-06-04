<?php 
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include("../db_connect.php");

$search = "";
if(isset($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM students WHERE name LIKE '%$search%' OR college_id LIKE '%$search%'";
}else{
    $sql = "SELECT * FROM students";
}

$result = $conn->query($sql);

// Clear all students functionality
if (isset($_POST['clear_all'])) {
    // Disable foreign key checks temporarily to avoid constraint errors
    $conn->query("SET foreign_key_checks = 0");

    // Delete all students
    $clear_sql = "DELETE FROM students";
    if ($conn->query($clear_sql)) {
        echo "<script>alert('All students cleared successfully'); window.location.href='view_students.php';</script>";
    } else {
        echo "<script>alert('Error clearing students'); window.location.href='view_students.php';</script>";
    }

    // Re-enable foreign key checks
    $conn->query("SET foreign_key_checks = 1");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        /* Background Animation */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg, #ff512f, #dd2476, #00c6ff, #0072ff);
            background-size: 400% 400%;
            animation: bgAnimation 15s ease infinite;
            color: #fff;
        }

        @keyframes bgAnimation {
            0%{background-position: 0 50%;}
            50%{background-position: 100% 50%;}
            100%{background-position: 0 50%;}
        }

        h2 {
            text-align: center;
            font-size: 50px;
            margin-top: 30px;
            background: linear-gradient(-45deg,rgb(97, 27, 13),rgb(238, 77, 150),rgb(120, 30, 199),rgb(12, 66, 132));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textMove 5s infinite alternate;
        }

        @keyframes textMove {
            0%{letter-spacing: 2px;}
            100%{letter-spacing: 8px;}
        }

        .container {
            width: 90%;
            margin: 30px auto;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background: rgba(0,0,0,0.5);
            font-size: 20px;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.2);
            transition: 0.3s ease;
        }

        a {
            color:rgb(215, 20, 20);
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #ff0000;
        }

        .search-box {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            border: none;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background: #00dbde;
            border: none;
            color: #000;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>
<body>

<h2>Student List</h2>

<div class="container">

    <div class="search-box">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Search by Name or College ID" value="<?php echo $search; ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <form method="POST" action="">
        <button type="submit" name="clear_all" style="background-color: red; color: white; font-size: 18px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px;">
            Clear All Students
        </button>
        
    </form>
    <button onclick="window.location.href='admin_dashboard.php'" style="background-color:rgb(37, 204, 29); color: white; font-size: 18px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px; margin-left: 10px;">
  ← Back to Dashboard
</button>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>College ID</th>
            <th>Action</th>
        </tr>

        <?php
        $id = 1;
        while($row = $result->fetch_assoc()){
        ?>
        <tr>
            <td><?php echo $id++; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['college_id']; ?></td>
            <td><a href="delete_student.php?college_id=<?php echo $row['college_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
        <?php } ?>

    </table>
</div>


</body>
</html>
