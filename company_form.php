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
    $visit_date = $_POST['visit_date'];
    $visit_day = $_POST['visit_day'];
    $company_name = $_POST['company_name'];

    $sql = "INSERT INTO company_visits (visit_date, visit_day, company_name) 
            VALUES (:visit_date, :visit_day, :company_name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':visit_date', $visit_date);
    $stmt->bindParam(':visit_day', $visit_day);
    $stmt->bindParam(':company_name', $company_name);

    if ($stmt->execute()) {
        echo "<p class='success-message'>Company visit successfully added!</p>";
    } else {
        echo "<p class='error-message'>Error adding company visit!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company Visit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #162251;
            color: #fff;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #f8f9fa;
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
        }
        .success-message, .error-message {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
        }
        .success-message {
            color: #4CAF50;
        }
        .error-message {
            color: #f44336;
        }
        .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #0e043b; /* Changed color */
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
        <a class="navbar-brand d-flex align-items-center" href="umenu.html">
            <div class="logo d-flex align-items-center justify-content-center text-white">
                <span class="brand-title">VIT-Connect.in</span>
            </div>
        </a>
    </nav>
    <h1>Add Company Visit</h1>
    <form method="post" action="company_form.php">
        <label for="visit_date">Visit Date:</label>
        <input type="date" id="visit_date" name="visit_date" required>

        <label for="visit_day">Visit Day:</label>
        <input type="text" id="visit_day" name="visit_day" required>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
