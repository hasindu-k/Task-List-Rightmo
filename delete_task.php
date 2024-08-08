<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'];

    if (deleteTaskById($taskId)) {
        echo 'success';
    } else {
        echo 'failure';
    }
}

function deleteTaskById($taskId) {
    include 'conn.php';

    $stmt = $conn->prepare("DELETE FROM task WHERE id = ?");
    $stmt->bind_param("i", $taskId);

    $result = $stmt->execute();

    $stmt->close();
    $conn->close();
    
    return $result;
}
?>
