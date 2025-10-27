<?php
include '../db.php';
session_start();

// Check admin is logged in
$adminId = $_SESSION['admin_id'] ?? 0;
$adminRole = $_SESSION['role'] ?? 0;
$isSuperAdmin = ($adminRole == 1);

if(!$adminId) {
    echo json_encode(['success'=>false, 'message'=>'Unauthorized']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$appId = intval($data['app_id'] ?? 0);

if(!$appId) {
    echo json_encode(['success'=>false, 'message'=>'Invalid application ID']);
    exit;
}

// Fetch parent ID of the application
$stmt = $conn->prepare("SELECT applicant_id FROM application_info WHERE id = ?");
$stmt->bind_param("i", $appId);
$stmt->execute();
$result = $stmt->get_result();
$app = $result->fetch_assoc();

if(!$app) {
    echo json_encode(['success'=>false, 'message'=>'Application not found']);
    exit;
}

$parentId = $app['applicant_id'];

// Optional: check if admin is assigned to this application or super admin
$checkStmt = $conn->prepare("SELECT assigned_admin_id FROM application_info WHERE id = ?");
$checkStmt->bind_param("i", $appId);
$checkStmt->execute();
$checkRes = $checkStmt->get_result()->fetch_assoc();
$assignedAdmin = $checkRes['assigned_admin_id'];

if(!$isSuperAdmin && $assignedAdmin != $adminId){
    echo json_encode(['success'=>false, 'message'=>'You are not authorized to send message to this application']);
    exit;
}

// Insert message into dashboard_messages
$message = "You have an update regarding your application ID $appId."; // customize as needed
$insert = $conn->prepare("INSERT INTO dashboard_messages (parent_id, app_id, message) VALUES (?, ?, ?)");
$insert->bind_param("iis", $parentId, $appId, $message);
if($insert->execute()){
    echo json_encode(['success'=>true, 'message'=>'Message sent to parent dashboard!']);
} else {
    echo json_encode(['success'=>false, 'message'=>'Failed to send message']);
}
