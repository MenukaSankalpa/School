<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_file'])) {
    $oldFile = $_POST['old_file'];
    $type = $_POST['type'];

    $uploadDir = "uploads/{$type}/{$user_id}/";

    
    if (!is_dir("../" . $uploadDir)) {
        mkdir("../" . $uploadDir, 0777, true);
    }

    $newFilename = $uploadDir . time() . "_" . basename($_FILES['new_file']['name']);
    $newFilePath = "../" . $newFilename;

    if (move_uploaded_file($_FILES['new_file']['tmp_name'], $newFilePath)) {
        
        $fullOldPath = "../" . $oldFile;
        if (file_exists($fullOldPath)) {
            unlink($fullOldPath);
        }

        
        $column = $type === 'ebill' ? 'ebill_files' : 'lbill_files';

        
        $sql = "SELECT id, $column FROM application_info WHERE user_id = ? AND $column LIKE ?";
        $stmt = $conn->prepare($sql);
        $likePattern = '%' . $oldFile . '%';
        $stmt->bind_param("is", $user_id, $likePattern);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $app_id = $data['id'];
            $fileList = explode(", ", $data[$column]);

            
            foreach ($fileList as &$f) {
                if ($f === $oldFile) {
                    $f = $newFilename;
                    break;
                }
            }
            $updatedList = implode(", ", $fileList);

            $updateSql = "UPDATE application_info SET $column=? WHERE id=?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $updatedList, $app_id);
            $updateStmt->execute();

            header("Location: view_photos.php?id=$app_id");
            exit();
        } else {
            echo "Application not found or multiple results.";
        }
    } else {
        echo "Failed to upload new file.";
    }
} else {
    echo "Invalid request.";
}
