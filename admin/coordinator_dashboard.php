<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['coordinator_id'])) {
    header("Location: coordinator_login.php");
    exit();
}

$coordinator_name = $_SESSION['coordinator_name'] ?? 'Coordinator';

// Handle delete request (single student)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE college_id = ?");
    $stmt->bind_param("s", $delete_id);
    $stmt->execute();
    header("Location: coordinator_dashboard.php?show_students=1");
    exit();
}

// Handle delete all request
if (isset($_GET['delete_all']) && $_GET['delete_all'] == '1') {
    $conn->query("DELETE FROM students");
    header("Location: coordinator_dashboard.php");
    exit();
}

$show_students = isset($_GET['show_students']) && $_GET['show_students'] == '1';

$search_name = $_GET['search_name'] ?? '';
$search_id = $_GET['search_id'] ?? '';

$students = [];
if ($show_students) {
    $sql = "SELECT * FROM students WHERE 1=1";
    $params = [];
    $types = '';

    if ($search_name !== '') {
        $sql .= " AND name LIKE ?";
        $params[] = "%" . $search_name . "%";
        $types .= 's';
    }
    if ($search_id !== '') {
        $sql .= " AND college_id LIKE ?";
        $params[] = "%" . $search_id . "%";
        $types .= 's';
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coordinator Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background: #e0f7fa; /* Light blue background */
            border-radius: 10px;
            font-size: 18px; /* Increased font size */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
        }

        .top-links {
            margin-bottom: 25px;
            text-align: center;
        }

        .top-links a {
            margin: 0 20px;
            text-decoration: none;
            font-weight: bold;
            color: #007BFF;
            font-size: 18px;
        }

        .top-links a:hover {
            text-decoration: underline;
        }

        button, .btn {
            padding: 12px 24px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            border: none;
            background-color: #007BFF;
            color: white;
            transition: background-color 0.3s ease;
        }

        button:hover, .btn:hover {
            background-color: #0056b3;
        }

        form.search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        input[type="text"] {
            padding: 10px 14px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 220px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 16px;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-size: 18px;
        }

        .delete-button {
            background-color: #e74c3c;
            border: none;
            padding: 8px 16px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .danger-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size:13px;
            margin-left: 10px;
        }

        .danger-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($coordinator_name); ?>!</h2>

<div class="top-links">
    <a href="add_student_by_coordinator.php">Add Student</a> |
    <a href="coordinator_logout.php">Logout</a>
</div>

<?php if (!$show_students): ?>
    <div style="text-align:center;">
        <a href="coordinator_dashboard.php?show_students=1" class="btn">Show Registered Students</a>
    </div>
<?php else: ?>

    <form method="GET" class="search-form">
        <input type="hidden" name="show_students" value="1">
        <input type="text" name="search_name" placeholder="Search by Name" value="<?php echo htmlspecialchars($search_name); ?>">
        <input type="text" name="search_id" placeholder="Search by College ID" value="<?php echo htmlspecialchars($search_id); ?>">
        <button type="submit">Search</button>

        <!-- Delete All Students button -->
        <a href="coordinator_dashboard.php?show_students=1&delete_all=1"
           class="danger-btn"
           onclick="return confirm('Are you sure you want to delete ALL students? This action cannot be undone.')">
            Delete All Students
        </a>
    <a href="coordinator_dashboard.php" class="btn">Back</a>

    </form>

    <table>
        <thead>
        <tr>
            <th>College ID</th>
            <th>Name</th>
            <th>House</th>
            <th>Has Voted</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($students)): ?>
            <tr>
                <td colspan="5" style="text-align:center;">No students found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['house']); ?></td>
                    <td><?php echo $row['has_voted'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <form method="GET" onsubmit="return confirm('Are you sure you want to delete this student?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['college_id']); ?>">
                            <button type="submit" class="delete-button">Delete</button>
                            <input type="hidden" name="show_students" value="1">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: center;">
       
    </div>

<?php endif; ?>

</body>
</html>
