<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addTask'])) {
      
      $title = $_POST['title'];
      $priority = $_POST['selectedPriority'];
      $date = date('Y-m-d'); 

      if($priority == ''){
        //reject the form
        // echo "Please select a priority";
        header("Location: index.php");
        exit();

      }
    
      $stmt = $conn->prepare("INSERT INTO task (title, priority, date) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $title, $priority, $date);

      if ($stmt->execute()) {
      } else {
          echo "Error: " . $stmt->error;
      }

      $stmt->close();

    }
    
}
?>