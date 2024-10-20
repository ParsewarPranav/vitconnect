<?php
// Database connection
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "user_profiles";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save profile data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $about = $_POST['about'];
    $experience1_title = $_POST['experience1'];
    $experience1_details = $_POST['experience-details1'];
    $experience1_description = $_POST['experience-description1'];
    $experience2_title = $_POST['experience2'];
    $experience2_details = $_POST['experience-details2'];
    $experience2_description = $_POST['experience-description2'];
    $education_title = $_POST['education-title'];
    $education_university = $_POST['education-university'];
    $education_duration = $_POST['education-duration'];
    $skills = $_POST['skills'];

    // Insert or update profile
    $sql = "INSERT INTO profiles (name, title, about, experience1_title, experience1_details, experience1_description,
            experience2_title, experience2_details, experience2_description, education_title, education_university, 
            education_duration, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=?, about=?, experience1_title=?, experience1_details=?, experience1_description=?,
            experience2_title=?, experience2_details=?, experience2_description=?, education_title=?, education_university=?,
            education_duration=?, skills=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssss",
        $name,
        $title,
        $about,
        $experience1_title,
        $experience1_details,
        $experience1_description,
        $experience2_title,
        $experience2_details,
        $experience2_description,
        $education_title,
        $education_university,
        $education_duration,
        $skills,
        $title,
        $about,
        $experience1_title,
        $experience1_details,
        $experience1_description,
        $experience2_title,
        $experience2_details,
        $experience2_description,
        $education_title,
        $education_university,
        $education_duration,
        $skills
    );

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: profile.php"); // Redirect to profile page after saving
    exit();
}
