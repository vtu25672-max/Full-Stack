<?php
$servername = "localhost";
$username   = "root";          // default for Laragon & XAMPP
$password   = "";              // default empty
$dbname     = "student_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists (run once)
$conn->query("CREATE DATABASE IF NOT EXISTS student_db");
$conn->select_db("student_db");

// Create table if not exists
$conn->query("
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        dob DATE NOT NULL,
        department VARCHAR(50) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $dob        = $_POST['dob'];
    $department = $_POST['department'];
    $phone      = trim($_POST['phone']);

    // Basic server-side validation
    if (empty($name) || empty($email) || empty($dob) || empty($department) || empty($phone)) {
        header("Location: index.html?status=error&message=All fields are required");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?status=error&message=Invalid email format");
        exit;
    }

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO students (name, email, dob, department, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $dob, $department, $phone);

    if ($stmt->execute()) {
        header("Location: index.html?status=success");
    } else {
        // If duplicate email (UNIQUE constraint)
        if ($conn->errno == 1062) {
            header("Location: index.html?status=error&message=Email already registered");
        } else {
            header("Location: index.html?status=error&message=" . urlencode($stmt->error));
        }
    }

    $stmt->close();
}

$conn->close();
?>