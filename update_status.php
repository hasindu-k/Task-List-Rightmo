<?php

include 'conn.php';

if (isset($_POST['updateStatus'])) {
    $update_id = $_POST['status_update_id'];
    $update_status = $_POST['updated_status'];

    $stmt = $conn->prepare("UPDATE task SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $update_status, $update_id);
    if ($stmt->execute()) {
        echo "Status updated successfully ".$update_id." ".$update_status .".";
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
