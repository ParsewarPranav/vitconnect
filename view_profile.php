<?php
// Start the session to access session variables
session_start();

// Database connection
$host = 'localhost';
$dbname = 'connectu'; // Change to your database name
$dbusername = 'root';
$dbpassword = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get the logged-in username from the session

    // Fetch student profile data
    $sql = "SELECT * FROM students WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Error: Student profile not found.");
    }
} else {
    // If no username is found in the session, redirect to login or show an error
    die("Error: No username set. Please log in.");
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .profile-card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .profile-card img {
            width: 100px; /* Adjust size as needed */
            height: 100px; /* Adjust size as needed */
            border-radius: 50%; /* Circular image */
            object-fit: cover;
            margin-bottom: 15px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 50px;
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #100247; /* Dark background for navbar */
            padding: 10px 20px; /* Padding inside navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-logo {  
            height: 100px; /* Adjust logo height */
            width: auto; /* Maintain aspect ratio */
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-title {
            color: white;
            font-size: 1.5em;
            font-weight: bold;
            margin-left: 10px;
        }

        .navbar-links {
            display: flex;
            align-items: center;
        }

        .navbar-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1em;
            font-weight: 500;
            padding: 5px 10px;
            transition: color 0.3s ease;
        }

        .navbar-links a:hover {
            color: #ff7f50; /* Light orange hover effect */
        }

        /* Responsive Navbar */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            .navbar-links {
                flex-direction: column;
                width: 100%;
            }

            .navbar-links a {
                margin-left: 0;
                margin-bottom: 10px;
                width: 100%;
                text-align: left;
                padding-left: 0;
            }

            .brand-title {
             color: white;
              font-size: 1.5rem;
             width: 500px;
           }
        }

        /* Button Styling */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #100247;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 1em;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #ff7f50; /* Light orange on hover */
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo d-flex align-items-center">
        <img src="vitlogo2.png" alt="Logo" class="navbar-logo">
        <span class="brand-title">VIT-Connect.in <br> Vishwakarma Institute Of Technology, Pune</span>
    </div>
    <div class="navbar-links">
        <a href="student-index.html">Home</a>
        <a href="view_profile.php">Profile</a>
        <a href="profile.php">View Profile</a>
        <a href="#">Notifications</a>
    </div>
</nav>

<h1>Student Profile</h1>

<div class="profile-card">
    <?php if (!empty($student['profile_pic'])): ?>
        <img src="<?php echo htmlspecialchars($student['profile_pic']); ?>" alt="<?php echo htmlspecialchars($student['name']); ?>">
    <?php endif; ?>
    <h2><?php echo htmlspecialchars($student['name']); ?></h2>
    <p><strong>About:</strong> <?php echo htmlspecialchars($student['about']); ?></p>
    <p><strong>Skills:</strong> <?php echo htmlspecialchars($student['skills']); ?></p>
    <!-- Add the "See Profile" button -->
    <a href="profile.php" class="btn">See Profile</a>
</div>

</body>
</html>
