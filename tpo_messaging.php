<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("Error: User not logged in.");
}

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

// Fetch the username from the query string
if (isset($_GET['username'])) {
    $studentUsername = $_GET['username'];
} else {
    die("Error: Username not provided.");
}

// Fetch the student details
$sql = "SELECT id, name FROM students WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $studentUsername);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Error: Student not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Messaging | ConnectU.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #0e043b;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .brand-title {
            color: blue;
        }
        .navbar .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: rgb(255, 139, 15);
            text-decoration: none;
        }
        .navbar-links a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
        }
        .navbar-links a:hover {
            color: #050505;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a class="navbar-brand d-flex align-items-center" href="profile1.php?username=<?php echo urlencode($studentUsername); ?>">
        <div class="logo d-flex align-items-center justify-content-center text-white">
            <span class="brand-title">VIT-Connect.in</span>
        </div>
    </a>
</nav>
<div class="container my-5">
    <h1>TPO Messaging</h1>
    <div class="section">
        <div class="section-title" style="color: #0d00ff;">Message to: <?php echo htmlspecialchars($student['name']); ?></div>
        <form id="messageForm" action="send_message.php" method="post">
            <!-- Hidden input to store student ID -->
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Enter your message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
