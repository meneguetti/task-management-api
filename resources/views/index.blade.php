<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Management Board</title>
  <link rel="stylesheet" href="css/general.css" type="text/css">
</head>
<body>
  <h1>Task Management Board</h1>
  <div class="board" id="board"></div>

  <!-- Edit Modal -->
  <div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal('modal')">×</span>
      <h3>
        Edit Task
        <span class="delete-button" onclick="confirmDeleteTask()"><img src="img/trash-icon.png" /></span>
      </h3>
      <div id="edit-errors"></div>
      <input type="text" id="modal-title" placeholder="Title">
      <div id="error-title" class="error"></div>
      <textarea id="modal-description" placeholder="Description"></textarea>
      <div id="error-description" class="error"></div>
      <select id="modal-status">
        <option value="backlog">Backlog</option>
        <option value="todo">Todo</option>
        <option value="in_progress">In Progress</option>
        <option value="done">Done</option>
      </select>
      <div id="error-status" class="error"></div>
      <select id="modal-priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <div id="error-priority" class="error"></div>
      <input type="date" id="modal-due-date">
      <div id="error-due_date" class="error"></div>
    </div>
  </div>

  <!-- Create Modal -->
  <div id="create-modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal('create-modal')">×</span>
      <h3>Create Task</h3>
      <div id="create-errors"></div>
      <input type="text" id="create-title" placeholder="Title">
      <div id="create-error-title" class="error"></div>
      <textarea id="create-description" placeholder="Description"></textarea>
      <div id="create-error-description" class="error"></div>
      <select id="create-status">
        <option value="backlog">Backlog</option>
        <option value="todo">Todo</option>
        <option value="in_progress">In Progress</option>
        <option value="done">Done</option>
      </select>
      <div id="create-error-status" class="error"></div>
      <select id="create-priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <div id="create-error-priority" class="error"></div>
      <input type="date" id="create-due-date">
      <div id="create-error-due_date" class="error"></div>
      <button onclick="createTask()">Create</button>
    </div>
  </div>

  <!-- Confirm Delete Modal -->
  <div id="delete-modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal('delete-modal')">×</span>
      <h3>Confirm Delete</h3>
      <p>Are you sure you want to delete this task?</p>
      <button onclick="deleteTask()">Yes</button>
      <button onclick="closeModal('delete-modal')">No</button>
    </div>
  </div>
  <div id="no-more">No more tasks to display</div>
  <div id="notification"></div>
  
  <script type="text/javascript" src="js/general.js"></script>
</body>
</html>
