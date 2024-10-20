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
$id = $_SESSION['id']; 
$sql = "SELECT * FROM students WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    die("Error: Student profile not found.");
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
            padding: 0;
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
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            object-fit: cover;
            margin-bottom: 15px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 50px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #100247;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-logo {  
            height: 100px; 
            width: auto; 
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
            margin-left:100px !important;
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
            color: #ff7f50; 
        }
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            .navbar-links {
                flex-direction: column;
                width: 100%;
                margin-right:100px !important;
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
            background-color: #ff7f50;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo d-flex align-items-center">
        <a href="umenu.html"><img src="vitlogo2.png" alt="Logo" class="navbar-logo"></a>
        <span class="brand-title">VIT-Connect.in <br> Vishwakarma Institute Of Technology, Pune</span>
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
    <a href="profile1.php" class="btn">See Profile</a>
</div>

</body>
</html>
