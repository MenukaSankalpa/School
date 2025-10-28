<?php
if(!isset($_GET['address'])) {
    echo json_encode([]);
    exit;
}

$address = $_GET['address'];
$url = "https://nominatim.openstreetmap.org/search?format=json&q=".urlencode($address);

$options = [
    "http" => [
        "header" => "User-Agent: SchoolAdmissionSystemApp/1.0\r\n"
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
echo $response;
