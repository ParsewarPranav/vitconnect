<?php
session_start();

// Database connection (modify the credentials as per your setup)
$host = 'localhost';
$dbname = 'connectu';
$dbusername = 'root';  // Your MySQL username
$dbpassword = '';      // Your MySQL password

// Connect to MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Form submission logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $about = $_POST['about'];
    $education_title = $_POST['education_title'];
    $education_university = $_POST['education_university'];
    $education_duration = $_POST['education_duration'];
    $skills = $_POST['skills'];

    // Handle profile picture upload
    $profile_pic = $_FILES['profile_pic'];
    $profilePicPath = '';

    if (!empty($profile_pic['name'])) {
        $profilePicPath = 'uploads/' . basename($profile_pic['name']); // Set the upload directory
        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($profile_pic['tmp_name'], $profilePicPath)) {
            echo "<div class='error'>Error uploading profile picture.</div>";
            $profilePicPath = ''; // Reset to empty if the upload fails
        }
    }

    // Get the current username from session
    $username = $_SESSION['username'];

    // Check if the profile already exists for this username
    $checkProfile = $pdo->prepare("SELECT * FROM students WHERE username = :username");
    $checkProfile->bindParam(':username', $username);
    $checkProfile->execute();

    if ($checkProfile->rowCount() > 0) {
        // Fetch existing profile data
        $existingProfile = $checkProfile->fetch(PDO::FETCH_ASSOC);
        
        // If no new profile picture is uploaded, keep the existing one
        if (empty($profilePicPath)) {
            $profilePicPath = $existingProfile['profile_pic'];
        }

        // Update existing profile
        $sql = "UPDATE students SET 
                name = :name,
                title = :title,
                about = :about,
                education_title = :education_title,
                education_university = :education_university,
                education_duration = :education_duration,
                skills = :skills,
                profile_pic = :profile_pic
                WHERE username = :username";
    } else {
        // Insert new profile
        $sql = "INSERT INTO students 
                (name, title, about, education_title, education_university, education_duration, skills, profile_pic, username)
                VALUES 
                (:name, :title, :about, :education_title, :education_university, :education_duration, :skills, :profile_pic, :username)";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':about', $about);
    $stmt->bindParam(':education_title', $education_title);
    $stmt->bindParam(':education_university', $education_university);
    $stmt->bindParam(':education_duration', $education_duration);
    $stmt->bindParam(':skills', $skills);
    $stmt->bindParam(':profile_pic', $profilePicPath);
    $stmt->bindParam(':username', $username);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "<div class='success'>Profile updated successfully!</div>";
    } else {
        echo "<div class='error'>Error updating profile.</div>";
    }

    // Handle multiple experiences
    $experience_titles = $_POST['experience_title'];
    $experience_details = $_POST['experience_details'];
    $experience_descriptions = $_POST['experience_description'];

    // Clear old experiences
    $deleteOldExperiences = $pdo->prepare("DELETE FROM experiences WHERE student_id = :student_id");
    $deleteOldExperiences->execute(['student_id' => $existingProfile['id']]);

    // Insert new experiences
    $insertExperience = $pdo->prepare("INSERT INTO experiences (student_id, experience_title, experience_details, experience_description) 
                                       VALUES (:student_id, :title, :details, :description)");

    foreach ($experience_titles as $index => $title) {
        $details = $experience_details[$index];
        $description = $experience_descriptions[$index];
        $insertExperience->execute([
            'student_id' => $existingProfile['id'],
            'title' => $title,
            'details' => $details,
            'description' => $description
        ]);
    }
}

// Fetch profile data (for form prefill)
$profileData = null;
$username = $_SESSION['username']; // Get username from session
$stmt = $pdo->prepare("SELECT * FROM students WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $profileData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch experiences data
$experienceData = [];
if ($profileData) {
    $experienceStmt = $pdo->prepare("SELECT * FROM experiences WHERE student_id = :student_id");
    $experienceStmt->bindParam(':student_id', $profileData['id']);
    $experienceStmt->execute();
    $experienceData = $experienceStmt->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Light background for contrast */
            color: #333; /* Dark text for readability */
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #100247;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .navbar-brand {
            font-size: 1.5em;
            color: white;
            text-decoration: none;
        }
        .navbar-brand:hover{
            color:blue;
        }

        .navbar-links {
            float: right;
        }

        .navbar-links a {
            margin-left: 20px;
            color: white;
            text-decoration: none;
        }

        .navbar-links a:hover {
           color:blue;
        }

        /* Main Content */
        .main-content {
            max-width: 800px; /* Centered container */
            margin: 20px auto; /* Centered with margin */
            padding: 20px;
            background-color: #fff; /* White background for forms */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow for depth */
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
        }

        label {
            margin-top: 10px; /* Space between labels and inputs */
            font-weight: bold; /* Bold labels for emphasis */
        }

        input[type="text"],
        input[type="file"],
        textarea {
            padding: 10px; /* Padding inside input elements */
            border: 1px solid #ccc; /* Light border */
            border-radius: 4px; /* Slightly rounded corners */
            margin-top: 5px; /* Space between label and input */
            font-size: 1em; /* Increase font size for inputs */
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #ff7f50; /* Light orange on focus */
            outline: none; /* Remove default outline */
        }

        textarea {
            resize: vertical; /* Allow vertical resizing only */
            height: 100px; /* Default height */
        }

        /* Experience Block Styles */
        .experience-block {
            margin-top: 20px; /* Space between experience blocks */
            padding: 10px;
            border: 1px solid #ccc; /* Light border around experience blocks */
            border-radius: 4px; /* Rounded corners */
        }
        .experience-block 
        input[type="text"],
        input[type="file"],
        textarea
        {
          
            padding: 10px; /* Padding inside input elements */
            border: 1px solid #ccc; /* Light border */
            border-radius: 4px; /* Slightly rounded corners */
            margin-top: 5px; /* Space between label and input */
            font-size: 1em;
            width: 100% !important;
        }

        #add-more-experience {
            margin-top: 15px; /* Space above button */
            color: white; /* White text */
            border: none; /* Remove border */
            padding: 10px 15px; /* Padding for button */
            border-radius: 4px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor for clickable button */
        }
        .error {
            color: red; /* Red text for errors */
            margin-top: 10px; /* Space above error messages */
        }

        .success {
            color: green; /* Green text for success messages */
            margin-top: 10px; /* Space above success messages */
        }
        .navbar-logo {  
    height: 100px; /* Adjust logo height */
    width: auto; /* Maintain aspect ratio */
}

/* Flexbox alignment for the logo and brand title */
.logo {
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
</head>
<body>

<nav class="navbar">
          <div class="logo d-flex align-items-center justify-content-center text-white">
                <img src="vitlogo2.png" alt="Logo" class="navbar-logo"> <!-- Add logo image here -->
                <span class="brand-title ms-2">VIT-Connect.in <br> Vishwakarma Institute Of Technology , Pune</span>
            </div>
        <div class="navbar-links">
            <a href="student-index.html">Home</a>
            <a href="view_profile.php">Profile</a>
            <a href="profile.php" class="menu-item">View Profile</a>
            <a href="#">Notifications</a>
        </div>
    </nav>

<div class="main-content">
    <h2>Edit Profile</h2>

    <!-- Profile update form -->
     <form method="POST" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($profileData['name']) ?>" required>
        
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($profileData['title']) ?>" required>
        
        <label for="about">About</label>
        <textarea id="about" name="about" required><?= htmlspecialchars($profileData['about']) ?></textarea>

        <label for="education_title">Education Title</label>
        <input type="text" id="education_title" name="education_title" value="<?= htmlspecialchars($profileData['education_title']) ?>" required>
        
        <label for="education_university">University</label>
        <input type="text" id="education_university" name="education_university" value="<?= htmlspecialchars($profileData['education_university']) ?>" required>
        
        <label for="education_duration">Duration</label>
        <input type="text" id="education_duration" name="education_duration" value="<?= htmlspecialchars($profileData['education_duration']) ?>" required>
        
        <label for="skills">Skills</label>
        <textarea id="skills" name="skills" required><?= htmlspecialchars($profileData['skills']) ?></textarea>
        
        <h3>Experience</h3>
        <div id="experience-container">
            <?php foreach ($experienceData as $experience): ?>
                <div class="experience-block">
                    <label for="experience_title[]">Experience Title</label><br>
                    <input type="text" name="experience_title[]" value="<?= htmlspecialchars($experience['experience_title']) ?>" required>
                    <br>
                    <label for="experience_details[]">Experience Details</label><br>
                    <input type="text" name="experience_details[]" value="<?= htmlspecialchars($experience['experience_details']) ?>" required>
                    <br>
                    <label for="experience_description[]">Experience Description</label><br>
                    <textarea name="experience_description[]" required><?= htmlspecialchars($experience['experience_description']) ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="add-more-experience" class="btn btn-primary">Add More Experience</button>
        <br><br>
        <label for="profile_pic">Profile Picture (optional)</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
        <input type="submit" value="Update Profile" class="btn btn-primary">
    </form>
</div>

<script>
    // Add more experience fields
    document.getElementById('add-more-experience').addEventListener('click', function() {
        const experienceContainer = document.getElementById('experience-container');

        const experienceBlock = document.createElement('div');
        experienceBlock.className = 'experience-block';
        experienceBlock.innerHTML = `
            <label for="experience_title[]">Experience Title</label>
            <input type="text" name="experience_title[]" required>
            
            <label for="experience_details[]">Experience Details</label>
            <input type="text" name="experience_details[]" required>
            
            <label for="experience_description[]">Experience Description</label>
            <textarea name="experience_description[]" required></textarea>
        `;
        
        experienceContainer.appendChild(experienceBlock);
    });
</script>

</body>
</html>
