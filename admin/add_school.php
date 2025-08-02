<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $address = trim($_POST['address']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        $error = "Invalid latitude or longitude values.";
    } else {
        $stmt = $conn->prepare("INSERT INTO schools (name, type, address, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdd", $name, $type, $address, $latitude, $longitude);
        if ($stmt->execute()) {
            header("Location: view_schools.php?added=1");
            exit();
        } else {
            $error = "Error adding school: " . htmlspecialchars($stmt->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel - Add School</title>
    <style>
        /* Reset & basics */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 220px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            padding: 15px 25px;
            display: block;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #34495e;
            color: #fff;
        }

        /* Main content */
        .main-content {
            margin-left: 220px;
            padding: 40px 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        /* Form */
        form {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            max-width: 480px;
        }
        form input[type="text"],
        form select {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        form input[type="text"]:focus,
        form select:focus {
            border-color: #2980b9;
            outline: none;
        }
        form button {
            background-color: #2980b9;
            border: none;
            color: white;
            padding: 14px 0;
            width: 100%;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
        }
        form button:hover {
            background-color: #1c5980;
        }

        /* Error message */
        .error-msg {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 10px 0;
                flex-direction: row;
                overflow-x: auto;
            }
            .sidebar a {
                padding: 10px 15px;
                flex: 1 0 auto;
                text-align: center;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            form {
                padding: 20px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="view_schools.php">View Schools</a>
    <a href="add_school.php" class="active">Add School</a>
    <!-- You can add more menu items here -->
</div>

<div class="main-content">
    <h1>Add New School</h1>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <input type="text" name="name" placeholder="School Name" required />
        <select name="type" required>
            <option value="" disabled selected>Select Type</option>
            <option value="boy">Boy</option>
            <option value="girl">Girl</option>
            <option value="mixed">Mixed</option>
        </select>
        <input type="text" name="address" placeholder="Address" required />
        <input type="text" name="latitude" placeholder="Latitude" required />
        <input type="text" name="longitude" placeholder="Longitude" required />
        <button type="submit">Add School</button>
    </form>
</div>

</body>
</html>
