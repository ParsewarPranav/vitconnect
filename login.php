<?php
session_start(); // Start the session

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data
    $stmt = $pdo->prepare("SELECT id FROM students WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // Use hashed passwords in production!
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, save session data
        $_SESSION['id'] = $user['id'];  // Save user ID in session
        $_SESSION['username'] = $_POST['username'];  // Save user ID in session
        $_SESSION['logged_in'] = true;       // Mark user as logged in

        // Check if profile exists
        $student_id = $user['id'];
        $profileStmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
        $profileStmt->bindParam(':id', $student_id);
        $profileStmt->execute();
        $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);

        if ($profile) {
            // Redirect to profile page if profile exists
            header("Location: profile.php?id=$student_id");
            exit();
        } else {
            // Redirect to profile creation page if no profile exists
            header("Location: create_profile.php?id=$student_id");
            exit();
        }
    } else {
        // User not found
        echo "Invalid username or password.";
    }
}
?>
