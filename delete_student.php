<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "9099";
$database = "sonamdb";

$conn = new mysqli($servername, $username, $password, $database);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("DELETE FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $message = "✅ Student with email '$email' deleted.";
        } else {
            $message = "❌ No student found with that email.";
        }
    } else {
        $message = "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Student</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
        }

        .btn:hover {
            background: #d32f2f;
        }

        .msg {
            margin-top: 10px;
            color: #333;
        }

        .back-link {
            margin-top: 15px;
            display: inline-block;
            color: #2196F3;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>❌ Delete Student</h2>

    <?php if (!empty($message)) echo "<div class='msg'>$message</div>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Student Email" required>
        <button type="submit" class="btn">Delete Student</button>
    </form>

    <a class="back-link" href="welcome.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
