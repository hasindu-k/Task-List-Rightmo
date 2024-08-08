<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM task WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        echo json_encode($task);
    } else {
        echo json_encode(['error' => 'Task not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No task ID provided']);
}
?>
