<?php
session_start();
include './db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    error_log('Unauthorized access attempt in delete-all-news.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('Invalid CSRF token in delete-all-news.php');
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit();
}

$query = "DELETE FROM news_events";
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    error_log('Database error in delete-all-news.php: ' . mysqli_error($conn));
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>