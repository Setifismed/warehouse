const tasksByUser = {
    MS: [
        { title: "Complete project proposal", details: "Due: May 25, 2023 • High priority" },
        { title: "Review team submissions", details: "Due: May 28, 2023 • Medium priority" },
        { title: "Update client documentation", details: "Due: June 1, 2023 • Medium priority" }
    ],
    AJ: [
        { title: "Schedule client meeting", details: "Due: May 30, 2023 • Low priority" },
        { title: "Prepare quarterly report", details: "Due: June 5, 2023 • High priority" }
    ],
    SP: [
        { title: "Onboard new team member", details: "Due: May 27, 2023 • High priority" },
        { title: "Update marketing materials", details: "Due: June 3, 2023 • Medium priority" }
    ],
    DK: [
        { title: "Fix critical bugs", details: "Due: ASAP • High priority" },
        { title: "Code review", details: "Due: May 29, 2023 • Medium priority" }
    ],
    TM: [
        { title: "Customer support tickets", details: "Due: Daily • Medium priority" },
        { title: "Product demo preparation", details: "Due: June 2, 2023 • High priority" }
    ]
};

document.addEventListener('DOMContentLoaded', function() {
    const userSelectFrom = document.getElementById('user-select-from');
    const userSelectTo = document.getElementById('user-select-to');
    const taskList = document.getElementById('task-list');
    const transferSection = document.getElementById('transfer-section');
    const selectedTasksTransferContainer = document.getElementById('selected-tasks-transfer-container');
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    const selectedTasks = new Set();

    userSelectFrom.addEventListener('change', function() {
        const userId = this.value;
        selectedTasks.clear(); // Clear selections when changing user
        updateSelectedTasksDisplay();

        // Hide transfer section when changing source user
        transferSection.style.display = 'none';
        userSelectTo.value = '';
        userSelectTo.style.display = 'none';

        if (userId) {
            // Show task list with animation
            taskList.style.display = 'block';
            setTimeout(() => {
                taskList.classList.add('fade-in');
            }, 10);

            // Show "to" dropdown with animation
            userSelectTo.style.display = 'block';
            setTimeout(() => {
                document.querySelector('.user-selection-to').classList.add('fade-in');
            }, 10);

            renderTasks(userId);
        } else {
            // Hide sections with animation
            taskList.classList.remove('fade-in');
            setTimeout(() => {
                taskList.style.display = 'none';
            }, 300);
        }
    });

    userSelectTo.addEventListener('change', function() {
        if (this.value) {
            transferSection.style.display = 'block';
            setTimeout(() => {
                transferSection.classList.add('fade-in');
            }, 10);
        } else {
            transferSection.classList.remove('fade-in');
            setTimeout(() => {
                transferSection.style.display = 'none';
            }, 300);
        }
    });

    // Select All button functionality
    selectAllBtn.addEventListener('click', function() {
        const userId = userSelectFrom.value;
        if (!userId) return;

        const tasks = tasksByUser[userId] || [];
        tasks.forEach(task => {
            selectedTasks.add(task.title);
        });

        // Update UI to show all tasks as selected
        document.querySelectorAll('.task-item').forEach(item => {
            item.classList.add('multi-selected');
        });

        updateSelectedTasksDisplay();
        showToast(`All ${tasks.length} tasks selected`, 'success');
    });

    // Deselect All button functionality
    deselectAllBtn.addEventListener('click', function() {
        selectedTasks.clear();

        // Update UI to show all tasks as deselected
        document.querySelectorAll('.task-item').forEach(item => {
            item.classList.remove('multi-selected');
        });

        updateSelectedTasksDisplay();
        showToast('All tasks deselected', 'success');
    });

    function renderTasks(userId) {
        taskList.innerHTML = `
            <h2><i class="fas fa-tasks"></i> User Tasks</h2>
            <div class="task-list-actions">
                <button class="btn btn-sm btn-outline" id="select-all-btn">
                    <i class="fas fa-check-square"></i> Select All
                </button>
                <button class="btn btn-sm btn-outline" id="deselect-all-btn">
                    <i class="fas fa-square"></i> Deselect All
                </button>
            </div>
        `;

        // Reattach event listeners directly to buttons after rendering
        document.getElementById('select-all-btn').addEventListener('click', selectAllTasks);
        document.getElementById('deselect-all-btn').addEventListener('click', deselectAllTasks);

        const tasks = tasksByUser[userId] || [];

        if (tasks.length === 0) {
            taskList.innerHTML += '<p style="color: var(--gray); text-align: center; padding: 20px;">No tasks found for this user</p>';
            return;
        }

        tasks.forEach((task, index) => {
            const taskItem = document.createElement('div');
            taskItem.className = 'task-item';
            if (selectedTasks.has(task.title)) {
                taskItem.classList.add('multi-selected');
            }
            taskItem.innerHTML = `
                <div class="task-title">${task.title}</div>
                <div class="task-details">${task.details}</div>
            `;

            taskItem.style.animationDelay = `${index * 0.05}s`;
            taskItem.classList.add('fade-in');

            taskItem.addEventListener('click', function() {
                const taskTitle = task.title;
                if (selectedTasks.has(taskTitle)) {
                    selectedTasks.delete(taskTitle);
                    taskItem.classList.remove('multi-selected');
                } else {
                    selectedTasks.add(taskTitle);
                    taskItem.classList.add('multi-selected');
                }
                updateSelectedTasksDisplay();
            });
            taskList.appendChild(taskItem);
        });
    }

    function selectAllTasks() {
        const userId = userSelectFrom.value;
        if (!userId) return;

        const tasks = tasksByUser[userId] || [];
        tasks.forEach(task => selectedTasks.add(task.title));

        document.querySelectorAll('.task-item').forEach(item => item.classList.add('multi-selected'));
        updateSelectedTasksDisplay();
        showToast(`All ${tasks.length} tasks selected`, 'success');
    }

    function deselectAllTasks() {
        selectedTasks.clear();
        document.querySelectorAll('.task-item').forEach(item => item.classList.remove('multi-selected'));
        updateSelectedTasksDisplay();
        showToast('All tasks deselected', 'success');
    }
    function updateSelectedTasksDisplay() {
        selectedTasksTransferContainer.innerHTML = '';

        if (selectedTasks.size === 0) {
            selectedTasksTransferContainer.classList.add('empty');
            selectedTasksTransferContainer.innerHTML = '<span>No tasks selected</span>';
            return;
        }

        selectedTasksTransferContainer.classList.remove('empty');

        selectedTasks.forEach(taskTitle => {
            const taskTag = document.createElement('div');
            taskTag.className = 'selected-task-tag';
            taskTag.innerHTML = `${taskTitle} <button data-task="${taskTitle}"><i class="fas fa-times"></i></button>`;
            selectedTasksTransferContainer.appendChild(taskTag);

            taskTag.querySelector('button').addEventListener('click', function(e) {
                e.stopPropagation();
                const taskToRemove = this.getAttribute('data-task');
                selectedTasks.delete(taskToRemove);

                document.querySelectorAll('.task-item').forEach(item => {
                    if (item.querySelector('.task-title').textContent === taskToRemove) {
                        item.classList.remove('multi-selected');
                    }
                });

                updateSelectedTasksDisplay();
            });
        });
    }

    document.getElementById('transfer-btn').addEventListener('click', function() {
        const tasksList = Array.from(selectedTasks);
        const userFrom = userSelectFrom.options[userSelectFrom.selectedIndex].text;
        const userTo = userSelectTo.options[userSelectTo.selectedIndex].text;
        const notes = document.getElementById('transfer-notes').value;

        if (tasksList.length === 0) {
            showToast('Please select at least one task to transfer', 'error');
            return;
        }

        if (!userFrom || !userTo) {
            showToast('Please select both source and destination users', 'error');
            return;
        }

        // In a real app, you would send this data to your backend
        const transferData = {
            from: userFrom,
            to: userTo,
            tasks: tasksList,
            notes: notes
        };
        console.log('Transfer data:', transferData);

        const tasksText = tasksList.length > 1 ?
            `${tasksList.length} tasks` :
            `"${tasksList[0]}"`;

        showToast(`${tasksText} transferred to ${userTo}`, 'success');

        // Reset form
        selectedTasks.clear();
        userSelectFrom.value = '';
        userSelectTo.value = '';
        document.getElementById('transfer-notes').value = '';
        taskList.style.display = 'none';
        transferSection.style.display = 'none';
        updateSelectedTasksDisplay();
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        selectedTasks.clear();
        userSelectFrom.value = '';
        userSelectTo.value = '';
        document.getElementById('transfer-notes').value = '';
        taskList.style.display = 'none';
        transferSection.style.display = 'none';
        updateSelectedTasksDisplay();
    });

    function showToast(message, type) {
        toastMessage.textContent = message;
        const icon = toast.querySelector('i');

        // Update style based on type
        if (type === 'error') {
            toast.style.backgroundColor = '#f72585';
            icon.className = 'fas fa-exclamation-circle';
        } else {
            toast.style.backgroundColor = '#4361ee';
            icon.className = 'fas fa-check-circle';
        }

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
});