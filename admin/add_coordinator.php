<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Add Coordinator
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_coordinator'])) {
    $coordinator_name = trim($_POST["coordinator_name"]);
    $password = trim($_POST["password"]);
    $hashed_password = $password; // store as plain text for now

    $check_sql = "SELECT * FROM coordinators WHERE coordinator_name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $coordinator_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Coordinator already exists.";
    } else {
        $sql = "INSERT INTO coordinators (coordinator_name, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $coordinator_name, $hashed_password);

        if ($stmt->execute()) {
            $message = "Coordinator added successfully!";
        } else {
            $message = "Error adding coordinator.";
        }
    }
}

// Delete Coordinator
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM coordinators WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Coordinator deleted successfully!";
    } else {
        $message = "Error deleting coordinator.";
    }
}

// Fetch all coordinators to display
$coordinators = [];
$result = $conn->query("SELECT id, coordinator_name FROM coordinators ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $coordinators[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coordinator Management</title>
    <style>
  /* Reset some default styles */
  * {
    box-sizing: border-box;
  }

  body {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 50px 15px;
    display: flex;
    justify-content: center;
    min-height: 100vh;
    color: #333;
  }

  .container {
    background-color: #fff;
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 650px;
    text-align: center;
    transition: box-shadow 0.3s ease;
  }
  .container:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
  }

  h2 {
    margin-bottom: 30px;
    font-weight: 700;
    font-size: 2.25rem;
    color: #222;
  }

  label {
    display: block;
    text-align: left;
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 1rem;
    color: #444;
  }

  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 25px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus, input[type="password"]:focus {
    outline: none;
    border-color: #2575fc;
    box-shadow: 0 0 8px rgba(37, 117, 252, 0.4);
  }

  input[type="submit"], button.toggle-btn {
    width: 100%;
    background-color: #2575fc;
    color: white;
    border: none;
    padding: 14px;
    font-size: 1.1rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 0.05em;
    transition: background-color 0.3s ease, transform 0.2s ease;
    user-select: none;
  }
  input[type="submit"]:hover, button.toggle-btn:hover {
    background-color: #1a4fcc;
    transform: translateY(-2px);
  }
  input[type="submit"]:active, button.toggle-btn:active {
    transform: translateY(0);
  }

  p.message {
    font-size: 1.1rem;
    margin-top: 15px;
    font-weight: 600;
    user-select: none;
  }
  p.success {
    color: #28a745;
  }
  p.error {
    color: #dc3545;
  }

  table {
    margin-top: 30px;
    width: 100%;
    border-collapse: collapse;
    font-size: 1rem;
  }
  th, td {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    text-align: left;
  }
  th {
    background-color: #f7f9fc;
    color: #555;
    font-weight: 600;
  }
  tbody tr:hover {
    background-color: #f0f4ff;
  }

  .delete-button {
    background-color: #dc3545;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
    display: inline-block;
  }
  .delete-button:hover {
    background-color: #b52a37;
  }

  .back-button {
    margin-top: 30px;
    display: inline-block;
    padding: 12px 26px;
    background-color: #6c757d;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }
  .back-button:hover {
    background-color: #565e64;
    transform: translateY(-2px);
  }

  /* Spacing below form for toggle button */
  #addCoordinatorSection {
    margin-bottom: 15px;
  }

  /* Responsive */
  @media (max-width: 480px) {
    .container {
      padding: 30px 25px;
    }
    input[type="submit"], button.toggle-btn {
      font-size: 1rem;
    }
    h2 {
      font-size: 1.8rem;
    }
  }
</style>

</head>
<body>

<div class="container">
    <h2>Coordinator Management</h2>

    <!-- Show message always here -->
    <?php if (!empty($message)): ?>
        <p class="message <?php echo ($message === 'Coordinator added successfully!' || $message === 'Coordinator deleted successfully!') ? 'success' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <!-- Add Coordinator Form -->
    <div id="addCoordinatorSection">
        <form method="post" action="">
            <input type="hidden" name="add_coordinator" value="1">
            <label for="coordinator_name">Coordinator Name:</label>
            <input type="text" name="coordinator_name" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Add Coordinator">
        </form>
    </div>

    <!-- Toggle button moved below the form -->
    <button class="toggle-btn" id="toggleViewBtn">Show Coordinators List</button>

    <!-- Coordinator List -->
    <div id="coordinatorListSection" style="display:none;">
        <?php if (count($coordinators) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Coordinator Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coordinators as $coord): ?>
                        <tr>
                            <td><?php echo $coord['id']; ?></td>
                            <td><?php echo htmlspecialchars($coord['coordinator_name']); ?></td>
                            <td>
                                <a href="?delete=<?php echo $coord['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this coordinator?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No coordinators found.</p>
        <?php endif; ?>
    </div>

    <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
</div>

<script>
    const toggleBtn = document.getElementById('toggleViewBtn');
    const addSection = document.getElementById('addCoordinatorSection');
    const listSection = document.getElementById('coordinatorListSection');

    toggleBtn.addEventListener('click', () => {
        if (addSection.style.display === 'none') {
            // Show Add Form, hide list
            addSection.style.display = 'block';
            listSection.style.display = 'none';
            toggleBtn.textContent = 'Show Coordinators List';
        } else {
            // Hide Add Form, show list
            addSection.style.display = 'none';
            listSection.style.display = 'block';
            toggleBtn.textContent = 'Add Coordinator';
        }
    });
</script>

</body>
</html>
