<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Management Board</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
    h1 { text-align: center; margin-bottom: 20px;}
    .board { display: flex; padding: 20px; gap: 20px; overflow-x: auto; }
    .column { flex: 1; background: #fff; border-radius: 8px; padding: 10px; display: flex; flex-direction: column; min-width: 250px; max-height: 100vh; overflow-y: auto; position: relative; }
    .column-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .column-title { font-size: 18px; font-weight: bold; }
    .column-count { background: #ccc; border-radius: 12px; padding: 2px 8px; font-size: 12px; }
    .add-task { cursor: pointer; font-size: 20px; margin-left: 10px; }
    .droppable { flex: 1; padding: 5px; transition: background 0.3s ease; }
    .droppable.hover { background: #c8e6c9; border-radius: 4px; }
    .task { background: #fff; border-radius: 6px; padding: 10px; margin-bottom: 10px; cursor: move; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .backlog .task { background: #ddd; }
    .todo .task { background: #fff; }
    .in_progress .task { background: #bbdefb; }
    .done .task { background: #c8e6c9; }
    .modal, .confirm-modal { display: none; position: fixed; top: 10%; left: 50%; transform: translateX(-50%); width: 400px; background: #fff; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 1000; padding: 20px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; }
    .modal-title { font-size: 18px; font-weight: bold; }
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
    .close-modal { cursor: pointer; font-size: 20px; }
    .modal-body { margin-top: 10px; }
    .form-group { margin-bottom: 10px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 4px; }
    .error { color: red; font-size: 12px; }
    .trash { color: red; cursor: pointer; font-size: 18px; margin-left: auto; }
    #no-more { text-align: center; color: #666; margin-top: 10px; display: none; }
  </style>
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
  <div id="no-more">No more tasks to display</div>

  <script>
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
    const columns = ['backlog', 'todo', 'in_progress', 'done'];
    const priorities = { low: 'Low', medium: 'Medium', high: 'High' };
    let tasks = [];
    let nextPageUrl = '/api/tasks';

    function createColumn(status) {
      const column = document.createElement('div');
      column.className = `column ${status}`;

      const header = document.createElement('div');
      header.className = 'column-header';
      header.innerHTML = `
        <span class="column-title">${status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</span>
        <span class="column-count" id="count-${status}">0</span>
        <span class="add-task" onclick="openCreateModal('${status}')">+</span>
      `;

      const droppable = document.createElement('div');
      droppable.className = 'droppable';
      droppable.dataset.status = status;
      droppable.ondragover = e => {
        e.preventDefault();
        droppable.classList.add('hover');
      };
      droppable.ondragleave = () => droppable.classList.remove('hover');
      droppable.ondrop = e => {
        const id = e.dataTransfer.getData('text');
        droppable.classList.remove('hover');
        updateTaskStatus(id, status);
      };

      column.appendChild(header);
      column.appendChild(droppable);
      document.getElementById('board').appendChild(column);
    }

    function renderTask(task) {
      const div = document.createElement('div');
      div.className = 'task';
      div.draggable = true;
      div.dataset.id = task.id;
      div.innerHTML = `
        <div><strong>${task.title}</strong></div>
        <div>Priority: ${priorities[task.priority]}</div>
        <div>Due: ${task.due_date}</div>
      `;
      div.ondragstart = e => e.dataTransfer.setData('text', task.id);
      div.onclick = () => openModal(task.id);
      document.querySelector(`.${task.status} .droppable`).appendChild(div);
    }

    async function updateTaskStatus(id, status) {
      try {
        await fetch(`/api/tasks/${id}`, {
          method: 'PATCH',
          headers: headers,
          body: JSON.stringify({ status })
        });
        tasks = [];
        loadTasks(true);
      } catch (err) {
        alert('Error updating status.');
      }
    }

    function retrieveUniqueTasks(newTasks) {
        const existingIds = new Set(tasks.map(t => t.id));
        uniqueTasks = [];
        newTasks.forEach(task => {
            if (!existingIds.has(task.id)) {
                uniqueTasks.push(task);
            }
        });

        return uniqueTasks;
    }

    async function loadTasks(reset = false) {
      if (reset) {
        document.getElementById('board').innerHTML = '';
        columns.forEach(createColumn);
        nextPageUrl = '/api/tasks';
      }
      if (!nextPageUrl) return;

      const res = await fetch(nextPageUrl, { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      nextPageUrl = data.links?.next;
      uniqueTasks = retrieveUniqueTasks(data.data);
        
      uniqueTasks.forEach(task => {
        tasks.push(task);
        renderTask(task);
      });

      updateCounts();

      if (!nextPageUrl) {
        const msg = document.getElementById('no-more');
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 5000);
      }
    }

    function updateCounts() {
      columns.forEach(col => {
        const count = document.querySelectorAll(`.${col} .task`).length;
        document.getElementById(`count-${col}`).textContent = count;
      });
    }

    window.onscroll = function () {
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
        loadTasks();
      }
    };

    function openCreateModal(status) {
      document.getElementById('create-status').value = status;
      document.getElementById('create-title').value = '';
      document.getElementById('create-description').value = '';
      document.getElementById('create-priority').value = 'medium';
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
        loadTasks();
      });
    }

    window.onclick = e => {
      if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
      }
    };
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
      }).then(loadTasks());
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
          tasks = [];
          loadTasks(true);
        }
      } catch (e) {
        alert('Error deleting task.');
      }
    }


    loadTasks(true);
  </script>
</body>
</html>
