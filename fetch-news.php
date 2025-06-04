<?php
session_start();
include './db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    error_log('Unauthorized access attempt in fetch-news.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$query = "SELECT id, news_event_name, news_content, created_at FROM news_events";
$result = mysqli_query($conn, $query);

$news = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $news[] = [
            'id' => (int)$row['id'], // Ensure ID is an integer
            'news_event_name' => $row['news_event_name'] ?? 'Untitled',
            'news_content' => $row['news_content'] ?? 'No content',
            'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }
    echo json_encode($news);
} else {
    error_log('Database error in fetch-news.php: ' . mysqli_error($conn));
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>