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
    $academic_year = $_POST['academic_year'];
    $branch = $_POST['branch'];
    $students_placed = $_POST['students_placed'];
    $highest_package = $_POST['highest_package'];
    $average_package = $_POST['average_package'];

    $sql = "INSERT INTO placement_data (academic_year, branch, students_placed, highest_package, average_package) 
            VALUES (:academic_year, :branch, :students_placed, :highest_package, :average_package)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':academic_year', $academic_year);
    $stmt->bindParam(':branch', $branch);
    $stmt->bindParam(':students_placed', $students_placed);
    $stmt->bindParam(':highest_package', $highest_package);
    $stmt->bindParam(':average_package', $average_package);

    if ($stmt->execute()) {
        echo "Data successfully inserted!";
    } else {
        echo "Error inserting data!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Placement Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 50px;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"], 
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #2f73c7;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #1e5da9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-text {
            color: #888;
            font-size: 0.9rem;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            color: #666;
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
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo d-flex align-items-center">
        <a href="umenu.html"><img src="vitlogo2.png" alt="Logo" class="navbar-logo"></a>
        <span class="brand-title">VIT-Connect.in <br> Vishwakarma Institute Of Technology, Pune</span>
    </div>
</nav>
    <div class="container">
        <h1>Update Placement Data</h1>
        <form method="post" action="update.php">
            <div class="form-group">
                <label for="academic_year">Academic Year:</label>
                <input type="text" id="academic_year" name="academic_year" class="form-control" placeholder="e.g., 2023-2024" required>
            </div>

            <div class="form-group">
                <label for="branch">Branch:</label>
                <input type="text" id="branch" name="branch" class="form-control" placeholder="e.g., Computer Science, Mechanical" required>
            </div>

            <div class="form-group">
                <label for="students_placed">Number of Students Placed:</label>
                <input type="number" id="students_placed" name="students_placed" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="highest_package">Highest Package (LPA):</label>
                <input type="number" step="0.01" id="highest_package" name="highest_package" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="average_package">Average Package (LPA):</label>
                <input type="number" step="0.01" id="average_package" name="average_package" class="form-control" required>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2024 ConnectU.in - All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
