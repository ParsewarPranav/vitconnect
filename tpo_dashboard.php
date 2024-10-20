<?php
// tpo_dashboard.php

include 'db_connection.php';

// Fetch all student profiles
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Dashboard</title>
    <link rel="stylesheet" href="styless.css"> <!-- Assuming CSS for card layout -->
</head>
<body>
    <h1>TPO Dashboard - Student Profiles</h1>
    <div class="student-cards">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="card">';
                echo '<img src="uploads/' . $row['profile_pic'] . '" alt="Profile Picture" class="profile-img">';
                echo '<h2>' . $row['name'] . '</h2>';
                echo '<p>Skills: ' . $row['skills'] . '</p>';
                echo '<a href="view_profile.php?id=' . $row['id'] . '" class="btn">See Profile</a>';
                echo '</div>';
            }
        } else {
            echo 'No student profiles found.';
        }
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
