<?php
$servername = "localhost";
$username = "root";
$password = "9099";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS sonamdb";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database 'sonamdb' created successfully or already exists.<br>";
} else {
    die("❌ Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db("sonamdb");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gmail VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'users' created successfully or already exists.<br>";
} else {
    echo "❌ Error creating 'users' table: " . $conn->error . "<br>";
}

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    dob DATE,
    gender VARCHAR(10),
    address VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(100) UNIQUE,
    course VARCHAR(100)
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Table 'students' created successfully or already exists.<br>";
} else {
    echo "❌ Error creating 'students' table: " . $conn->error . "<br>";
}

$conn->close();
?>
