<?php
include '../db.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM schools";
$result = $conn->query($sql);

$schools = [];

while ($row = $result->fetch_assoc()) {
    $schools[] = [
        'name' => $row['name'],
        'type' => $row['type'],
        'address' => $row['address'],
        'lat' => (float)$row['latitude'],
        'lon' => (float)$row['longitude']
    ];
}

echo json_encode($schools);
?>
