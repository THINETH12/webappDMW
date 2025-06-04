<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);
    $query = "SELECT doctor_availability FROM doctors WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $availability = json_decode($row['doctor_availability'], true);
        $availableDays = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            if (isset($availability[$day]['active']) && $availability[$day]['active']) {
                $availableDays[] = ucfirst($day); // e.g., "Monday"
            }
        }

        $availableDates = [];
        $startDate = new DateTime('2025-06-02'); // Start from today
        $endDate = new DateTime('2025-12-31'); // End of 2025

        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dayName = strtolower($currentDate->format('l')); // Full day name (e.g., "monday")
            if (in_array(ucfirst($dayName), $availableDays)) {
                $availableDates[] = $currentDate->format('Y-m-d');
            }
            $currentDate->modify('+1 day');
        }

        echo json_encode($availableDates);
    } else {
        echo json_encode([]); // No availability data
    }
} else {
    echo json_encode([]); // Invalid request
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>