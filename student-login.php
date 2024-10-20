<?php
$host = 'localhost';
$dbname = 'connectu';
$username = 'root'; 
$password = '';


session_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password']; 


    $stmt = $pdo->prepare("SELECT * FROM students WHERE username = :username");
    $stmt->bindParam(':username', $inputUsername);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the entered password with the stored hash
        if (password_verify($inputPassword, $user['password'])) {
            // Password is correct, set session variable with user data
            $_SESSION['id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session
            header("Location: profile.php"); // Redirect to profile page
            exit();
        } else {
            // Invalid password
            $error = "Invalid password.";
        }
    } else {
        // Invalid username
        $error = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | ConnectU.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Container for form width and centering */
        .container {
            width: 50%; /* Adjust this value to make the form narrower */
            max-width: 600px; /* Optional: Set a max-width to prevent it from becoming too wide */
            margin: auto; /* Center the container */
            margin-top: 50px; /* Add space between navbar and form */
        }

        /* Login section background */
        .login-section {
            background-color: rgba(255, 255, 255, 0.6); /* Transparent background to view the image */
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(10px); /* Adds blur effect to background */
            margin-bottom: 50px;
            margin-top: 0%;
        }

        /* Form input styling */
        .login-section .form-control, .login-section .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Button styling */
        .login-section .btn-primary {
            background-color: #50aaff;
            border: none;
            color: #fff;
        }

        .login-section .btn-primary:hover {
            background-color: #3a8fd8;
        }

        .auth-buttons {
            margin-top: 20px; /* Spacing for the auth buttons */
        }

        /* Navbar styles */
        .logo {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .brand-title {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .bg-light {
            background-color: #0e0142 !important;
        }

        .navbar {
            min-height: 100px;
            padding: 20px 10px;
            display: flex;
            align-items: center;
        }

        /* Logo styling */
        .navbar-logo {
            height: 80px; /* Adjust logo height */
            width: auto;
        }

        /* Background image for the page */
        body {
            background-image: url('sback.png'); /* Path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Dark mode for login section */
        .login-section.dark-mode {
            background-color: #333;
            color: #fff;
        }

        h1 {
            color: #0e0142;
            margin-bottom: 10px;
            margin-left: 25%;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="logo d-flex align-items-center justify-content-center text-white">
                <img src="vitlogo2.png" alt="Logo" class="navbar-logo">
                <span class="brand-title ms-2">VIT-Connect.in <br> Vishwakarma Institute Of Technology, Pune</span>
            </div>
        </div>
    </nav>

    <div class="container">
        <form class="login-section" method="post" action="">
            <h1>Student Login</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="auth-buttons text-center mt-3">
                <p>Don't have an account? <a href="signup.php" class="btn btn-secondary">Sign Up</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
