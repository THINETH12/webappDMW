<?php
session_start();
include './db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    error_log('Unauthorized access attempt in delete-doctor-process.php');
    echo json_encode(['success' => false, 'message' => 'Access denied: Admin permission required']);
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('Invalid CSRF token in delete-doctor-process.php');
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id <= 0) {
        error_log('Invalid doctor ID in delete-doctor-process.php: ' . $id);
        echo json_encode(['success' => false, 'message' => 'Invalid doctor ID']);
        exit();
    }

    $sql = "DELETE FROM doctors WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            error_log('Failed to delete doctor in delete-doctor-process.php: ' . mysqli_error($conn));
            echo json_encode(['success' => false, 'message' => 'Failed to delete doctor: ' . mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log('Failed to prepare statement in delete-doctor-process.php: ' . mysqli_error($conn));
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . mysqli_error($conn)]);
    }
} else {
    error_log('Invalid request method in delete-doctor-process.php');
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

mysqli_close($conn);
?>