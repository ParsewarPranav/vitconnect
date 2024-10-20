<?php
// Start session and connect to the database
session_start();

$host = 'localhost';
$dbname = 'connectu'; 
$dbusername = 'root';
$dbpassword = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}

// Initialize the search keyword
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch all student profiles or filter by search keyword
$sql = "SELECT * FROM students WHERE name LIKE :keyword OR skills LIKE :keyword OR about LIKE :keyword";
$stmt = $pdo->prepare($sql);
$searchTerm = "%" . $searchKeyword . "%";  // Wrap the search keyword in wildcards for partial matches
$stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$students) {
    $noResultMessage = "No student profiles found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Student Profiles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .profile-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .profile-card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            max-width: 300px;
            text-align: center;
            flex: 1 1 calc(33% - 40px);
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
            margin-bottom: 20px;
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

        /* Search Box Styling */
        .search-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-box input[type="text"] {
            width: 60%;
            padding: 10px;
            font-size: 1.1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            background-color: #100247;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background-color: #ff7f50;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo d-flex align-items-center">
        <a href="manager-index.html"><img src="vitlogo2.png" alt="Logo" class="navbar-logo"></a>
        <span class="brand-title">VIT-Connect.in <br> Vishwakarma Institute Of Technology, Pune</span>
    </div>
</nav>

<h1>All Student Profiles</h1>

<!-- Search Box -->
<div class="search-box">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search profiles by name, skills, or about" value="<?php echo htmlspecialchars($searchKeyword); ?>">
        <input type="submit" value="Search">
    </form>
</div>

<div class="profile-grid">
    <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
            <div class="profile-card">
                <?php if (!empty($student['profile_pic'])): ?>
                    <img src="<?php echo htmlspecialchars($student['profile_pic']); ?>" alt="<?php echo htmlspecialchars($student['name']); ?>">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($student['name']); ?></h2>
                <p><strong>About:</strong> <?php echo htmlspecialchars($student['about']); ?></p>
                <p><strong>Skills:</strong> <?php echo htmlspecialchars($student['skills']); ?></p>
                <a href="profile1.php?username=<?php echo $student['username']; ?>" class="btn">See Profile</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No profiles match your search.</p>
    <?php endif; ?>
</div>

</body>
</html>
