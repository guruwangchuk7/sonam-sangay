<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "9099";
$database = "sonamdb";
$conn = new mysqli($servername, $username, $password, $database);
$message = "";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] === "register") {
        $name = $_POST["name"];
        $gmail = $_POST["gmail"];
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, gmail, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $gmail, $pass);

        if ($stmt->execute()) {
            $message = "✅ Account created! Please log in.";
        } else {
            $message = "❌ Error: " . $conn->error;
        }

        $stmt->close();
    } elseif ($_POST["action"] === "login") {
        $gmail = $_POST["gmail"];
        $pass = $_POST["password"];

        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE gmail = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashed);
            $stmt->fetch();

            if (password_verify($pass, $hashed)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["name"] = $name;
                header("Location: welcome.php");
                exit;
            } else {
                $message = "❌ Incorrect password.";
            }
        } else {
            $message = "❌ No account found.";
        }

        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Management - Login/Register</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 30px 0 10px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            background: #f0f4f8;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }

        .form-container {
            width: 360px;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 15px;
        }

        .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .tabs button {
            width: 48%;
            background: #ddd;
            color: #333;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        .tabs .active {
            background: #4CAF50;
            color: white;
        }

        .form-group {
            display: none;
        }

        .form-group.active {
            display: block;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #45a049;
        }

        .message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    📚 <span>Student Management System</span>
</div>

<div class="main">
    <div class="form-container">
        <div class="tabs">
            <button id="loginTab" class="active" onclick="showForm('login')">Login</button>
            <button id="registerTab" onclick="showForm('register')">Register</button>
        </div>

        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

        <form method="POST" class="form-group active" id="loginForm">
            <input type="hidden" name="action" value="login">
            <input type="email" name="gmail" placeholder="Gmail" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <form method="POST" class="form-group" id="registerForm">
            <input type="hidden" name="action" value="register">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="gmail" placeholder="Gmail" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</div>

<script>
    function showForm(type) {
        document.getElementById('loginForm').classList.remove('active');
        document.getElementById('registerForm').classList.remove('active');
        document.getElementById('loginTab').classList.remove('active');
        document.getElementById('registerTab').classList.remove('active');

        if (type === 'login') {
            document.getElementById('loginForm').classList.add('active');
            document.getElementById('loginTab').classList.add('active');
        } else {
            document.getElementById('registerForm').classList.add('active');
            document.getElementById('registerTab').classList.add('active');
        }
    }
</script>

</body>
</html>
