<?php
session_start();
include './db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['login_error'] = 'Invalid request method.';
    header('Location: index.php');
    exit();
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email and password are required.';
    header('Location: index.php');
    exit();
}

$query = "SELECT id, password, role FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        header('Location: user-profile.php');
        exit();
    } else {
        $_SESSION['login_error'] = 'Invalid password.';
    }
} else {
    $_SESSION['login_error'] = 'Email not found.';
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
header('Location: index.php');
exit();
?>
<?php

