<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Transfer Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/change-tasks.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h3>LogiTrack</h3>
        <p class="text-muted">Gestion Logistique</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard-preparation.html"><i class="bi bi-speedometer2"></i>Home</a></li>
        <li><a href="equipe-preparation.html"><i class="bi bi-people"></i>Equipe</a></li>
        <li><a href="change-tasks.html"><i class="bi bi-people-fill"></i>Clients</a></li>
    </ul>
</div>
<div class="container">
    <header>
        <div class="logo">TaskTransfer</div>
        <div class="user-profile">
            <div class="avatar">JS</div>
            <span>John Smith</span>
        </div>
    </header>

    <main class="main-content">
        <section class="user-selection fade-in">
            <h2><i class="fas fa-user-circle"></i> Select User to Transfer From</h2>
            <select id="user-select-from" class="user-select-dropdown">
                <option value="">-- Select a user --</option>
                <option value="MS">Maria Sanchez</option>
                <option value="AJ">Alex Johnson</option>
                <option value="SP">Sarah Parker</option>
                <option value="DK">David Kim</option>
                <option value="TM">Taylor Moore</option>
            </select>
        </section>

        <section class="task-list fade-in" id="task-list" style="display:none;">
            <h2><i class="fas fa-tasks"></i> User Tasks</h2>
            <div class="task-list-actions">
                <button class="btn btn-sm btn-outline" id="select-all-btn">
                    <i class="fas fa-check-square"></i> Select All
                </button>
                <button class="btn btn-sm btn-outline" id="deselect-all-btn">
                    <i class="fas fa-square"></i> Deselect All
                </button>
            </div>
            <!-- Tasks will be dynamically populated here based on selected user -->
        </section>

        <section class="user-selection-to fade-in">
            <h2><i class="fas fa-user-friends"></i> Select User to Transfer To</h2>
            <select id="user-select-to" class="user-select-dropdown" style="display:none;">
                <option value="">-- Select a user --</option>
                <option value="MS">Maria Sanchez</option>
                <option value="AJ">Alex Johnson</option>
                <option value="SP">Sarah Parker</option>
                <option value="DK">David Kim</option>
                <option value="TM">Taylor Moore</option>
            </select>
        </section>

        <section class="transfer-section fade-in" id="transfer-section" style="display:none;">
            <h2><i class="fas fa-exchange-alt"></i> Transfer Selected Tasks</h2>
            <div class="selected-tasks-container empty" id="selected-tasks-transfer-container">
                <span>No tasks selected</span>
            </div>

            <div class="form-group">
                <label for="transfer-notes"><i class="fas fa-sticky-note"></i> Transfer Notes</label>
                <textarea id="transfer-notes" rows="4" placeholder="Add any additional context or instructions for this task transfer..."></textarea>
            </div>

            <div class="action-buttons">
                <button class="btn btn-secondary" id="cancel-btn">Cancel</button>
                <button class="btn btn-primary" id="transfer-btn">Transfer Tasks</button>
            </div>
        </section>
    </main>
</div>

<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toast-message">Tasks transferred successfully!</span>
</div>
<script src="js/change-tasks.js"></script>
</body>
</html>