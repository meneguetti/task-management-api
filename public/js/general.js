const headers = {
    "Content-Type": "application/json",
    Accept: "application/json",
};
const columns = ["backlog", "todo", "in_progress", "done"];
const priorities = { low: "Low", medium: "Medium", high: "High" };
let tasks = [];
let nextPageUrl = "/api/v1/tasks";
let nextPages = [];

function createColumn(status) {
    const column = document.createElement("div");
    column.className = `column ${status}`;

    const header = document.createElement("div");
    header.className = "column-header";
    header.innerHTML = `
        <span class="column-title">${status
            .replace(/_/g, " ")
            .replace(/\b\w/g, (c) => c.toUpperCase())}</span>
        <span class="column-count" id="count-${status}">0</span>
        <span class="add-task" onclick="openCreateModal('${status}')">+</span>
      `;

    const droppable = document.createElement("div");
    droppable.className = "droppable";
    droppable.dataset.status = status;
    droppable.ondragover = (e) => {
        e.preventDefault();
        droppable.classList.add("hover");
    };
    droppable.ondragleave = () => droppable.classList.remove("hover");
    droppable.ondrop = (e) => {
        const id = e.dataTransfer.getData("text");
        droppable.classList.remove("hover");
        updateTaskStatus(id, status);
    };

    column.appendChild(header);
    column.appendChild(droppable);
    document.getElementById("board").appendChild(column);
}

function renderTask(task) {
    const div = document.createElement("div");
    div.className = "task";
    div.draggable = true;
    div.dataset.id = task.id;
    div.innerHTML = `
        <div><strong>${task.title}</strong></div>
        <div>Priority: ${priorities[task.priority]}</div>
        <div>Due: ${task.due_date}</div>
      `;
    div.ondragstart = (e) => e.dataTransfer.setData("text", task.id);
    div.onclick = () => openModal(task.id);
    document.querySelector(`.${task.status} .droppable`).appendChild(div);
}

function updateRenderedTask(task) {
    const renderedTask = document.querySelectorAll(`[data-id="${task.id}"]`)[0] ?? null;
    
    if(renderedTask) {
        renderedTask.innerHTML = `
            <div><strong>${task.title}</strong></div>
            <div>Priority: ${priorities[task.priority]}</div>
            <div>Due: ${task.due_date}</div>
        `;
    }  
}

async function updateTaskStatus(id, status) {
    try {
        await fetch(`/api/v1/tasks/${id}`, {
            method: "PATCH",
            headers: headers,
            body: JSON.stringify({ status }),
        });
        tasks = [];
        notification("Task Updated!", "darkorange");
        loadTasks(true);
    } catch (err) {
        alert("Error updating status.");
    }
}

function retrieveUniqueTasks(newTasks) {
    const existingIds = new Set(tasks.map((t) => t.id));
    uniqueTasks = [];
    newTasks.forEach((task) => {
        if (!existingIds.has(task.id)) {
            uniqueTasks.push(task);
        }
    });

    return uniqueTasks;
}

async function loadTasks(reset = false) {
    if (reset) {
        document.getElementById("board").innerHTML = "";
        columns.forEach(createColumn);
        nextPageUrl = "/api/v1/tasks";
        nextPages = [];
    }
    if (!nextPageUrl) return;

    if (nextPages.includes(nextPageUrl)) return;
    nextPages.push(nextPageUrl);

    const res = await fetch(nextPageUrl, { headers: headers });
    const data = await res.json();
    nextPageUrl = data.links?.next;
    uniqueTasks = retrieveUniqueTasks(data.data);

    uniqueTasks.forEach((task) => {
        tasks.push(task);
        renderTask(task);
    });

    updateCounts();

    if (!nextPageUrl) {
        const msg = document.getElementById("no-more");
        msg.style.display = "block";
        setTimeout(() => (msg.style.display = "none"), 5000);
    }
}

function updateCounts() {
    columns.forEach((col) => {
        const count = document.querySelectorAll(`.${col} .task`).length;
        document.getElementById(`count-${col}`).textContent = count;
    });
}

window.onscroll = function () {
    if (
        window.innerHeight + window.scrollY >=
        document.body.offsetHeight - 100
    ) {
        loadTasks();
    }
};

function openCreateModal(status) {
    document.getElementById("create-status").value = status;
    document.getElementById("create-title").value = "";
    document.getElementById("create-description").value = "";
    document.getElementById("create-priority").value = "medium";
    document.getElementById("create-due-date").value = "";
    document.getElementById("create-modal").style.display = "flex";
}

async function createTask() {
    const task = {
        title: document.getElementById("create-title").value,
        description: document.getElementById("create-description").value,
        status: document.getElementById("create-status").value,
        priority: document.getElementById("create-priority").value,
        due_date: document.getElementById("create-due-date").value,
    };

    const res = await fetch("/api/v1/tasks/", {
        method: "POST",
        headers: headers,
        body: JSON.stringify(task),
    });
    const responseJson = await res.json();
    
    document.getElementById("create-modal").style.display = "none";
    notification("Task Created!", "green");
    
    const taskCreated = responseJson.data;
    tasks.push(taskCreated);
    renderTask(taskCreated);
    updateCounts();
}

function notification(text, background) {
    const notification = document.getElementById("notification");
    notification.style.display = "block";
    notification.style.background = background;
    notification.innerHTML = text;
    setTimeout(() => (notification.style.display = "none"), 5000);
}

window.onclick = (e) => {
    if (e.target.classList.contains("modal")) {
        e.target.style.display = "none";
    }
};
function openModal(id) {
    const task = tasks.find((t) => t.id == id);
    currentTaskId = id;
    document.getElementById("modal-title").value = task.title;
    document.getElementById("modal-description").value = task.description;
    document.getElementById("modal-status").value = task.status;
    document.getElementById("modal-priority").value = task.priority;
    document.getElementById("modal-due-date").value = task.due_date;
    document.getElementById("modal").style.display = "flex";
}

function updateField(field, value) {
    fetch(`/api/v1/tasks/${currentTaskId}`, {
        method: "PATCH",
        headers: headers,
        body: JSON.stringify({ [field]: value }),
    }).then(() => {
        notification("Task Updated!", "darkorange");
        tasks = [];
        loadTasks(true);
    });
}

[
    "modal-title",
    "modal-description",
    "modal-status",
    "modal-priority",
    "modal-due-date",
].forEach((id) => {
    document.getElementById(id).addEventListener("blur", (e) => {
        const field = id.replace("modal-", "").replace("-", "_");
        updateField(field, e.target.value);
    });
});

function handleErrors(errors, prefix) {
    Object.keys(errors).forEach((key) => {
        const el = document.getElementById(`${prefix}-error-${key}`);
        if (el) el.textContent = errors[key].join(", ");
    });
}

function clearErrors(prefix) {
    document
        .querySelectorAll(`[id^="${prefix}-error-"]`)
        .forEach((el) => (el.textContent = ""));
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

function confirmDeleteTask() {
    document.getElementById("delete-modal").style.display = "flex";
}

async function deleteTask() {
    try {
        const res = await fetch(`/api/v1/tasks/${currentTaskId}`, {
            method: "DELETE",
            headers,
        });
        if (res.status === 404) {
            alert("Task not found.");
        } else {
            document.getElementById("modal").style.display = "none";
            document.getElementById("delete-modal").style.display = "none";
            notification("Task Deleted!", "red");
            tasks = [];
            loadTasks(true);
        }
    } catch (e) {
        alert("Error deleting task.");
    }
}

function openFilterModal() {
    document.getElementById("filter-modal").style.display = "block";
}

function closeFilterModal() {
    document.getElementById("filter-modal").style.display = "none";
}

async function applyTaskFilter() {
    const title = document.getElementById("filter-title").value.trim();
    const status = [
        ...document.querySelectorAll('input[name="status"]:checked'),
    ].map((cb) => cb.value);
    const priority = [
        ...document.querySelectorAll('input[name="priority"]:checked'),
    ].map((cb) => cb.value);
    const dueFrom = document.getElementById("due-from").value;
    const dueTo = document.getElementById("due-to").value;

    const hasInput =
        title || status.length || priority.length || dueFrom || dueTo;

    const params = new URLSearchParams();
    if (title) params.append("title", title);
    status.forEach((s) => params.append("status[]", s));
    priority.forEach((p) => params.append("priority[]", p));
    if (dueFrom) params.append("due_date_from", dueFrom);
    if (dueTo) params.append("due_date_to", dueTo);

    try {
        const response = await fetch(`/api/v1/tasks?${params.toString()}`, {
            headers: headers,
        });
        if (!response.ok) throw response;

        const data = await response.json();
        tasks = data.data; // reset current task list
        document.getElementById("board").innerHTML = "";
        columns.forEach(createColumn);
        tasks.forEach((task) => renderTask(task));
        nextPageUrl = data.links?.next;
        updateCounts();
        closeFilterModal();
    } catch (err) {
        if (err.status === 422) {
            alert("Validation failed while filtering tasks.");
        } else {
            alert("Failed to load filtered tasks.");
        }
    }
}

loadTasks(true);
