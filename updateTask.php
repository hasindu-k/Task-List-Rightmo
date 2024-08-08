<?php

include 'conn.php';

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['priority'])) {
    $taskId = $_POST['id'];
    $title = $_POST['title'];
    $priority = $_POST['priority'];

    if($title == '' || $priority == '') {
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE task SET title = ?, priority = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $priority, $taskId);
    if ($stmt->execute()) {
        echo "Task updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Required data not provided.";
}
?>
