<?php
session_start();
include './db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = 'Invalid request method.';
    header('Location: index.php');
    exit();
}

// Sanitize inputs manually or with appropriate methods
$full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$gender = htmlspecialchars(trim($_POST['gender'] ?? ''), ENT_QUOTES, 'UTF-8');
$address = htmlspecialchars(trim($_POST['address'] ?? ''), ENT_QUOTES, 'UTF-8');
$dob = trim($_POST['dob'] ?? ''); // Date, validate separately
$contact_number = preg_replace('/[^0-9]/', '', trim($_POST['contact_number'] ?? '')); // Remove non-digits
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$medical_condition = htmlspecialchars(trim($_POST['medical_condition'] ?? ''), ENT_QUOTES, 'UTF-8');

// Basic validation
if (empty($full_name) || empty($gender) || empty($address) || empty($dob) || empty($contact_number) || empty($email) || empty($password) || empty($medical_condition)) {
    $_SESSION['register_error'] = 'All fields are required.';
    header('Location: index.php');
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'Passwords do not match.';
    header('Location: index.php');
    exit();
}

if (!preg_match('/^[0-9]{10}$/', $contact_number)) {
    $_SESSION['register_error'] = 'Please enter a valid 10-digit phone number.';
    header('Location: index.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'Please enter a valid email address.';
    header('Location: index.php');
    exit();
}

if (strlen($password) < 6) {
    $_SESSION['register_error'] = 'Password must be at least 6 characters long.';
    header('Location: index.php');
    exit();
}

// Check if email already exists
$query = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $_SESSION['register_error'] = 'Email already registered.';
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: index.php');
    exit();
}
mysqli_stmt_close($stmt);

// Insert user into the database
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (full_name, gender, address, dob, contact_number, email, password, medical_condition) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ssssssss', $full_name, $gender, $address, $dob, $contact_number, $email, $password_hashed, $medical_condition);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['register_success'] = 'Registration successful. Please login.';
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: index.php');
    exit();
} else {
    $_SESSION['register_error'] = 'Registration failed. Please try again.';
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: index.php');
    exit();
}
?>