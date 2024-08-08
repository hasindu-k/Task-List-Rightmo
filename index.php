<?php 
  include 'conn.php';
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
                  switch ($priority) {
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
                          $class = 'bg-gray-200 text-black'; 
                          break;
                  }
                  $uniqueId = "status-list-" . $row["id"]; 
                  $currentId = $row["id"];
                  
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
                    <button class='bg-slate-100 text-slate-400 font-bold py-1 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500' onclick='showList(\"".$uniqueId."\")'>
                      ".$row["status"]."
                    </button>

                    <div id='".$uniqueId."' class='absolute hidden right-0 mt-2 w-48 p-3 bg-white border border-gray-200 rounded-lg shadow-lg z-10'>
                      <form action='update_status.php' method='post'>
                        <select name='updated_status' id='updated_status' class='bg-white border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'>
                          <option value='To Do'>To Do</option>
                          <option value='In Progress'>In Progress</option>
                          <option value='Done'>Done</option>
                        </select>
                        <input type='text' name='status_update_id' value='".$currentId."'>
                        
                        <button
                          class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                          type='submit' name='updateStatus'
                        >
                          Update Status
                        </button>
                      </form>
                    </div>

                    <img 
                      src='src/" . 
                      ($row["status"] == "To Do" ? 'circle-dashed-svgrepo-com.svg' : 
                      ($row["status"] == "In Progress" ? 'shape-half-circle-svgrepo-com.svg' : 'circle-svgrepo-com.svg')) . 
                      "' class='size-4 my-auto'/>
                  </div>

                  <div class='task-operation flex mb-2 gap-8 items-center md:basis-1/6'>
                    <img
                      src='src/edit.svg'
                      alt='edit task'
                      class='size-4'
                      onclick='openUpdateModal(".$row["id"].")'
                    />
                    <img
                      src='src/delete.svg'
                      alt='edit task'
                      class='size-4'
                      onclick='openDeleteModal(".$row["id"].")'
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
    </div>

    <!-- Modal -->
    <div id="taskModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Add New Task</h2>
        <form action="addTask.php" method="post">
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
              placeholder="Task Title" required
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
              value=""
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
    <div id="taskDeleteModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Delete Task</h2>
        <p class="mb-4">
          Are you sure you want to delete this task? This action cannot be
          undone.
        </p>
        <div class="flex items-center justify-between">
          <form action="" method="post">
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            onclick="deleteTask()"
          >
            Delete Task
          </button>
          </form>
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
          this.classList.add(this.getAttribute("data-priority") === "High"
              ? "bg-red-500": this.getAttribute("data-priority") === "Medium"
              ? "bg-yellow-500": "bg-green-500",
            "text-white"
          );

          // Update the hidden input field with the clicked button's priority
          document.getElementById("selectedPriority").value =this.getAttribute("data-priority");
        });
      });

      function addTask() {
        const taskTitle = document.getElementById("taskTitle").value;
        const taskPriority = document.getElementById("selectedPriority").value;

        console.log("Task Title:", taskTitle);
        console.log("Task Priority:", taskPriority);
        closeModal();
      }
      function openUpdateModal(taskId) {
        
        fetch(`getTaskDetails.php?id=${taskId}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById('updateTaskTitle').value = data.title;
            document.getElementById('updateSelectedPriority').value = data.priority;

            document.querySelectorAll('.priority-button').forEach(button => {
              if (button.dataset.priority === data.priority) {
                button.classList.add('bg-' + getPriorityColor(data.priority), 'text-white');
                button.classList.remove('bg-gray-200', 'text-' + getPriorityColor(data.priority));
              } else {
                button.classList.remove('bg-' + getPriorityColor(button.dataset.priority), 'text-white');
                button.classList.add('bg-gray-200', 'text-' + getPriorityColor(button.dataset.priority));
              }
            });

            document.getElementById('updateTaskForm').dataset.taskId = taskId;

            document.getElementById('taskUpdateModal').classList.remove('hidden');
          })
          .catch(error => console.error('Error fetching task details:', error));
      }

      function getPriorityColor(priority) {
        switch (priority) {
          case 'High':
            return 'red-500';
          case 'Medium':
            return 'yellow-500';
          case 'Low':
            return 'green-500';
          default:
            return 'gray-500';
        }
      }

      function closeUpdateModal() {
        document.getElementById('taskUpdateModal').classList.add('hidden');
      }
      function closeUpdateModal() {
        document.getElementById("taskUpdateModal").classList.add("hidden");
      }

      document
        .querySelectorAll("#updatePriorityButtons .priority-button")
        .forEach((button) => {
          button.addEventListener("click", function () {
            document
              .querySelectorAll("#updatePriorityButtons .priority-button")
              .forEach((btn) =>
                btn.classList.remove("bg-blue-500", "text-white")
              );

            this.classList.add("bg-blue-500", "text-white");

            document.getElementById("updateSelectedPriority").value =
              this.getAttribute("data-priority");
          });
        });

      // function updateTask() {
      //   const title = document.getElementById("updateTaskTitle").value;
      //   const priority = document.getElementById(
      //     "updateSelectedPriority"
      //   ).value;
        
      //   closeModal();
      // }

      function updateTask() {
      const taskId = document.getElementById('updateTaskForm').dataset.taskId;
      const title = document.getElementById('updateTaskTitle').value;
      const priority = document.getElementById('updateSelectedPriority').value;

      const formData = new FormData();
      formData.append('id', taskId);
      formData.append('title', title);
      formData.append('priority', priority);

      fetch('updateTask.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        console.log(data);
        closeUpdateModal();
        location.reload();
      })
      .catch(error => console.error('Error updating task:', error));
    }
      function closeDeleteModal() {
        document.getElementById("taskDeleteModal").classList.add("hidden");
      }

      function openDeleteModal(taskId) {
        
        fetch(`getTaskDetails.php?id=${taskId}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById('updateTaskTitle').value = data.title;
            document.getElementById('updateSelectedPriority').value = data.priority;

            document.querySelectorAll('.priority-button').forEach(button => {
              if (button.dataset.priority === data.priority) {
                button.classList.add('bg-' + getPriorityColor(data.priority), 'text-white');
                button.classList.remove('bg-gray-200', 'text-' + getPriorityColor(data.priority));
              } else {
                button.classList.remove('bg-' + getPriorityColor(button.dataset.priority), 'text-white');
                button.classList.add('bg-gray-200', 'text-' + getPriorityColor(button.dataset.priority));
              }
            });

            document.getElementById('updateTaskForm').dataset.taskId = taskId;

            document.getElementById("taskDeleteModal").classList.remove("hidden");
          })
          .catch(error => console.error('Error fetching task details:', error));
      }
      function deleteTask() {
        console.log("Task deleted");
        closeDeleteModal();
      }

      function showList(uniqueId) {
    document.querySelectorAll('.status-list').forEach(function(list) {
        list.classList.add('hidden');
    });

    var statusList = document.getElementById(uniqueId);
    if (statusList.classList.contains('hidden')) {
        statusList.classList.remove('hidden');
    } else {
        statusList.classList.add('hidden');
    }
}
    </script>
  </body>
</html>
