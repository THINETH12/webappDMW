<?php
session_start();
include './db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    error_log('Unauthorized access attempt in fetch-doctors.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$query = "SELECT id, doctor_full_name, doctor_category, doctor_email FROM doctors";
$result = mysqli_query($conn, $query);

$doctors = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = [
            'id' => $row['id'],
            'doctor_full_name' => $row['doctor_full_name'],
            'doctor_category' => $row['doctor_category'],
            'doctor_email' => $row['doctor_email']
        ];
    }
    echo json_encode($doctors);
} else {
    error_log('Database error in fetch-doctors.php: ' . mysqli_error($conn));
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>