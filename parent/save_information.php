<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Collect form data
    $child_full_name = $_POST['child_full_name'];
    $child_initials = $_POST['child_initials'];
    $child_religion = $_POST['child_religion'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];

    $applicant_full_name = $_POST['applicant_full_name'];
    $applicant_initials = $_POST['applicant_initials'];
    $applicant_nic = $_POST['applicant_nic'];
    $applicant_religion = $_POST['applicant_religion'];
    $applicant_address = $_POST['applicant_address'];
    $applicant_phone = $_POST['applicant_phone'];
    $resident_district = $_POST['resident_district'];

    $spouse_full_name = $_POST['spouse_full_name'];
    $spouse_initials = $_POST['spouse_initials'];
    $spouse_nic = $_POST['spouse_nic'];
    $spouse_religion = $_POST['spouse_religion'];
    $spouse_address = $_POST['spouse_address'];
    $spouse_phone = $_POST['spouse_phone'];
    $spouse_district = $_POST['spouse_district'];

    // Create folder and handle uploads
    function handleUploads($files, $folderType, $user_id) {
        $baseDir = __DIR__ . "/../uploads/{$folderType}/{$user_id}";
        $relativeBaseDir = "uploads/{$folderType}/{$user_id}";

        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $uploaded = [];
        foreach ($files['name'] as $key => $name) {
            $tmp = $files['tmp_name'][$key];
            if (!is_uploaded_file($tmp)) continue; // skip invalid

            $fileName = time() . "_" . basename($name);
            $fullPath = "$baseDir/$fileName";
            $relativePath = "$relativeBaseDir/$fileName";

            if (move_uploaded_file($tmp, $fullPath)) {
                $uploaded[] = $relativePath;
            }
        }

        return implode(", ", $uploaded);
    }

    $ebill_files = handleUploads($_FILES['ebill'], "ebill", $user_id);
    $lbill_files = handleUploads($_FILES['lbill'], "lbill", $user_id);

    // Save everything to database
    $sql = "INSERT INTO application_info (
        user_id, child_full_name, child_initials, child_religion, dob, age,
        applicant_full_name, applicant_initials, applicant_nic, applicant_religion, applicant_address, applicant_phone, resident_district,
        spouse_full_name, spouse_initials, spouse_nic, spouse_religion, spouse_address, spouse_phone, spouse_district,
        ebill_files, lbill_files
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssissssssssssssssss",
        $user_id, $child_full_name, $child_initials, $child_religion, $dob, $age,
        $applicant_full_name, $applicant_initials, $applicant_nic, $applicant_religion, $applicant_address, $applicant_phone, $resident_district,
        $spouse_full_name, $spouse_initials, $spouse_nic, $spouse_religion, $spouse_address, $spouse_phone, $spouse_district,
        $ebill_files, $lbill_files
    );

    if ($stmt->execute()) {
        // Redirect only if no output was sent
        header("Location: parent_dash.php");
        exit();
    } else {
        echo "Database error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
