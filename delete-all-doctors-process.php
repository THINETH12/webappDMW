<?php
session_start();
include './db_connect.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $response['message'] = 'Unauthorized access.';
    error_log('Unauthorized access attempt in delete-all-doctors.php');
    echo json_encode($response);
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $response['message'] = 'Invalid CSRF token.';
    error_log('Invalid CSRF token in delete-all-doctors.php');
    echo json_encode($response);
    exit();
}

$query = "DELETE FROM doctors";
if (mysqli_query($conn, $query)) {
    $response['success'] = true;
} else {
    $response['message'] = 'Database error: ' . mysqli_error($conn);
    error_log('Database error in delete-all-doctors.php: ' . mysqli_error($conn));
}

echo json_encode($response);
mysqli_close($conn);
?>