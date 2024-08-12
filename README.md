# Task-List-Rightmo

This repository contains the Task List application developed as part of the developer test for Rightmo. The application allows users to manage tasks by creating, updating, filtering, and deleting them. Each task is categorized by its priority (High, Medium, Low) and status (To Do, In Progress, Done).

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Live Demo](#live-demo)
- [File Structure](#file-structure)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Task Creation**: Add new tasks with a title, priority, and initial status.
- **Task Filtering**: Filter tasks by their title using a search bar.
- **Task Status Update**: Update the status of tasks (To Do, In Progress, Done).
- **Priority Display**: Display task priorities with color-coded labels (High, Medium, Low).
- **Task Deletion**: Delete tasks with a confirmation prompt.

## Technologies Used

- **Frontend**:
  - HTML5
  - CSS3 (Tailwind CSS)
  - JavaScript

- **Backend**:
  - PHP (for server-side logic and database interaction)
  
- **Database**:
  - MySQL (for storing task information)

## Installation

### Prerequisites

- [PHP](https://www.php.net/) (version 7.4 or later)
- [MySQL](https://www.mysql.com/) (version 5.7 or later)
- A web server like [Apache](https://httpd.apache.org/) or [Nginx](https://www.nginx.com/)

### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/Task-List-Rightmo.git
   cd Task-List-Rightmo

2. **Set up the database**:

- Import the `tasks.sql` file into your MySQL database.
- Update the database connection details in the `config.php` file.

3. **Run the application**:

- If using a local server (like XAMPP or WAMP), move the project folder to the `htdocs` directory and start your server.
- Access the application in your web browser at `http://localhost/Task-List-Rightmo`.

4. **Usage**:

### Adding a Task:

- Fill in the task details (title, priority) and submit the form to create a new task.

### Updating a Task:

- Click on the "Edit" icon next to a task to modify its details.
- Change the task's status using the dropdown and save the changes.

### Filtering Tasks:

- Use the search bar at the top to filter tasks by title.

### Deleting a Task:

- Click on the "Delete" icon next to a task to remove it from the list. A confirmation modal will appear.

## Live Demo

You can view and interact with the application live at <a href="http://taskapp.infinityfreeapp.com/">Task-List-Rightmo.</a>

6. **File Structure**:
   ```bash
   Task-List-Rightmo/
      ├── src/
      │   ├── css/                # Stylesheets
      │   ├── images/             # Images and icons
      ├── conn.php                # Database configuration
      ├── index.php               # Main application page
      ├── tasks.sql               # Database schema
      ├── README.md               # This README file

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any feature requests or bugs.

