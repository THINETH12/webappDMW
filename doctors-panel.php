<?php
include './header.php';
include './db_connect.php';

// Ensure the database is selected
mysqli_select_db($conn, 'zigma_hospital_db');

// Debug: Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to format availability into readable text
function formatAvailability($availabilityJson) {
    if (empty($availabilityJson)) {
        return "Not available";
    }

    // Decode the JSON string
    $availability = json_decode($availabilityJson, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Invalid availability data";
    }

    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $formatted = [];

    foreach ($days as $day) {
        if (isset($availability[$day]['active']) && $availability[$day]['active'] === "true") {
            $start = isset($availability[$day]['start']) ? $availability[$day]['start'] : null;
            $end = isset($availability[$day]['end']) ? $availability[$day]['end'] : null;

            if ($start && $end) {
                // Convert 24-hour format to 12-hour AM/PM format
                $startTime = DateTime::createFromFormat('H:i', $start);
                $endTime = DateTime::createFromFormat('H:i', $end);

                if ($startTime && $endTime) {
                    $formattedStart = $startTime->format('g:i A');
                    $formattedEnd = $endTime->format('g:i A');
                    $formatted[] = ucfirst($day) . " " . $formattedStart . " - " . $formattedEnd;
                }
            }
        }
    }

    return !empty($formatted) ? "Availability: " . implode(", ", $formatted) : "Not available";
}

// Fetch all doctors from the database
$doctors = [];
$query = "SELECT doctor_full_name AS name, doctor_category AS category, doctor_hospital AS hospital, doctor_job_title AS job_title, doctor_availability AS availability FROM doctors ORDER BY doctor_category, doctor_full_name";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[$row['category']][] = $row;
    }
} else {
    $error = "Error fetching doctors: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<div class="page" id="doctors-panel">
    <div class="content">
        <h2 class="panel-title">Doctors Panel</h2>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php else: ?>
            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" id="doctor-search" placeholder="Search by doctor name or category..." onkeyup="filterDoctors()">
                <span class="search-icon">🔍</span>
            </div>

            <div class="categories" id="doctors-container">
                <?php
                $categories = [
                    'Pediatrics', 'Obstetrics and Gynecology', 'Dermatology',
                    'Cardiology', 'Neurology', 'Psychiatry', 'Orthopedics',
                    'Ophthalmology', 'Otolaryngology (ENT)', 'Gastroenterology',
                    'Pulmonology', 'Nephrology', 'Hematology'
                ];

                foreach ($categories as $category) {
                    echo "<h3 class='category-title'>$category</h3>";
                    if (isset($doctors[$category]) && !empty($doctors[$category])) {
                        echo '<div class="doctor-cards" id="cards-' . strtolower(str_replace(' ', '-', $category)) . '">';
                        foreach ($doctors[$category] as $doctor) {
                            $doctorId = htmlspecialchars($doctor['name'] . '|' . $doctor['hospital']);
                            echo '<div class="doctor-card" data-name="' . strtolower(htmlspecialchars($doctor['name'])) . '" data-category="' . strtolower(htmlspecialchars($category)) . '">';
                            echo '<h4 class="doctor-name">' . htmlspecialchars($doctor['name']) . '</h4>';
                            echo '<p class="doctor-info"><strong>Hospital:</strong> ' . htmlspecialchars($doctor['hospital']) . '</p>';
                            echo '<p class="doctor-info"><strong>Job Title:</strong> ' . htmlspecialchars($doctor['job_title']) . '</p>';
                            echo '<p class="doctor-info"><strong>' . formatAvailability($doctor['availability']) . '</strong></p>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="no-doctors">No doctors found in this category.</p>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterDoctors() {
    const searchTerm = document.getElementById('doctor-search').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.doctor-card');

    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const category = card.getAttribute('data-category');
        const matches = name.includes(searchTerm) || category.includes(searchTerm);
        card.style.display = matches ? 'block' : 'none';
    });
}
</script>

<style>
/* Minimalistic Styles */
.content {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    background-image: url('../Images/BACKGROUND/5.jpg');
}

.panel-title {
    color: #333;
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
}

.error-message {
    color: #dc3545;
    text-align: center;
    font-weight: 500;
    padding: 10px;
    background: #f8d7da;
    border-radius: 5px;
}

/* Search Container */
.search-container {
    margin-bottom: 30px;
    text-align: center;
    position: relative;
}

#doctor-search {
    padding: 10px 40px 10px 15px;
    width: 100%;
    max-width: 400px;
    border: 1px solid #ced4da;
    border-radius: 20px;
    font-size: 16px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    outline: none;
    transition: box-shadow 0.3s ease;
}

#doctor-search:focus {
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    border-color: #007bff;
}

.search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #007bff;
    font-size: 16px;
}

/* Categories and Titles */
.category-title {
    color: #333;
    font-size: 22px;
    margin-top: 30px;
    margin-bottom: 10px;
    font-weight: 600;
    border-left: 4px solid #007bff;
    padding-left: 10px;
}

.doctor-cards {
    display: flex;
    flex-wrap: nowrap;
    gap: 20px;
    overflow-x: auto;
    padding-bottom: 10px;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.doctor-cards::-webkit-scrollbar {
    height: 8px;
}

.doctor-cards::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 4px;
}

.doctor-cards::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 4px;
}

.no-doctors {
    color: #6c757d;
    font-style: italic;
    margin-top: 10px;
    text-align: center;
}

/* Doctor Cards */
.doctor-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    width: 280px;
    flex: 0 0 auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: block;
}

.doctor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.doctor-name {
    color: #333;
    font-size: 20px;
    margin: 0 0 10px;
    font-weight: 600;
    text-align: center;
}

.doctor-info {
    color: #555;
    font-size: 15px;
    margin: 5px 0;
    line-height: 1.5;
}

.doctor-info strong {
    color: #333;
}

/* Responsive Design */
@media (max-width: 768px) {
    .content { padding: 15px; }
    .panel-title { font-size: 24px; }
    .category-title { font-size: 20px; }
    #doctor-search { max-width: 100%; }
    .doctor-card { width: 260px; }
}
</style>

<?php include './footer.php'; ?>