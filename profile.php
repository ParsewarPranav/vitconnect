<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("Error: User not logged in.");
}

// Database connection
$host = 'localhost';
$dbname = 'connectu'; 
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Get the logged-in user's username from session
$username = $_SESSION['username']; 

// Fetch the student profile using username
$sql = "SELECT * FROM students WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Error: Student profile not found.");
}

// Fetch experience data
$experienceStmt = $pdo->prepare("SELECT * FROM experiences WHERE student_id = :student_id");
$experienceStmt->bindParam(':student_id', $student['id']); // Use the ID of the student from the fetched profile
$experienceStmt->execute();
$experienceData = $experienceStmt->fetchAll(PDO::FETCH_ASSOC);

// Education data
$education = [
    'title' => $student['education_title'] ?? 'N/A',
    'university' => $student['education_university'] ?? 'N/A',
    'duration' => $student['education_duration'] ?? 'N/A',
];

// Skills
$skills = explode(',', $student['skills'] ?? ''); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="profile.css">
    <style>
        body {
            position: relative; 
        }
        .sidebar {
            width: 250px; 
            transition: transform 0.3s ease; 
            background-color: #f8f9fa; 
            position: fixed;
            height: 100%; 
            z-index: 1; 
        }
        .sidebar.hidden {
            transform: translateX(-100%); 
        }
        .toggle-button {
            position: absolute;
            left: 0;
            top: 20px;
            cursor: pointer;
            background: #fff; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            padding: 10px; 
            z-index: 2; 
            margin-left: 270px;
        }
        .toggle-button:hover {
            background: #f0f0f0; 
        }
        .main-content {
            margin-left: 260px; 
            transition: margin-left 0.3s ease; 
        }
        .main-content.shifted {
            margin-left: 10px; 
        }
        .brand-title {
            color: #291ef7;
        }
        .profile-info img {
            width: 100px;
            height: 100px; 
            border-radius: 50%; 
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a class="navbar-brand d-flex align-items-center" href="student-index.html">
            <div class="logo d-flex align-items-center justify-content-center text-white">
                <span class="brand-title">VIT-Connect.in</span>
            </div>
        </a>
    </nav>
    <div class="profile-container">
        <aside class="sidebar">
            <div class="profile-info">
            <?php if (!empty($student['profile_pic'])): ?>
                    <img src="<?php echo htmlspecialchars($student['profile_pic']); ?>" alt="<?php echo htmlspecialchars($student['name']); ?>">
                <?php endif; ?>

                <p class="profile-name" id="profile-name"><?php echo htmlspecialchars($student['name']); ?></p>
                <p class="profile-title" id="profile-title"><?php echo htmlspecialchars($student['title']); ?></p>
            </div>
            <button class="toggle-button">&#8249;</button>
            <nav class="menu">
                 <a href="edit.php" class="menu-item">Edit Profile</a>
                 <a href="student-index.html" class="menu-item">Home</a>
                 <a href="student-login.php" class="menu-item">Log out</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="section">
                <div class="section-title" style="color: #0d00ff;">About</div>
                <p id="about-text"><?php echo htmlspecialchars($student['about']); ?></p>
            </div>
            <div class="section">
                <div class="section-title" style="color: #0d00ff;">Experience</div>
                <?php if (!empty($experienceData)): ?>
                    <?php foreach ($experienceData as $index => $experience): ?>
                        <div class="experience">
                            <div class="experience-title" id="experience-title-<?php echo $index + 1; ?>">
                                <?php echo htmlspecialchars($experience['experience_title']); ?>
                            </div>
                            <div class="experience-details" id="experience-details-<?php echo $index + 1; ?>">
                                <?php echo htmlspecialchars($experience['experience_details']); ?>
                            </div>
                            <p id="experience-description-<?php echo $index + 1; ?>">
                                <?php echo htmlspecialchars($experience['experience_description']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No experience listed.</p>
                <?php endif; ?>
            </div>
            <div class="section">
                <div class="section-title" style="color: #0d00ff;">Education</div>
                <div class="education">
                    <div class="education-title" id="education-title-display"><?php echo htmlspecialchars($education['title']); ?></div>
                    <div class="education-details" id="education-details-display"><?php echo htmlspecialchars($education['university']) . ', ' . htmlspecialchars($education['duration']); ?></div>
                </div>
            </div>
            <div class="section">
                <div class="section-title" style="color: #0d00ff;">Skills</div>
                <ul class="skills-list" id="skills-list">
                    <?php foreach ($skills as $skill): ?>
                        <li><?php echo htmlspecialchars(trim($skill)); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </main>
    </div>

    <script>
    $(document).ready(function() {
        $('.toggle-button').on('click', function() {
            $('.sidebar').toggleClass('hidden');
            $('.main-content').toggleClass('shifted');
        });
    });
    </script>

</body>
</html>
