<?php
session_start();
include './db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$query = "SELECT full_name, gender, address, dob, contact_number, email, medical_condition FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) != 1) {
    $_SESSION['error'] = "User not found.";
    header("Location: signup.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

// Handle form submission to update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $dob = mysqli_real_escape_string($conn, trim($_POST['dob']));
    $contact_number = mysqli_real_escape_string($conn, trim($_POST['contact_number']));
    $medical_condition = mysqli_real_escape_string($conn, trim($_POST['medical_condition']));

    // Validation
    if (empty($full_name) || empty($gender) || empty($address) || empty($dob) || empty($contact_number) || empty($medical_condition)) {
        $_SESSION['update_error'] = "All fields are required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $contact_number)) {
        $_SESSION['update_error'] = "Contact number must be exactly 10 digits.";
    } else {
        $update_query = "UPDATE users SET full_name = '$full_name', gender = '$gender', address = '$address', dob = '$dob', contact_number = '$contact_number', medical_condition = '$medical_condition' WHERE id = $user_id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['update_success'] = "Profile updated successfully.";
            header("Location: user-profile.php");
            exit();
        } else {
            $_SESSION['update_error'] = "Error updating profile: " . mysqli_error($conn);
        }
    }
}
?>

<?php include './header.php'; ?>

<div class="page" id="user-profile">
    <div class="content">
        <h2>User Profile</h2>

        <?php if (isset($_SESSION['update_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['update_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['update_error']; unset($_SESSION['update_error']); ?></p>
        <?php endif; ?>

        <div class="profile-info">
            <h3>Your Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Medical Condition:</strong> <?php echo htmlspecialchars($user['medical_condition']); ?></p>
        </div>

        <div class="profile-edit">
            <h3>Edit Your Details</h3>
            <form action="user-profile.php" method="POST">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>

                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>

                <label for="contact">Contact Number:</label>
                <input type="text" id="contact" name="contact_number" pattern="[0-9]{10}" maxlength="10" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required placeholder="Enter 10-digit mobile number">

                <label for="medical-condition">Medical Condition:</label>
                <textarea id="medical-condition" name="medical_condition" required><?php echo htmlspecialchars($user['medical_condition']); ?></textarea>

                <button type="submit" name="update">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>
<?php mysqli_close($conn); ?>