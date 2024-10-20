<?php
session_start();

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

// Fetch the student_id based on the logged-in user's username from the session
if (isset($_SESSION['username'])) {
    $sessionUsername = $_SESSION['username'];

    // Fetch the student ID based on the username from the student table
    $stmt = $pdo->prepare("SELECT id FROM students WHERE username = :username");
    $stmt->bindParam(':username', $sessionUsername);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Error: Student not found.");
    }

    $studentId = $student['id']; // This is the student_id that will be used to fetch messages
} else {
    die("Error: User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | ConnectU.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #162251;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }
        .notifications-list {
            list-style-type: none;
            padding: 0;
        }
        .notification-card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .notification-card:hover {
            transform: scale(1.02);
        }
        .notification-message {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
        }
        .notification-timestamp {
            font-size: 0.9rem;
            color: #777;
        }
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
.brand-title{
    color:blue;
}

.navbar .navbar-brand {
    font-size: 24px;
    font-weight: bold;
    color: rgb(255, 139, 15);
    text-decoration: none;
}

.navbar-links a {
    color: #ffffff; /* Changed color */
    text-decoration: none;
    margin-left: 15px;
    font-size: 16px;
}


.navbar-links a:hover {
    color: #050505; /* Changed color */
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
<div class="container">
    <h1>Notifications</h1>
    <div class="section">
        <ul class="notifications-list">
            <?php
            // Now use the correct student_id to fetch messages
            $messageStmt = $pdo->prepare("SELECT message, timestamp FROM messages WHERE student_id = :student_id ORDER BY timestamp DESC");
            $messageStmt->bindParam(':student_id', $studentId); // Use student_id from the session
            $messageStmt->execute();
            $messages = $messageStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($messages)) {
                foreach ($messages as $message) {
                    echo "
                    <li>
                        <div class='notification-card'>
                            <div class='notification-message'>" . htmlspecialchars($message['message']) . "</div>
                            <div class='notification-timestamp'><em>Received on: " . htmlspecialchars($message['timestamp']) . "</em></div>
                        </div>
                    </li>";
                }
            } else {
                echo "
                <li>
                    <div class='notification-card'>
                        <div class='notification-message'>No new notifications.</div>
                    </div>
                </li>";
            }
            ?>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>