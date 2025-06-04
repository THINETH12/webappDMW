<?php
$host = 'localhost';
$user = 'root'; // Update with your MySQL username
$password = ''; // Update with your MySQL password
$database = 'zigma_hospital_db';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>