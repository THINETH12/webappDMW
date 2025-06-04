<?php
session_start();
include './db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    error_log('Unauthorized access attempt in delete-news.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('Invalid CSRF token in delete-news.php');
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit();
}

if (!isset($_POST['id']) || !is_numeric($_POST['id']) || (int)$_POST['id'] <= 0) {
    error_log('Invalid or missing ID in delete-news.php: ' . ($_POST['id'] ?? 'not provided'));
    echo json_encode(['success' => false, 'message' => 'Invalid or missing news item ID']);
    exit();
}

$id = (int)$_POST['id'];
$query = "DELETE FROM news_events WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        error_log('Database error in delete-news.php: ' . mysqli_error($conn));
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt);
} else {
    error_log('Failed to prepare statement in delete-news.php: ' . mysqli_error($conn));
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>