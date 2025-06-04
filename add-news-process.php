<?php
session_start();

// // Check if user is admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     die("Access denied. Admins only.");
// }

// Include database connection
require_once './db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $news_event_name = mysqli_real_escape_string($conn, trim($_POST['news_event_name'] ?? ''));
    $news_content = mysqli_real_escape_string($conn, trim($_POST['news_content'] ?? ''));

    // Validation
    if (empty($news_event_name) || empty($news_content)) {
        die("Both News/Event Name and Content are required.");
    }

    // Prepare and bind
    $sql = "INSERT INTO news_events (news_event_name, news_content) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $news_event_name, $news_content);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<h2>News/Event Added Successfully</h2>";
            echo "<p>Name: " . htmlspecialchars($news_event_name) . "</p>";
            echo "<p>Content: " . htmlspecialchars($news_content) . "</p>";
            echo "<a href='index.php'>Back to Home</a>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>