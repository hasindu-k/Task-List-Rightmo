<?php 
  include 'conn.php';

  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addTask'])) {
      
      $title = $_POST['title'];
      $priority = $_POST['selectedPriority'];
      $date = date('Y-m-d'); // Current date in YYYY-MM-DD format

    
      $stmt = $conn->prepare("INSERT INTO task (title, priority, date) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $title, $priority, $date);

      // Execute the statement
      if ($stmt->execute()) {
          // echo "New task added successfully.";
      } else {
          echo "Error: " . $stmt->error;
      }

      // Close statement and connection
      $stmt->close();

    } elseif (isset($_POST['updateStatus'])) {
      // Retrieve form data
      $update_id = $_POST['status_update_id'];
      $update_status = $_POST['updated_status'];
    
      $stmt = $conn->prepare("UPDATE task SET status = ? WHERE id = ?");
      $stmt->bind_param("si", $update_status, $update_id);
      if ($stmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
      $stmt->close();
    }
    
}


$sql = "SELECT id, title, priority, status FROM task";
$result = $conn->query($sql);


?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Task List</title>
  </head>
  <body class="bg-gray-400 flex justify-center min-h-screen">
    <div class="main-container w-full px-4 my-3 flex-col h-fit lg:w-4/5">
      <div class="title-container flex place-content-between mb-3 lg:mt-4">
        <h1 class="text-3xl font-bold">Task List</h1>
        <button
          type="button"
          onclick="openModal()"
          class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <div class="add-button flex place-items-center">
            <img src="src/add.svg" alt="task add button" class="size-4 pr-1" />
            Add Task
          </div>
        </button>
      </div>
      <div class="full-task-container ">
        <?php
          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $priority = $row["priority"];
                  // Determine the class based on priority
                  switch ($priority) {
                      // case 'High':
                      //     $class = 'text-red-500';
                      //     break;
                      // case 'Medium':
                      //     $class = 'text-yellow-500';
                      //     break;
                      // case 'Low':
                      //     $class = 'text-green-500';
                      //     break;
                      // default:
                      //     $class = 'bg-gray-200 text-black'; // Default case
                      //     break;

                          case 'High':
                            $class = 'bg-red-500 text-white';
                            break;
                        case 'Medium':
                            $class = 'bg-yellow-500 text-white';
                            break;
                        case 'Low':
                            $class = 'bg-green-500 text-white';
                            break;
                        default:
                            $class = 'bg-gray-200 text-black'; // Default case
                            break;
                  }
                  echo "
                  <div class='task-container mb-4'>
                <div class='single-task bg-white rounded-lg p-3 md:flex md:gap-16'>

                  <div class='task-title mb-2 md:basis-1/3 '>
                    <h2 class='text-lg text-slate-400'>Task</h2>
                    <h2 class='text-xl font-semibold text-black-400'>".$row["title"]."</h2>
                  </div>

                  <div class='task-priority mb-2 md:basis-1/6'>
                    <h2 class='text-lg text-slate-400'>Priority</h2>
                    <h2 class='text-xl font-semibold <?php echo $class; ?> '>".$row["priority"]."</h2>
                  </div> 

                  <div class='task-status flex relative gap-5 mb-3 md:basis-1/3'>
                    <button
                      class='bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500' onclick='showList()'
                    >
                      ".$row["status"]."
                    </button>
                    <div id='status-list' class='absolute hidden right-0 mt-2 w-48 p-3 bg-white border border-gray-200 rounded-lg shadow-lg z-10'>
                      <form action='' method='post'>
                        <select name='updated_status' id='updated_status' class='bg-white border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'>
                          <option value='To Do'>To Do</option>
                          <option value='In Progress'>In Progress</option>
                          <option value='Done'>Done</option>
                        </select>
                        <input type='text' name='status_update_id' value='".$row["id"]."'>
                        
                        <button
                          class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                          type='submit' name='updateStatus'
                        >
                          Update Status
                        </button>
                    </div>
                    <img
                      src='src/circle-dashed-svgrepo-com.svg'
                      class='size-4 my-auto'
                      alt=''
                    />
                  </div>

                  <div class='task-operation flex mb-2 gap-8 items-center md:basis-1/6'>
                    <img
                      src='src/edit.svg'
                      alt='edit task'
                      class='size-4'
                      onclick='openUpdateModal(this)'
                    />
                    <img
                      src='src/delete.svg'
                      alt='edit task'
                      class='size-4'
                      onclick='openDeleteModal()'
                    />
                  </div>
                </div>
              </div>
        ";
              }
          } else {
              echo "No Tasks found";
          }
          $conn->close();
        ?>

        <!-- <div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div><div class="task-container mb-4 ">
            <div class="single-task bg-white rounded-lg p-3 md:flex md:gap-16">
  
              <div class="task-title mb-2 md:basis-1/3 ">
                <h2 class="text-lg text-slate-400">Task</h2>
                <h2 class="text-xl font-semibold text-black-400">Task</h2>
              </div>
  
              <div class="task-priority mb-2 md:basis-1/6">
                <h2 class="text-lg text-slate-400">Priority</h2>
                <h2 class="text-xl font-semibold text-black-400">Priority</h2>
              </div>
  
              <div class="task-status flex gap-5 mb-3 md:basis-1/3">
                <button
                  class="bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  To Do
                </button>
                <img
                  src="src/circle-dashed-svgrepo-com.svg"
                  class="size-4 my-auto"
                  alt=""
                />
              </div>
  
              <div class="task-operation flex mb-2 gap-8 items-center md:basis-1/6 ">
                <img
                  src="src/edit.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openUpdateModal(this)"
                />
                <img
                  src="src/delete.svg"
                  alt="edit task"
                  class="size-4"
                  onclick="openDeleteModal()"
                />
              </div>
            </div>
          </div>
      </div> -->
    </div>

    <!-- Modal -->
    <div id="taskModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Add New Task</h2>
        <form action="" method="post">
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="taskTitle"
              >Title</label
            >
            <input
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="taskTitle"
              type="text"
              name="title"
              placeholder="Task Title"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="taskPriority"
              >Priority</label
            >
            <div id="priorityButtons" class="flex gap-2">
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-red-500 text-red-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="High"
              >
                High
              </button>
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-yellow-500 text-yellow-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="Medium"
              >
                Medium
              </button>
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-green-500 text-green-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="Low"
              >
                Low
              </button>
            </div>
            <input
              type="hidden"
              id="selectedPriority"
              name="selectedPriority"
              value=""
            />
          </div>
          <div class="flex items-center justify-between">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="submit" name="addTask"
            >
              Add Task
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="button"
              onclick="closeModal()"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Task Update Modal -->
    <div id="taskUpdateModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Update Task</h2>
        <form id="updateTaskForm">
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="updateTaskTitle"
              >Title</label
            >
            <input
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="updateTaskTitle"
              type="text"
              placeholder="Task Title"
            />
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="updateTaskPriority"
              >Priority</label
            >
            <div id="updatePriorityButtons" class="flex gap-2">
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-red-500 text-red-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="High"
              >
                High
              </button>
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-yellow-500 text-yellow-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="Medium"
              >
                Medium
              </button>
              <button
                type="button"
                class="priority-button border-2 bg-gray-200 border-green-500 text-green-500 font-bold py-2 px-4 rounded-lg focus:outline-none"
                data-priority="Low"
              >
                Low
              </button>
            </div>
            <input
              type="hidden"
              id="updateSelectedPriority"
              name="selectedPriority"
              value=""
            />
          </div>
          <div class="flex items-center justify-between">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="button"
              onclick="updateTask()"
            >
              Update Task
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="button"
              onclick="closeUpdateModal()"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Modal -->
    <div
      id="taskDeleteModal"
      class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center"
    >
      <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Delete Task</h2>
        <p class="mb-4">
          Are you sure you want to delete this task? This action cannot be
          undone.
        </p>
        <div class="flex items-center justify-between">
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            onclick="deleteTask()"
          >
            Delete Task
          </button>
          <button
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            onclick="closeDeleteModal()"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

    <script>
      function openModal() {
        document.getElementById("taskModal").classList.remove("hidden");
      }

      function closeModal() {
        document.getElementById("taskModal").classList.add("hidden");
      }

      document.querySelectorAll(".priority-button").forEach((button) => {
        button.addEventListener("click", function () {
          // Remove the 'bg-*' and 'text-white' classes from all buttons
          document.querySelectorAll(".priority-button").forEach((btn) => {
            btn.classList.remove(
              "bg-red-500",
              "bg-yellow-500",
              "bg-green-500",
              "text-white"
            );
            // Reset to original text color based on priority
            const priority = btn.getAttribute("data-priority");
            if (priority === "High") {
              btn.classList.add("text-red-500");
            } else if (priority === "Medium") {
              btn.classList.add("text-yellow-500");
            } else if (priority === "Low") {
              btn.classList.add("text-green-500");
            }
          });

          // Add the appropriate 'bg-*' and 'text-white' classes to the clicked button
          this.classList.add(
            this.getAttribute("data-priority") === "High"
              ? "bg-red-500"
              : this.getAttribute("data-priority") === "Medium"
              ? "bg-yellow-500"
              : "bg-green-500",
            "text-white"
          );

          // Update the hidden input field with the clicked button's priority
          document.getElementById("selectedPriority").value =
            this.getAttribute("data-priority");
        });
      });

      function addTask() {
        // Code to add the task goes here
        const taskTitle = document.getElementById("taskTitle").value;
        const taskPriority = document.getElementById("selectedPriority").value;
        console.log("Task Title:", taskTitle);
        console.log("Task Priority:", taskPriority);
        // Close the modal after adding the task
        closeModal();
      }

      function openUpdateModal(task) {
        // Set the values of the form fields with the task data
        document.getElementById("updateTaskTitle").value = task.title;
        document.getElementById("updateSelectedPriority").value = task.priority;

        // Highlight the button corresponding to the task's priority
        document
          .querySelectorAll("#updatePriorityButtons .priority-button")
          .forEach((button) => {
            button.classList.remove("bg-blue-500", "text-white");
            if (button.getAttribute("data-priority") === task.priority) {
              button.classList.add("bg-blue-500", "text-white");
            }
          });

        // Show the modal
        document.getElementById("taskUpdateModal").classList.remove("hidden");
      }

      function closeUpdateModal() {
        // Hide the modal
        document.getElementById("taskUpdateModal").classList.add("hidden");
      }

      document
        .querySelectorAll("#updatePriorityButtons .priority-button")
        .forEach((button) => {
          button.addEventListener("click", function () {
            // Remove the 'bg-blue-500' and 'text-white' classes from all buttons
            document
              .querySelectorAll("#updatePriorityButtons .priority-button")
              .forEach((btn) =>
                btn.classList.remove("bg-blue-500", "text-white")
              );

            // Add the 'bg-blue-500' and 'text-white' classes to the clicked button
            this.classList.add("bg-blue-500", "text-white");

            // Update the hidden input field with the clicked button's priority
            document.getElementById("updateSelectedPriority").value =
              this.getAttribute("data-priority");
          });
        });

      function updateTask() {
        const title = document.getElementById("updateTaskTitle").value;
        const priority = document.getElementById(
          "updateSelectedPriority"
        ).value;
        
        closeModal();
      }

      function openDeleteModal() {
        // Show the delete modal
        document.getElementById("taskDeleteModal").classList.remove("hidden");
      }

      function closeDeleteModal() {
        // Hide the delete modal
        document.getElementById("taskDeleteModal").classList.add("hidden");
      }

      function deleteTask() {
        // Perform the delete operation here, such as sending the request to the server to delete the task

        // Close the modal after deleting
        closeDeleteModal();
      }

      function showList() {
          var statusList = document.getElementById('status-list');
          if (statusList.classList.contains('hidden')) {
              statusList.classList.remove('hidden');
          } else {
              statusList.classList.add('hidden');
          }
      }
    </script>
  </body>
</html>
