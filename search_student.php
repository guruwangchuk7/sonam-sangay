<?php
$servername = "localhost";
$username = "root";
$password = "9099";
$database = "sonamdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = trim($_POST["search"]);

    if (!empty($search)) {
        $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR course LIKE ?");
        $search_param = "%" . $search . "%";
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        input[type="text"] {
            padding: 12px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 0 5px 5px 0;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        td {
            background: #f9f9f9;
        }

        p.no-result {
            text-align: center;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📚 Search Student</h2>

    <form method="POST">
        <input type="text" name="search" placeholder="Enter name, email or course" required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($search_results)): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Course</th>
            </tr>
            <?php foreach ($search_results as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student["name"]) ?></td>
                    <td><?= htmlspecialchars($student["dob"]) ?></td>
                    <td><?= htmlspecialchars($student["gender"]) ?></td>
                    <td><?= htmlspecialchars($student["address"]) ?></td>
                    <td><?= htmlspecialchars($student["phone"]) ?></td>
                    <td><?= htmlspecialchars($student["email"]) ?></td>
                    <td><?= htmlspecialchars($student["course"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p class="no-result">No students found matching your search.</p>
    <?php endif; ?>
</div>
</body>
</html>
