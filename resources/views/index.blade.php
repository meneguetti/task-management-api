<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Management Board</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    .board {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .column {
      flex: 1;
      min-width: 250px;
      max-width: 300px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 10px;
      position: relative;
    }
    .column h2 {
      text-align: center;
    }
    .column-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    .add-task {
      cursor: pointer;
      font-size: 18px;
    }
    .task-count {
      font-size: 14px;
      color: #555;
    }
    .task {
      padding: 10px;
      margin: 10px 0;
      border-radius: 8px;
      cursor: move;
      background-color: #fff;
    }
    .task.backlog { background-color: #ccc; }
    .task.todo { background-color: #fff; }
    .task.in_progress { background-color: #add8e6; }
    .task.done { background-color: #90ee90; }
    .drag-over {
      background-color: #d0f0c0;
    }
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      position: relative;
    }
    .modal-content input,
    .modal-content textarea,
    .modal-content select {
      width: 100%;
      padding: 8px;
      margin: 10px 0;
    }
    .modal-content button {
      padding: 8px 12px;
      margin-top: 10px;
    }
    .close-button {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 20px;
      cursor: pointer;
    }
    .delete-button {
      color: red;
      font-size: 20px;
      cursor: pointer;
      margin-left: 10px;
    }
    .error {
      color: red;
      font-size: 13px;
    }
    
  </style>
</head>
<body>
  <h1>Task Management Board</h1>
  <div class="board" id="board">
    <div class="column" data-status="backlog">
      <div class="column-header">
        <h2>Backlog</h2>
        <span class="add-task" onclick="openCreateModal('backlog')">➕</span>
      </div>
      <div class="task-count" id="count-backlog"></div>
      <div class="task-list" id="backlog"></div>
    </div>
    <div class="column" data-status="todo">
      <div class="column-header">
        <h2>Todo</h2>
        <span class="add-task" onclick="openCreateModal('todo')">➕</span>
      </div>
      <div class="task-count" id="count-todo"></div>
      <div class="task-list" id="todo"></div>
    </div>
    <div class="column" data-status="in_progress">
      <div class="column-header">
        <h2>In Progress</h2>
        <span class="add-task" onclick="openCreateModal('in_progress')">➕</span>
      </div>
      <div class="task-count" id="count-in_progress"></div>
      <div class="task-list" id="in_progress"></div>
    </div>
    <div class="column" data-status="done">
      <div class="column-header">
        <h2>Done</h2>
        <span class="add-task" onclick="openCreateModal('done')">➕</span>
      </div>
      <div class="task-count" id="count-done"></div>
      <div class="task-list" id="done"></div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal('modal')">×</span>
      <h3>
        Edit Task
        <span class="delete-button" onclick="confirmDeleteTask()">🗑️</span>
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
  
  <script>
    let tasks = [];
    let draggedTask = null;
    let currentTaskId = null;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };

    async function fetchTasks() {
      const res = await fetch('/api/tasks');
      tasks = await res.json();
      renderTasks();
    }

    function renderTasks() {
      ['backlog','todo','in_progress','done'].forEach(status => {
        const col = document.getElementById(status);
        col.innerHTML = '';
        const filtered = tasks.filter(t => t.status === status);
        document.getElementById('count-' + status).textContent = `${filtered.length} tasks`;
        filtered.forEach(task => {
          const taskEl = document.createElement('div');
          taskEl.className = `task ${task.status}`;
          taskEl.setAttribute('draggable', 'true');
          taskEl.dataset.id = task.id;
          taskEl.innerHTML = `<strong>${task.title}</strong><br>Priority: ${task.priority}<br>Due: ${task.due_date}`;
          taskEl.addEventListener('dragstart', () => draggedTask = task);
          taskEl.addEventListener('click', () => openModal(task.id));
          col.appendChild(taskEl);
        });
      });
    }

    document.querySelectorAll('.task-list').forEach(column => {
      column.addEventListener('dragover', e => {
        e.preventDefault();
        column.classList.add('drag-over');
      });
      column.addEventListener('dragleave', () => column.classList.remove('drag-over'));
      column.addEventListener('drop', async () => {
        column.classList.remove('drag-over');
        if (draggedTask) {
          const newStatus = column.id;
          if (draggedTask.status !== newStatus) {
            await fetch(`/api/tasks/${draggedTask.id}`, {
              method: 'PATCH',
              headers: headers,
              body: JSON.stringify({ status: newStatus })
            });
            draggedTask.status = newStatus;
            renderTasks();
          }
        }
      });
    });

    function openModal(id) {
      const task = tasks.find(t => t.id == id);
      currentTaskId = id;
      document.getElementById('modal-title').value = task.title;
      document.getElementById('modal-description').value = task.description;
      document.getElementById('modal-status').value = task.status;
      document.getElementById('modal-priority').value = task.priority;
      document.getElementById('modal-due-date').value = task.due_date;
      document.getElementById('modal').style.display = 'flex';
    }

    function updateField(field, value) {
      fetch(`/api/tasks/${currentTaskId}`, {
        method: 'PATCH',
        headers: headers,
        body: JSON.stringify({ [field]: value })
      }).then(fetchTasks);
    }

    ['modal-title','modal-description','modal-status','modal-priority','modal-due-date'].forEach(id => {
      document.getElementById(id).addEventListener('blur', e => {
        const field = id.replace('modal-', '').replace('-', '_');
        updateField(field, e.target.value);
      });
    });

    function handleErrors(errors, prefix) {
      Object.keys(errors).forEach(key => {
        const el = document.getElementById(`${prefix}-error-${key}`);
        if (el) el.textContent = errors[key].join(', ');
      });
    }

    function clearErrors(prefix) {
      document.querySelectorAll(`[id^="${prefix}-error-"]`).forEach(el => el.textContent = '');
    }

    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    function confirmDeleteTask() {
      document.getElementById('delete-modal').style.display = 'flex';
    }

    async function deleteTask() {
      try {
        const res = await fetch(`/api/tasks/${currentTaskId}`, { method: 'DELETE', headers });
        if (res.status === 404) {
          alert('Task not found.');
        } else {
          document.getElementById('modal').style.display = 'none';
          document.getElementById('delete-modal').style.display = 'none';
          fetchTasks();
        }
      } catch (e) {
        alert('Error deleting task.');
      }
    }

    function openCreateModal(status) {
      document.getElementById('create-status').value = status;
      document.getElementById('create-title').value = '';
      document.getElementById('create-description').value = '';
      document.getElementById('create-priority').value = 'Medium';
      document.getElementById('create-due-date').value = '';
      document.getElementById('create-modal').style.display = 'flex';
    }

    function createTask() {
      const task = {
        title: document.getElementById('create-title').value,
        description: document.getElementById('create-description').value,
        status: document.getElementById('create-status').value,
        priority: document.getElementById('create-priority').value,
        due_date: document.getElementById('create-due-date').value,
      };
      fetch('/api/tasks', {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(task)
      }).then(() => {
        document.getElementById('create-modal').style.display = 'none';
        fetchTasks();
      });
    }

    window.onclick = e => {
      if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
      }
    };

    fetchTasks();
  </script>
</body>
</html>
