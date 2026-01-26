<?php
if (!isset($_GET['address'])) {
    echo json_encode([]);
    exit;
}

$address = urlencode($_GET['address']);
$url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: MySchoolApp/1.0 (your-email@example.com)'
]);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
