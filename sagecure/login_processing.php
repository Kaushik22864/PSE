<?php
session_start(); // Start a session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "sagecure_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form fields are set
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    die("Error: Invalid form submission.");
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Check if the email exists in the database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// If user is found
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify the hashed password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];  
        $_SESSION['fullname'] = $user['fullname'];

        // Redirect to the logged-in page
        header("Location: loggedInIndex.html");
        exit();
    } else {
        echo "<script>alert('Incorrect password. Please try again.'); window.location.href='login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Email not found. Please check and try again.'); window.location.href='login.html';</script>";
    exit();
}

// Close the connection
$stmt->close();
$conn->close();
?>
