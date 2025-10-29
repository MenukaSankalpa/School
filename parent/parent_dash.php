<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../index.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$_SESSION['username'] = $user['username'];
$_SESSION['child_name'] = $user['child_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Parent Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
<link rel="stylesheet" href="../css/parent_dash.css">
</head>
<body>

<header class="dashboard-header">
    <nav>
        <ul>
            <li><a href="#" id="showFormBtn">APPLY SCHOOLS</a></li>
            <li><a href="view_applications.php">VIEW APPLICATIONS</a></li>
            <li><a href="submit_complaint.php">SUBMIT COMPLAINT</a></li>
            <li><a href="message.php">STATUS & MESSAGES</a></li>
            <li><a href="logout.php" class="logout-btn">LOGOUT</a></li>
        </ul>
    </nav>
</header>

<div class="header-text" id="header-text">
    <h1>
        <span style="--i:1">H</span>
        <span style="--i:2">E</span>
        <span style="--i:3">L</span>
        <span style="--i:4">L</span>
        <span style="--i:5">O</span>
        <span style="--i:6">!</span>
    </h1>
</div>

<!-- Apply School Form -->
<div class="container" id="registrationForm" style="display:none;">
    <h2>Apply For Schools</h2>
    <form id="applyForm" method="POST">
        <div class="input-group">
            <input type="text" name="nin" placeholder="Parent NIC Number" required>
            <i class="ri-info-card-fill"></i>
        </div>

        <div class="input-group">
            <input type="text" name="child_name" value="<?= htmlspecialchars($user['child_name']); ?>" readonly>
            <i class="ri-account-box-fill"></i>
        </div>

        <div class="input-group">
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="boy">Boy</option>
                <option value="girl">Girl</option>
            </select>
        </div>

        <div class="input-group">
            <input id="address" type="text" name="address" placeholder="Address" required>
            <i class="ri-user-location-fill"></i>
        </div> 

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button type="submit" id="findSchoolsBtn">Find Nearby Schools</button>
    </form>
</div>

<!-- School Results -->
<div id="schoolResults" style="display:none;"></div>

<script>
// School array
const schoolArray = [
    { name: "Kalutara Boys' School", type: "boy", lat: 6.5836, lon: 79.9602 },
    { name: "Kalutara Balika Vidyalaya", type: "girl", lat: 6.5823, lon: 79.9609 },
    { name: "Holy Cross College", type: "mixed", lat: 6.5810, lon: 79.9631 },
    { name: "Tissa Central College", type: "mixed", lat: 6.5861, lon: 79.9605 },
    { name: "St. John's College", type: "boy", lat: 6.5887, lon: 79.9600 },
    { name: "Kalutara Muslim Girls School", type: "girl", lat: 6.5820, lon: 79.9620 },
    { name: "Al-Hambra Maha Vidyalaya", type: "mixed", lat: 6.5782, lon: 79.9635 },
    { name: "St. Thomas' Boys School", type: "boy", lat: 6.6345, lon: 79.9281 },
    { name: "Sagara Balika Vidyalaya", type: "girl", lat: 6.5334, lon: 79.9622 },
    { name: "Royal Central College", type: "mixed", lat: 6.5801, lon: 79.9520 }
];

// Toggle form
document.getElementById('showFormBtn').addEventListener('click', function(e){
    e.preventDefault();
    const form = document.getElementById('registrationForm');
    const header = document.getElementById('header-text');
    const isVisible = form.style.display === 'block';
    form.style.display = isVisible ? 'none' : 'block';
    header.style.display = isVisible ? 'flex' : 'none';
});

// Haversine formula
function getDistance(lat1, lon1, lat2, lon2){
    const R = 6371;
    const dLat = (lat2-lat1) * Math.PI/180;
    const dLon = (lon2-lon1) * Math.PI/180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Find nearby schools
document.getElementById('findSchoolsBtn').addEventListener('click', async function(e){
    e.preventDefault();

    const address = document.getElementById('address').value;
    const gender = document.getElementById('gender').value;
    const schoolResults = document.getElementById('schoolResults');
    const formContainer = document.getElementById('registrationForm');

    if(!address || !gender){ alert("Enter address and gender"); return; }

    try{
        // Proxy request
        const res = await fetch(`../api/get_location.php?address=${encodeURIComponent(address)}`);
        const geoData = await res.json();
        if(!geoData || geoData.length===0){ alert("Address not found"); return; }

        const userLat = parseFloat(geoData[0].lat);
        const userLon = parseFloat(geoData[0].lon);
        document.getElementById('latitude').value = userLat;
        document.getElementById('longitude').value = userLon;

        const allowed = gender==='boy' ? ['boy','mixed'] : ['girl','mixed'];
        const nearby = schoolArray
            .filter(s=>allowed.includes(s.type))
            .map(s=>({...s, distance:getDistance(userLat,userLon,s.lat,s.lon)}))
            .filter(s=>s.distance<=10)
            .sort((a,b)=>a.distance-b.distance)
            .slice(0,5);

        if(nearby.length===0){
            schoolResults.innerHTML = "<p>No schools found within 10km.</p>";
            schoolResults.style.display = 'block';
            formContainer.style.display = 'none';
            return;
        }

        // Build modern table
        let html = `<form id="schoolSelectForm" method="POST" action="../parent/submit_schools.php">
            <table class="school-table">
            <thead>
                <tr><th>Name</th><th>Type</th><th>Distance</th><th>Select</th></tr>
            </thead><tbody>`;
        nearby.forEach(s=>{
            html += `<tr>
                <td>${s.name}</td>
                <td>${s.type.charAt(0).toUpperCase()+s.type.slice(1)}</td>
                <td>${s.distance.toFixed(2)} km</td>
                <td><input type="checkbox" name="selected_schools[]" class="school-checkbox" value="${s.name}"></td>
            </tr>`;
        });
        html += `</tbody></table>
            <button type="submit" id="submitSelected" class="submit-btn" disabled>Submit</button>
        </form>`;

        schoolResults.innerHTML = html;
        schoolResults.style.display = 'block';
        formContainer.style.display = 'none';

        const checkboxes = document.querySelectorAll('.school-checkbox');
        const submitBtn = document.getElementById('submitSelected');
        checkboxes.forEach(cb=>{
            cb.addEventListener('change', ()=>{
                const selected = document.querySelectorAll('.school-checkbox:checked');
                if(selected.length>3){ cb.checked=false; alert("Select only 3 schools"); }
                submitBtn.disabled = selected.length===0;
            });
        });

    }catch(err){
        console.error(err);
        alert("Failed to fetch location");
    }
});
</script>
</body>
</html>
