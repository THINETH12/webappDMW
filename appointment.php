<head>
    <link rel="stylesheet" href="./CSS/appointment.css">
</head>
<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$header_file = './header.php';
$db_connect_file = './db_connect.php';

if (!file_exists($header_file)) {
    die("Error: header.php not found at $header_file");
}
if (!file_exists($db_connect_file)) {
    die("Error: db_connect.php not found at $db_connect_file");
}

include $header_file;
include $db_connect_file;

// Check if session user_id is set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$user_email = '';

// Fetch the user's email from the database
if ($user_id) {
    $query = "SELECT email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "<!-- Debug: Failed to prepare statement: " . mysqli_error($conn) . " -->";
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $user_data = mysqli_fetch_assoc($result);
            $user_email = isset($user_data['email']) ? htmlspecialchars($user_data['email']) : '';
            echo "<!-- Debug: Fetched email = $user_email for user_id = $user_id -->";
        } else {
            echo "<!-- Debug: Failed to execute query: " . mysqli_stmt_error($stmt) . " -->";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<!-- Debug: No user_id in session -->";
}

echo "<!-- Session Data: role = " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set') . ", user_id = $user_id -->";
?>

<div class="page" id="appointment">
    <div class="content">
        <h2>Appointment Management</h2>

        <!-- Make Appointment Section -->
        <div class="appointment-section">
            <h3>Make an Appointment</h3>
            <?php if (!$user_id): ?>
                <p class="error-message">Please <a href="#" onclick="showLoginModal(event)">log in</a> to make an appointment.</p>
            <?php else: ?>
                <form id="appointmentForm" action="appointment-process.php" method="POST" onsubmit="return validateAppointmentForm(event)">
                    <div class="form-group">
                        <label for="patient-name">Patient Name:</label>
                        <input type="text" id="patient-name" name="patient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="doctor-name">Doctor Name:</label>
                        <select id="doctor-name" name="doctor_id" required onchange="updateAvailableDates()">
                            <option value="" disabled selected>Select a Doctor</option>
                            <?php
                            $doctors_query = "SELECT id, doctor_full_name AS name FROM doctors ORDER BY doctor_full_name";
                            $doctors_result = mysqli_query($conn, $doctors_query);
                            if ($doctors_result) {
                                while ($doctor = mysqli_fetch_assoc($doctors_result)) {
                                    echo "<option value='" . htmlspecialchars($doctor['id']) . "'>" . htmlspecialchars($doctor['name']) . "</option>";
                                }
                            } else {
                                echo "<!-- Debug: Failed to fetch doctors: " . mysqli_error($conn) . " -->";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="appointment-date">Available Dates:</label>
                        <div id="available-dates-container">
                            <!-- Checkboxes will be populated dynamically by JavaScript -->
                        </div>
                        <input type="hidden" id="appointment-date" name="appointment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Phone Number:</label>
                        <input type="text" id="phone-number" name="phone_number" pattern="[0-9]{10}" maxlength="10" required placeholder="Enter 10-digit number">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required placeholder="example@domain.com" value="<?php echo $user_email; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nic-number">NIC Number:</label>
                        <input type="text" id="nic-number" name="nic_number" required placeholder="e.g., 123456789V">
                    </div>
                    <button type="submit" class="btn">Book Appointment</button>
                </form>
            <?php endif; ?>
            <!-- Booking History -->
            <h3>Booking History</h3>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p class="error-message">Please <a href="#" onclick="showLoginModal(event)">log in</a> to view your booking history.</p>
            <?php else: ?>
                <div id="booking-history" class="booking-history">
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $history_query = "SELECT a.id, a.appointment_date, d.doctor_full_name AS doctor_name, a.phone_number, a.email, a.nic_number 
                                      FROM appointments a 
                                      JOIN doctors d ON a.doctor_id = d.id 
                                      WHERE a.user_id = ? ORDER BY a.appointment_date DESC";
                    $stmt = mysqli_prepare($conn, $history_query);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($appointment = mysqli_fetch_assoc($result)) {
                            echo "<div class='appointment-card'>";
                            echo "<p><strong>Doctor:</strong> " . htmlspecialchars($appointment['doctor_name']) . "</p>";
                            echo "<p><strong>Date:</strong> " . date('F j, Y', strtotime($appointment['appointment_date'])) . "</p>";
                            echo "<p><strong>Phone:</strong> " . htmlspecialchars($appointment['phone_number']) . "</p>";
                            echo "<p><strong>Email:</strong> " . htmlspecialchars($appointment['email']) . "</p>";
                            echo "<p><strong>NIC:</strong> " . htmlspecialchars($appointment['nic_number']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No booking history available.</p>";
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>
<?php if (isset($conn)) mysqli_close($conn); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    updateAvailableDates();
    validateSessionEmail();
});

function updateAvailableDates() {
    const doctorId = document.getElementById('doctor-name').value;
    const container = document.getElementById('available-dates-container');
    const dateInput = document.getElementById('appointment-date');

    if (!doctorId) {
        container.innerHTML = '';
        dateInput.value = '';
        return;
    }

    fetch(`get-available-dates.php?doctor_id=${doctorId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(dates => {
            container.innerHTML = ''; // Clear previous checkboxes
            dates.forEach(date => {
                const checkbox = document.createElement('input');
                checkbox.type = 'radio'; // Use radio for single selection
                checkbox.name = 'appointment_date_radio'; // Group radios
                checkbox.value = date;
                checkbox.id = `date_${date.replace(/-/g, '_')}`;
                const label = document.createElement('label');
                label.htmlFor = `date_${date.replace(/-/g, '_')}`;
                label.textContent = new Date(date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                const div = document.createElement('div');
                div.appendChild(checkbox);
                div.appendChild(label);
                container.appendChild(div);
            });

            // Add event listener to update hidden input when a radio is selected
            const radios = container.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.checked) {
                        dateInput.value = radio.value;
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error fetching available dates:', error);
            container.innerHTML = '<p>Error loading available dates. Check console.</p>';
        });
}

function validateSessionEmail() {
    const email = document.getElementById('email').value;
    if (email && (!email.includes('@') || !email.toLowerCase().endsWith('.com'))) {
        alert('Your session email is invalid (must contain @ and end with .com). Please enter a valid email.');
        document.getElementById('email').focus();
    }
}

function validateAppointmentForm(event) {
    event.preventDefault();
    const phone = document.getElementById('phone-number').value;
    const nic = document.getElementById('nic-number').value;
    const email = document.getElementById('email').value;
    const date = document.getElementById('appointment-date').value;

    if (!/^[0-9]{10}$/.test(phone)) {
        alert('Please enter a valid 10-digit phone number.');
        return false;
    }
    if (!/^[0-9]{9}[VvXx]$/.test(nic) && !/^[0-9]{12}$/.test(nic)) {
        alert('Please enter a valid NIC number (e.g., 123456789V or 123456789012).');
        return false;
    }
    
    if (!date) {
        alert('Please select an appointment date.');
        return false;
    }

    document.getElementById('appointmentForm').submit();
    return true;
}
</script>