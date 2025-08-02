<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../db.php';

// Handle form submission via POST in this same file
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add_school') {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $address = trim($_POST['address']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        $error_message = "Invalid latitude or longitude values.";
    } else {
        $stmt = $conn->prepare("INSERT INTO schools (name, type, address, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdd", $name, $type, $address, $latitude, $longitude);
        if ($stmt->execute()) {
            $success_message = "School added successfully!";
        } else {
            $error_message = "Error adding school: " . htmlspecialchars($stmt->error);
        }
    }
}

// Fetch schools for the table
$sql = "SELECT * FROM schools ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin - View & Add Schools</title>
    <style>
        /* Button */
        .btn-add-new {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 3px 6px rgba(0, 123, 255, 0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 15px;
            cursor: pointer;
            border: none;
        }
        .btn-add-new:hover {
            background-color: #0056b3;
            box-shadow: 0 5px 12px rgba(0, 86, 179, 0.5);
        }

        /* Form styles */
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
            max-width: 500px;
            margin-bottom: 30px;
        }
        form input[type="text"], form select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }
        form button[type="submit"], form button.cancel-btn {
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        form button[type="submit"]:hover {
            background-color: #0056b3;
        }
        form button.cancel-btn {
            background-color: #6c757d;
        }
        form button.cancel-btn:hover {
            background-color: #565e64;
        }

        /* Modern table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        thead th {
            padding: 14px 20px;
            font-weight: 600;
            text-align: left;
            letter-spacing: 0.05em;
        }
        tbody tr {
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s ease;
        }
        tbody tr:hover {
            background-color: #f1faff;
        }
        tbody td {
            padding: 12px 20px;
            color: #333;
        }
        tbody td:last-child {
            white-space: nowrap;
        }
        tbody a {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        tbody a:hover {
            color: #0056b3;
        }

        /* Responsive for small screens */
        @media (max-width: 700px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tbody tr {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                background: white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            tbody td {
                padding-left: 50%;
                position: relative;
                text-align: left;
                border: none;
                border-bottom: 1px solid #eee;
            }
            tbody td::before {
                position: absolute;
                top: 12px;
                left: 15px;
                width: 45%;
                font-weight: 700;
                white-space: nowrap;
                color: #555;
                content: attr(data-label);
            }
            tbody td:last-child {
                border-bottom: 0;
            }
        }

        /* Hide sections */
        .hidden {
            display: none;
        }

        /* Messages */
        .message {
            padding: 15px;
            max-width: 500px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

    <h1>Schools Admin Panel</h1>

    <!-- Success / Error messages -->
    <?php if (!empty($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <!-- Add New School Button -->
    <button id="showAddFormBtn" class="btn-add-new">+ Add New School</button>

    <!-- Add School Form (hidden by default) -->
    <form id="addSchoolForm" class="hidden" method="POST" novalidate>
        <input type="hidden" name="action" value="add_school">
        <input type="text" name="name" placeholder="School Name" required>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="boy">Boy</option>
            <option value="girl">Girl</option>
            <option value="mixed">Mixed</option>
        </select>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="latitude" placeholder="Latitude (-90 to 90)" required pattern="^-?\d+(\.\d+)?$" title="Enter a valid latitude">
        <input type="text" name="longitude" placeholder="Longitude (-180 to 180)" required pattern="^-?\d+(\.\d+)?$" title="Enter a valid longitude">

        <button type="submit">Add School</button>
        <button type="button" id="cancelAddForm" class="cancel-btn">Cancel</button>
    </form>

    <!-- Schools Table -->
    <table id="schoolsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                    <td data-label="Type"><?= htmlspecialchars(ucfirst($row['type'])) ?></td>
                    <td data-label="Address"><?= htmlspecialchars($row['address']) ?></td>
                    <td data-label="Latitude"><?= htmlspecialchars($row['latitude']) ?></td>
                    <td data-label="Longitude"><?= htmlspecialchars($row['longitude']) ?></td>
                    <td data-label="Actions">
                        <a href="edit_school.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_school.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this school?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No schools found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

<script>
    const showAddFormBtn = document.getElementById('showAddFormBtn');
    const addSchoolForm = document.getElementById('addSchoolForm');
    const schoolsTable = document.getElementById('schoolsTable');
    const cancelAddFormBtn = document.getElementById('cancelAddForm');

    // Show form & hide table
    showAddFormBtn.addEventListener('click', () => {
        addSchoolForm.classList.remove('hidden');
        schoolsTable.classList.add('hidden');
        showAddFormBtn.classList.add('hidden');
    });

    // Cancel button: hide form, show table & button
    cancelAddFormBtn.addEventListener('click', () => {
        addSchoolForm.classList.add('hidden');
        schoolsTable.classList.remove('hidden');
        showAddFormBtn.classList.remove('hidden');
        addSchoolForm.reset();
    });
</script>

</body>
</html>
