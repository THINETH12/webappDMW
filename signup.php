<?php
session_start();
include './header.php';
?>

<div class="modal" id="registerModal" style="display: flex;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">×</span>
        <h2>Register</h2>
        <?php if (isset($_SESSION['register_error'])): ?>
            <p class="error-message"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['register_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
        <?php endif; ?>
        <form id="registerForm" action="register-process.php" method="POST" onsubmit="return validateRegisterForm(event)">
            <input type="hidden" name="debug_submit" value="1">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full_name" required>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea>
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact_number" pattern="[0-9]{10}" maxlength="10" required placeholder="Enter 10-digit number">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="example@domain.com">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm_password" required>
            <label for="medical-condition">Medical Condition:</label>
            <textarea id="medical-condition" name="medical_condition" required></textarea>
            <button type="submit">Register</button>
            <p class="register-link">Already have an account? <a href="#" onclick="showLoginModal(event)">Login here</a></p>
        </form>
    </div>
</div>

<script src="script.js"></script>

<style>
.error-message { color: red; text-align: center; }
.success-message { color: green; test-align: center; }
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
.modal-content { background: white; padding: 20px; border-radius: 5px; width: 90%; max-width: 500px; }
.close { float: right; cursor: pointer; font-size: 24px; }
</style>

<?php include './footer.php'; ?>