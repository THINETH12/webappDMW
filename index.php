<?php
session_start();

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

// Generate CSRF token for POST requests
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

echo "<!-- Session Data: role = " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set') . " -->";
?>

<div class="page" id="home">
    <div class="content">
        <h2 style="font-size: 70px; text-align: center;">Welcome to Zigma Hospital</h2>
        <p style="justify-content: center; font-size: 30px; text-align: center;">Providing world-class healthcare services with compassion, 
            integrity, and a relentless commitment to excellence, because every patient deserves not just treatment,
            but genuine care, comfort, and hope on their journey to recovery. At Zigma hospital,
            we strive to blend advanced medical expertise with a personal, human touch, ensuring that every 
            individual feels seen, heard, and supported every step of the way toward a healthier tomorrow</p>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['register_success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['register_success']); unset($_SESSION['register_success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <!-- Show admin buttons only if the user is an admin -->
            <a href="#" class="fixed-add-doctor-btn admin-btn" onclick="showAddDoctorModal(event)">Add Doctors</a>
            <a href="#" class="fixed-add-news-btn admin-btn" onclick="showAddNewsModal(event)">Add News & Events</a>
            <a href="#" class="fixed-delete-news-btn admin-btn" onclick="showDeleteNewsModal(event)">Delete News & Events</a>
            <a href="#" class="fixed-delete-doctors-btn admin-btn" onclick="showDeleteDoctorsModal(event)">Delete Doctors</a>
        <?php endif; ?>

        <div id="addDoctorModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddDoctorModal()">×</span>
                <h2>Add New Doctor</h2>
                <form id="addDoctorForm" action="add-doctor-process.php" method="POST">
                    <label for="doctor-full-name">Full Name:</label>
                    <input type="text" id="doctor-full-name" name="doctor_full_name" required>

                    <label for="doctor-contact">Contact Number:</label>
                    <input type="text" id="doctor-contact" name="doctor_contact" pattern="[0-9]{10}" maxlength="10" required placeholder="Enter 10-digit number">

                    <label for="doctor-email">Email:</label>
                    <input type="email" id="doctor-email" name="doctor_email" required placeholder="example@domain.com">

                    <label for="doctor-hospital">Working Hospital:</label>
                    <input type="text" id="doctor-hospital" name="doctor_hospital" required>

                    <label for="doctor-job-title">Job Title:</label>
                    <input type="text" id="doctor-job-title" name="doctor_job_title" required>

                    <label>Availability (Select Weekdays with Time Range):</label>
                    <div class="checkbox-group">
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[monday][active]" value="true"> Monday</label>
                            <input type="time" name="doctor_availability[monday][start]" disabled>
                            <input type="time" name="doctor_availability[monday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[tuesday][active]" value="true"> Tuesday</label>
                            <input type="time" name="doctor_availability[tuesday][start]" disabled>
                            <input type="time" name="doctor_availability[tuesday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[wednesday][active]" value="true"> Wednesday</label>
                            <input type="time" name="doctor_availability[wednesday][start]" disabled>
                            <input type="time" name="doctor_availability[wednesday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[thursday][active]" value="true"> Thursday</label>
                            <input type="time" name="doctor_availability[thursday][start]" disabled>
                            <input type="time" name="doctor_availability[thursday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[friday][active]" value="true"> Friday</label>
                            <input type="time" name="doctor_availability[friday][start]" disabled>
                            <input type="time" name="doctor_availability[friday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[saturday][active]" value="true"> Saturday</label>
                            <input type="time" name="doctor_availability[saturday][start]" disabled>
                            <input type="time" name="doctor_availability[saturday][end]" disabled>
                        </div>
                        <div class="day-group">
                            <label><input type="checkbox" name="doctor_availability[sunday][active]" value="true"> Sunday</label>
                            <input type="time" name="doctor_availability[sunday][start]" disabled>
                            <input type="time" name="doctor_availability[sunday][end]" disabled>
                        </div>
                    </div>

                    <label for="doctor-category">Doctor Category:</label>
                    <select id="doctor-category" name="doctor_category" required>
                        <option value="">Select Category</option>
                        <option value="Pediatrics">Pediatrics</option>
                        <option value="Obstetrics and Gynecology">Obstetrics and Gynecology</option>
                        <option value="Dermatology">Dermatology</option>
                        <option value="Cardiology">Cardiology</option>
                        <option value="Neurology">Neurology</option>
                        <option value="Psychiatry">Psychiatry</option>
                        <option value="Orthopedics">Orthopedics</option>
                        <option value="Ophthalmology">Ophthalmology</option>
                        <option value="Otolaryngology (ENT)">Otolaryngology (ENT)</option>
                        <option value="Gastroenterology">Gastroenterology</option>
                        <option value="Pulmonology">Pulmonology</option>
                        <option value="Nephrology">Nephrology</option>
                        <option value="Hematology">Hematology</option>
                    </select>

                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit">Add Doctor</button>
                </form>
            </div>
        </div>

        <div id="addNewsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddNewsModal()">×</span>
                <h3>Add News & Events</h3>
                <form id="addNewsForm" action="add-news-process.php" method="POST">
                    <label for="news-event-name">News/Event Name:</label>
                    <input type="text" id="news-event-name" name="news_event_name" required>

                    <label for="news-content">News Content:</label>
                    <textarea id="news-content" name="news_content" rows="5" required></textarea>

                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit">Add News/Event</button>
                </form>
            </div>
        </div>

        <div id="deleteNewsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeDeleteNewsModal()">×</span>
                <h3>Delete News & Events</h3>
                <button class="delete-all-btn" onclick="deleteAllNewsItems()">Delete All News</button>
                <div id="newsList" class="items-list"></div>
            </div>
        </div>

        <div id="deleteDoctorsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeDeleteDoctorsModal()">×</span>
                <h3>Delete Doctors</h3>
                <button class="delete-all-btn" onclick="deleteAllDoctorsItems()">Delete All Doctors</button>
                <div id="doctorsList" class="items-list"></div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>
<?php if (isset($conn)) mysqli_close($conn); ?>

<script>
function showAddDoctorModal(event) {
    event.preventDefault();
    console.log('Opening Add Doctor Modal');
    document.getElementById('addDoctorModal').style.display = 'flex';
    updateTimeInputs();
}

function showAddNewsModal(event) {
    event.preventDefault();
    console.log('Opening Add News Modal');
    document.getElementById('addNewsModal').style.display = 'flex';
}

function showDeleteNewsModal(event) {
    event.preventDefault();
    console.log('Opening Delete News Modal');
    const modal = document.getElementById('deleteNewsModal');
    if (!modal) {
        console.error('Delete News Modal not found in DOM');
        alert('Error: Delete News Modal not found.');
        return;
    }
    modal.style.display = 'flex';
    fetchNewsItems();
}

function showDeleteDoctorsModal(event) {
    event.preventDefault();
    console.log('Opening Delete Doctors Modal');
    const modal = document.getElementById('deleteDoctorsModal');
    if (!modal) {
        console.error('Delete Doctors Modal not found in DOM');
        alert('Error: Delete Doctors Modal not found.');
        return;
    }
    modal.style.display = 'flex';
    fetchDoctorsItems();
}

function closeAddDoctorModal() {
    document.getElementById('addDoctorModal').style.display = 'none';
}

function closeAddNewsModal() {
    document.getElementById('addNewsModal').style.display = 'none';
}

function closeDeleteNewsModal() {
    document.getElementById('deleteNewsModal').style.display = 'none';
}

function closeDeleteDoctorsModal() {
    document.getElementById('deleteDoctorsModal').style.display = 'none';
}

window.onclick = function(event) {
    const doctorModal = document.getElementById('addDoctorModal');
    const newsModal = document.getElementById('addNewsModal');
    const deleteNewsModal = document.getElementById('deleteNewsModal');
    const deleteDoctorsModal = document.getElementById('deleteDoctorsModal');
    if (event.target == doctorModal) closeAddDoctorModal();
    if (event.target == newsModal) closeAddNewsModal();
    if (event.target == deleteNewsModal) closeDeleteNewsModal();
    if (event.target == deleteDoctorsModal) closeDeleteDoctorsModal();
}

function updateTimeInputs() {
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    days.forEach(day => {
        const checkbox = document.querySelector(`input[name="doctor_availability[${day}][active]"]`);
        const startTime = document.querySelector(`input[name="doctor_availability[${day}][start]"]`);
        const endTime = document.querySelector(`input[name="doctor_availability[${day}][end]"]`);
        if (checkbox && startTime && endTime) {
            const enabled = checkbox.checked;
            startTime.disabled = !enabled;
            endTime.disabled = !enabled;
            if (!enabled) {
                startTime.value = '';
                endTime.value = '';
            }
        }
    });
}

function fetchNewsItems() {
    console.log('Fetching news items from fetch-news.php');
    fetch('fetch-news.php')
        .then(response => {
            console.log('Fetch news response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('News data received:', data);
            const newsList = document.getElementById('newsList');
            if (!newsList) {
                console.error('News list element not found in DOM');
                alert('Error: News list element not found.');
                return;
            }
            newsList.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                newsList.innerHTML = '<p class="no-items">No news items found.</p>';
                return;
            }
            data.forEach(item => {
                if (!item.id) {
                    console.warn('News item missing ID:', item);
                    return;
                }
                const itemCard = document.createElement('div');
                itemCard.className = 'item-card';
                itemCard.innerHTML = `
                    <h4 class="item-title">${item.news_event_name || 'Untitled'}</h4>
                    <p class="item-content">${item.news_content || 'No content'}</p>
                    <p class="item-date">${item.created_at ? new Date(item.created_at).toLocaleString() : 'No date'}</p>
                    <button class="delete-btn" onclick="deleteNewsItem(${item.id})">Delete</button>
                `;
                newsList.appendChild(itemCard);
            });
        })
        .catch(error => {
            console.error('Error fetching news items:', error);
            const newsList = document.getElementById('newsList');
            if (newsList) {
                newsList.innerHTML = '<p class="no-items">Error fetching news items: ' + error.message + '</p>';
            }
            alert('Error fetching news items. Check console for details.');
        });
}

function fetchDoctorsItems() {
    console.log('Fetching doctors from fetch-doctors.php');
    fetch('fetch-doctors.php')
        .then(response => {
            console.log('Fetch doctors response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Doctors data received:', data);
            const doctorsList = document.getElementById('doctorsList');
            if (!doctorsList) {
                console.error('Doctors list element not found in DOM');
                alert('Error: Doctors list element not found.');
                return;
            }
            doctorsList.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                doctorsList.innerHTML = '<p class="no-items">No doctors found.</p>';
                return;
            }
            data.forEach(item => {
                const fullName = item.doctor_full_name || item.full_name || 'Unknown Name';
                const category = item.doctor_category || item.category || 'Unknown Category';
                const email = item.doctor_email || item.email || 'Unknown Email';
                const id = item.id || '';
                if (!id) {
                    console.warn('Doctor item missing ID:', item);
                    return;
                }
                const itemCard = document.createElement('div');
                itemCard.className = 'item-card';
                itemCard.innerHTML = `
                    <h4 class="item-title">${fullName}</h4>
                    <p class="item-content">Category: ${category}</p>
                    <p class="item-content">Email: ${email}</p>
                    <button class="delete-btn" onclick="deleteDoctorItem(${id})">Delete</button>
                `;
                doctorsList.appendChild(itemCard);
            });
        })
        .catch(error => {
            console.error('Error fetching doctors:', error);
            const doctorsList = document.getElementById('doctorsList');
            if (doctorsList) {
                doctorsList.innerHTML = '<p class="no-items">Error fetching doctors: ' + error.message + '</p>';
            }
            alert('Error fetching doctors. Check console for details.');
        });
}

function deleteNewsItem(id) {
    if (!confirm('Are you sure you want to delete this news item?')) return;

    console.log('Deleting news item with ID:', id);
    const csrfToken = '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>';
    fetch('delete-news.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&csrf_token=${encodeURIComponent(csrfToken)}`
    })
        .then(response => {
            console.log('Delete news response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete news response data:', data);
            if (data.success) {
                fetchNewsItems();
                alert('News item deleted successfully.');
            } else {
                console.error('Delete news failed:', data.message);
                alert('Error deleting news item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting news item:', error);
            alert('Error deleting news item: ' + error.message);
        });
}

function deleteAllNewsItems() {
    if (!confirm('Are you sure you want to delete all news items?')) return;

    console.log('Deleting all news items');
    const csrfToken = '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>';
    fetch('delete-all-news.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `csrf_token=${encodeURIComponent(csrfToken)}`
    })
        .then(response => {
            console.log('Delete all news response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete all news response data:', data);
            if (data.success) {
                fetchNewsItems();
                alert('All news items deleted successfully.');
            } else {
                console.error('Delete all news failed:', data.message);
                alert('Error deleting all news items: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting all news items:', error);
            alert('Error deleting all news items: ' + error.message);
        });
}

function deleteDoctorItem(id) {
    if (!confirm('Are you sure you want to delete this doctor?')) return;

    console.log('Deleting doctor with ID:', id);
    const csrfToken = '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>';
    fetch('delete-doctor-process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&csrf_token=${encodeURIComponent(csrfToken)}`
    })
        .then(response => {
            console.log('Delete doctor response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete doctor response data:', data);
            if (data.success) {
                fetchDoctorsItems();
                alert('Doctor deleted successfully.');
            } else {
                console.error('Delete doctor failed:', data.message);
                alert('Error deleting doctor: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting doctor:', error);
            alert('Error deleting doctor: ' + error.message);
        });
}

function deleteAllDoctorsItems() {
    if (!confirm('Are you sure you want to delete all doctors?')) return;

    console.log('Deleting all doctors');
    const csrfToken = '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>';
    fetch('delete-all-doctors.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `csrf_token=${encodeURIComponent(csrfToken)}`
    })
        .then(response => {
            console.log('Delete all doctors response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete all doctors response data:', data);
            if (data.success) {
                fetchDoctorsItems();
                alert('All doctors deleted successfully.');
            } else {
                console.error('Delete all doctors failed:', data.message);
                alert('Error deleting all doctors: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting all doctors:', error);
            alert('Error deleting all doctors: ' + error.message);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, binding event listeners');
    const checkboxes = document.querySelectorAll('input[name^="doctor_availability["][type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTimeInputs);
    });

    // Verify button event listeners
    const deleteNewsBtn = document.querySelector('.fixed-delete-news-btn');
    const deleteDoctorsBtn = document.querySelector('.fixed-delete-doctors-btn');
    if (deleteNewsBtn) {
        deleteNewsBtn.addEventListener('click', showDeleteNewsModal);
        console.log('Delete News button listener attached');
    } else {
        console.error('Delete News button not found in DOM');
    }
    if (deleteDoctorsBtn) {
        deleteDoctorsBtn.addEventListener('click', showDeleteDoctorsModal);
        console.log('Delete Doctors button listener attached');
    } else {
        console.error('Delete Doctors button not found in DOM');
    }
});
</script>