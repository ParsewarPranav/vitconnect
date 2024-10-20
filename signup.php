<?php
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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before saving to the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert the username and hashed password
    $stmt = $pdo->prepare("INSERT INTO students (name, title, about, education_title, education_university, education_duration, skills, username, password) 
    VALUES (:name, :title, :about, :education_title, :education_university, :education_duration, :skills, :username, :password)");

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':about', $about);
    $stmt->bindParam(':education_title', $education_title);
    $stmt->bindParam(':education_university', $education_university);
    $stmt->bindParam(':education_duration', $education_duration);
    $stmt->bindParam(':skills', $skills);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful insert
        session_start();
        $_SESSION['username'] = $username; // Set session variable for the logged-in user
        header("Location: student-index.html"); // Redirect to student index page
        exit; // Always use exit after header redirection
    } else {
        // Error in inserting
        echo "Error: Could not register user. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Profile | ConnectU.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Create Your Profile</h1>
        <form method="post" action="signup.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="about" class="form-label">About</label>
                <textarea id="about" name="about" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="education_title" class="form-label">Education Title</label>
                <input type="text" id="education_title" name="education_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="education_university" class="form-label">University</label>
                <input type="text" id="education_university" name="education_university" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="education_duration" class="form-label">Duration</label>
                <input type="text" id="education_duration" name="education_duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="skills" class="form-label">Skills</label>
                <textarea id="skills" name="skills" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Profile</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
