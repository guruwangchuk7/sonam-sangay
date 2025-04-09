<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Student Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .dashboard {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .dashboard h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 22px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            font-size: 16px;
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            border-radius: 8px;
            transition: background 0.3s ease;
            box-sizing: border-box;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .logout {
            background-color: #f44336;
        }

        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>📚 Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h2>

    <a href="add_student.php" class="btn">➕ Add Student</a>
    <a href="delete_student.php" class="btn">❌ Delete Student</a>
    <a href="search_student.php" class="btn">🔍 Search Student</a>
    <a href="logout.php" class="btn logout">🚪 Logout</a>
</div>

</body>
</html>
