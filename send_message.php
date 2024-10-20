<?php
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
    $student_id = $_POST['student_id'];
    $message = $_POST['message'];
    $sql = "INSERT INTO messages (student_id, sender, message) VALUES (:student_id, 'TPO', :message)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':message', $message);
    
    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error sending message.";
    }
}
?>
