<?php
session_start();
include './header.php';
include './db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$query = "SELECT full_name, gender, address, dob, contact_number, email, medical_condition FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    $user = false;
    error_log("user-profile.php - Error preparing statement: " . mysqli_error($conn));
}
?>

<!-- Add the CSS link -->
<link rel="stylesheet" href="./CSS/user-profile.css">

<div class="page" id="user-profile">
    <div class="content user-profile-content">
        <h2>User Profile</h2>
        <?php if ($user): ?>
            <div class="user-info">
                <p><span class="label">Full Name:</span> <span class="value"><?php echo htmlspecialchars($user['full_name']); ?></span></p>
                <p><span class="label">Gender:</span> <span class="value"><?php echo htmlspecialchars($user['gender']); ?></span></p>
                <p><span class="label">Address:</span> <span class="value"><?php echo htmlspecialchars($user['address']); ?></span></p>
                <p><span class="label">Date of Birth:</span> <span class="value"><?php echo htmlspecialchars($user['dob']); ?></span></p>
                <p><span class="label">Contact Number:</span> <span class="value"><?php echo htmlspecialchars($user['contact_number']); ?></span></p>
                <p><span class="label">Email:</span> <span class="value"><?php echo htmlspecialchars($user['email']); ?></span></p>
                <p><span class="label">Special Medical Condition:</span> <span class="value"><?php echo htmlspecialchars($user['medical_condition']); ?></span></p>
            </div>
        <?php else: ?>
            <p class="error-message">Error loading profile. Please try again later.</p>
        <?php endif; ?>
    </div>
</div>

<?php include './footer.php'; ?>
<?php mysqli_close($conn); ?>