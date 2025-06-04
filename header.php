<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zigma Hospital</title>
    <link rel="stylesheet" href="./CSS/Commonstyle.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/login.css">
    <?php
    if (basename($_SERVER['PHP_SELF']) == 'about.php') {
        echo '<link rel="stylesheet" href="css/about.css">';
    }
    if (basename($_SERVER['PHP_SELF']) == 'doctors-panel.php') {
        echo '<link rel="stylesheet" href="css/doctors-panel.css">';
    }
    if (basename($_SERVER['PHP_SELF']) == 'lab-services.php') {
        echo '<link rel="stylesheet" href="css/lab-services.css">';
    }
    if (basename($_SERVER['PHP_SELF']) == 'facilities.php') {
        echo '<link rel="stylesheet" href="css/facilities.css">';
    }
    if (basename($_SERVER['PHP_SELF']) == 'index.php') {
        echo '<link rel="stylesheet" href="css/index.css">';
    }
    if (basename($_SERVER['PHP_SELF']) == 'appointment.php') {
        echo '<link rel="stylesheet" href="css/appointment.css">';
    }
    ?>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Zigma Hospital</h1>
            </div>
            <div class="nav-container">
                <ul class="nav-links nav-links-left">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Services</a>
                        <div class="dropdown-content">
                            <a href="appointment.php">Appointment Management</a>
                            <a href="lab-services.php">Lab Service</a>
                            <a href="doctors-panel.php">Doctors Panel</a>
                        </div>
                    </li>
                    <li><a href="facilities.php">Facilities</a></li>
                    <li><a href="news.php">News & Events</a></li>
                </ul>
                <ul class="nav-links nav-links-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- User or Admin is logged in -->
                        <?php if ($_SESSION['role'] === 'user'): ?>
                            <!-- Show user-profile link for users only -->
                            <li><a href="user-profile.php">User Profile</a></li>
                        <?php endif; ?>
                        <!-- Show Logout link for both users and admins -->
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <!-- Not logged in: show Login and Sign Up -->
                        <li><a href="#" onclick="showLoginModal(event)">Login</a></li>
                        <li><a href="#" onclick="showRegisterModal(event)">Signup</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Login Modal -->
        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('loginModal')" aria-label="Close login modal">×</span>
                <h2>Login to Zigma Hospital</h2>
                <div id="login-error" class="error-message" style="display: none;"></div>
                <form id="loginForm" action="login-process.php" method="POST" onsubmit="return validateLoginForm(event)">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <p class="form-link">Don't have an account? <a href="#" onclick="showRegisterModal(event)">Sign Up</a></p>
                </form>
            </div>
        </div>

        <!-- Signup Modal -->
        <div id="registerModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('registerModal')" aria-label="Close register modal">×</span>
                <h2>Sign Up for Zigma Hospital</h2>
                <div id="register-error" class="error-message" style="display: none;"></div>
                <form id="registerForm" action="register-process.php" method="POST" onsubmit="return validateRegisterForm(event)">
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Enter your address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact_number" pattern="[0-9]{10}" maxlength="10" placeholder="Enter 10-digit phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <label for="medical-condition">Medical Condition</label>
                        <textarea id="medical-condition" name="medical_condition" placeholder="Describe any medical conditions" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                    <p class="form-link">Already have an account? <a href="#" onclick="showLoginModal(event)">Login</a></p>
                </form>
            </div>
        </div>
    </header>

    <script>
        function showLoginModal(event) {
            event.preventDefault();
            document.getElementById('loginModal').style.display = 'flex';
            document.getElementById('registerModal').style.display = 'none';
            document.getElementById('login-error').style.display = 'none';
        }

        function showRegisterModal(event) {
            event.preventDefault();
            document.getElementById('registerModal').style.display = 'flex';
            document.getElementById('loginModal').style.display = 'none';
            document.getElementById('register-error').style.display = 'none';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function validateLoginForm(event) {
            event.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const errorDiv = document.getElementById('login-error');

            if (!email.includes('@') || !email.includes('.')) {
                errorDiv.textContent = 'Please enter a valid email address.';
                errorDiv.style.display = 'block';
                return false;
            }

            if (password.length < 6) {
                errorDiv.textContent = 'Password must be at least 6 characters long.';
                errorDiv.style.display = 'block';
                return false;
            }

            document.getElementById('loginForm').submit();
            return true;
        }

        function validateRegisterForm(event) {
            event.preventDefault();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const contact = document.getElementById('contact').value;
            const email = document.getElementById('email').value;
            const errorDiv = document.getElementById('register-error');

            if (password !== confirmPassword) {
                errorDiv.textContent = 'Passwords do not match.';
                errorDiv.style.display = 'block';
                return false;
            }

            if (password.length < 6) {
                errorDiv.textContent = 'Password must be at least 6 characters long.';
                errorDiv.style.display = 'block';
                return false;
            }

            const contactPattern = /^[0-9]{10}$/;
            if (!contactPattern.test(contact)) {
                errorDiv.textContent = 'Please enter a valid 10-digit phone number.';
                errorDiv.style.display = 'block';
                return false;
            }

            if (!email.includes('@') || !email.includes('.')) {
                errorDiv.textContent = 'Please enter a valid email address.';
                errorDiv.style.display = 'block';
                return false;
            }

            document.getElementById('registerForm').submit();
            return true;
        }

        window.onclick = function(event) {
            const loginModal = document.getElementById('loginModal');
            const registerModal = document.getElementById('registerModal');
            if (event.target === loginModal) {
                loginModal.style.display = 'none';
            }
            if (event.target === registerModal) {
                registerModal.style.display = 'none';
            }
        };
    </script>