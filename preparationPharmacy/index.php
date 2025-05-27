<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if zone session variable exists, if not redirect to login
if (!isset($_SESSION['zone'])) {
    header("Location: login.php");
    exit();
}

$zone = $_SESSION['zone'];
include('../include/pgsql_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepataion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* General Button Base Style */
        .btn,
        .btn-outline,
        .btn-action {
            font-family: 'Inter', sans-serif;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Default Button */
        .btn {
            background-color: #4CAF50;
            color: white;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Outline Button */
        .btn-outline {
            background-color: transparent;
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .btn-outline:hover {
            background-color: #4CAF50;
            color: white;
        }

        /* Action Buttons */
        .btn-action {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 6px;
        }

        .btn-action:hover {
            background-color: #2980b9;
        }

        /* Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 80%; 
            max-width: 500px; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .loading {
            display: none; 
            text-align: center; 
            font-size: 1.2rem; 
            margin-top: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-terminer {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-en cours {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-en attente {
            background-color: #cce5ff;
            color: #004085;
        }
        .modal-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.modal-actions button {
    padding: 8px 16px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-actions .btn-start {
    background-color: #4CAF50;
    color: white;
}

.modal-actions .btn-start:hover {
    background-color: #45a049;
}

.modal-actions .btn-finish {
    background-color: #2196F3;
    color: white;
}

.modal-actions .btn-finish:hover {
    background-color: #0b7dda;
}
    </style>
    <script>
        const userZone = "<?php echo htmlspecialchars($zone); ?>";
    </script>
</head>
<body>
<div class="container">
    <?php include("sidemenu.php"); ?>

    <main class="main-content">
        <header>
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h1>Dashboard</h1>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="user-avatar"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($_SESSION['zone']); ?></div>
                    </div>
                </div>
            </div>
        </header>

       <div class="status-cards">
            <div class="status-card status-pending">
                <h3><i class="fas fa-hourglass-start"></i> En Attente</h3>
                <div class="count">24</div>
            </div>
            <div class="status-card status-inprogress">
                <h3><i class="fas fa-spinner fa-spin"></i> En Cours</h3>
                <div class="count">12</div>
            </div>
            <div class="status-card status-completed">
                <h3><i class="fas fa-check-circle"></i> Terminer</h3>
                <div class="count">156</div>
            </div>
            <div class="status-card status-cancelled">
                <h3><i class="fas fa-times-circle"></i> Annuler</h3>
                <div class="count">8</div>
            </div>
        </div>

        <div class="input-section">
            <h2><i class="fas fa-barcode"></i> Task Scanner</h2>
            <div class="input-group">
                <div class="input-field">
                    <input type="text" id="barcode" placeholder=" " autocomplete="off">
                    <label for="barcode">Scan Barcode</label>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Heure Creation</th>
                        <th>Heure Début</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                
                $query = 'SELECT * FROM public."ordersPharmacy" WHERE zone=$1 ORDER BY id ASC';
                $result = pg_query_params($pg_conn, $query, array($zone));

                if ($result && pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $id = htmlspecialchars($row['id']);
                        $date = htmlspecialchars($row['date']);
                        $heurCreation = htmlspecialchars($row['heurCreation']);
                        $heurFin = htmlspecialchars($row['heurFin']);
                        $status = htmlspecialchars($row['status']);
                        $statusSlug = strtolower(str_replace(' ', '-', $status));
                        $statusIcon = 'hourglass-start';

                        if ($status === 'Terminer') {
                            $statusIcon = 'check-circle';
                        } elseif ($status === 'En Cours') {
                            $statusIcon = 'spinner fa-spin';
                        }

                        echo '<tr>';
                        echo '<td class="fw-semibold text-primary">#' . $id . '</td>';
                        echo '<td>' . $date . '</td>';
                        echo '<td>' . $heurCreation . '</td>';
                        echo '<td>' . $heurFin . '</td>';
                        echo '<td><span class="status-badge badge-' . $statusSlug . '"><i class="fas fa-' . $statusIcon . '"></i> ' . ucfirst($status) . '</span></td>';
                        echo '<td>
                                <button class="btn btn-sm btn-outline-primary me-1 view"
                                        data-id="' . htmlspecialchars($row['documentID']) . '"
                                        data-order-id="' . $id . '"
                                        data-status="' . htmlspecialchars(trim($status)) . '">
                                    <i class="fas fa-eye"></i>
                                </button>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center text-muted">Aucun ordre trouvé.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal for Product Details -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Order Details</h2>
            <div id="productDetails"></div>
            <div id="timerContainer" class="timer-container" style="display: none;">
                <div class="timer-label">Task Duration</div>
                <div id="timerDisplay" class="timer-display">00:00:00</div>
            </div>
            <div id="modalActions" class="modal-actions"></div>
        </div>
    </div>
</div>

<script>
    let timerInterval = null;
    let startTime = null;
    let currentOrderId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // View button click handlers
        setupViewButtons();

        // Close modal functionality
        setupModalClose();

        // Refresh button functionality
        setupRefreshButton();

        // Initialize and update datetime every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });

    // Set up all view buttons
    function setupViewButtons() {
        document.querySelectorAll('.view').forEach(button => {
            button.addEventListener('click', function() {
                const documentID = this.getAttribute('data-id');
                const orderId = this.getAttribute('data-order-id');
                const status = this.getAttribute('data-status');
                currentOrderId = orderId;
                showOrderDetails(documentID, orderId, status);
            });
        });
    }

    // Show order details in modal
    function showOrderDetails(documentID, orderId, status) {
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'loading';
        loadingIndicator.innerText = 'Loading...';
        document.getElementById('productDetails').innerHTML = '';
        document.getElementById('productDetails').appendChild(loadingIndicator);
        document.getElementById('productModal').style.display = 'flex';

        fetch(`fetchItems.php?DocumentID=${documentID}&zone=${userZone}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.style.display = 'none';
                displayOrderData(documentID, status, data);
                setupStatusButtons(documentID, orderId, status);

                // Show timer if status is "En Cours"
                if (status === 'En Cours') {
                    showTimer();
                    startTimer();
                } else {
                    hideTimer();
                }
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                console.error('Error:', error);
                showErrorInModal('Error loading order details. Please try again.');
            });
    }

    // Display order data in modal
    function displayOrderData(documentID, status, data) {
        const statusClass = status.toLowerCase().replace(/\s/g, '-');
        let productDetails = `
            <div class="order-header">
                <p><strong>Document ID:</strong> ${documentID}</p>
                <p><strong>Status:</strong>
                    <span class="status-badge badge-${statusClass}">
                        <i class="fas fa-${getStatusIcon(status)}"></i>
                        ${status}
                    </span>
                </p>
            </div>
            <table class="products-table">
                <thead>
                    <tr><th>Product Name</th><th>Quantity</th></tr>
                </thead>
                <tbody>`;

        if (data.success && data.products.length > 0) {
            data.products.forEach(product => {
                productDetails += `<tr><td>${product.name}</td><td>${product.quantity}</td></tr>`;
            });
        } else {
            productDetails += `<tr><td colspan="2">${data.error || 'No products found'}</td></tr>`;
        }

        productDetails += `</tbody></table>`;
        document.getElementById('productDetails').innerHTML = productDetails;
    }

    // Set up status action buttons based on current status
    function setupStatusButtons(documentID, orderId, status) {
        const modalActions = document.getElementById('modalActions');
        modalActions.innerHTML = '';

        // Clean up status string
        const cleanStatus = status.trim().toLowerCase();

        // Add action buttons based on status
        if (cleanStatus === 'en attente' || cleanStatus.includes('attente')) {
            // Only show buttons for "En attente" status
            if (!cleanStatus.includes('ramassing')) {
                addActionButton(modalActions, 'Start', 'play', () => {
                    updateStatusAndStartTimer(documentID, 'En Cours');
                }, 'btn-start');

                addActionButton(modalActions, 'Cancel', 'times', () => {
                    updateStatus(documentID, 'Annuler');
                }, 'btn-cancel');
            }
        }
        else if (cleanStatus === 'en cours' || cleanStatus.includes('cours')) {
            addActionButton(modalActions, 'Finish', 'flag-checkered', () => {
                showFinishForm(documentID);
            }, 'btn-finish');
        }
        else if (cleanStatus === 'terminer' || cleanStatus === 'annuler' || cleanStatus.includes('terminer') || cleanStatus.includes('annuler')) {
            addActionButton(modalActions, 'Close', 'times', () => {
                document.getElementById('productModal').style.display = 'none';
            }, 'btn-close');
        }
        else if (cleanStatus.includes('ramassing')) {
            // No buttons for "En attente de ramassing" status
        }
        else {
            // Show debug info for unknown status
            const debugInfo = document.createElement('div');
            debugInfo.style.cssText = 'background: #fff3cd; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #ffeaa7;';
            debugInfo.innerHTML = `
                <strong>Debug Info:</strong><br>
                Raw Status: "${status}"<br>
                Cleaned Status: "${cleanStatus}"<br>
                Length: ${status.length}<br>
                Please check the database status values.`;
            modalActions.appendChild(debugInfo);

            addActionButton(modalActions, 'Close', 'times', () => {
                document.getElementById('productModal').style.display = 'none';
            }, 'btn-close');
        }
    }

    // Helper function to add action buttons
    function addActionButton(container, text, icon, onClick, className = '') {
        const button = document.createElement('button');
        button.className = `btn btn-action ${className}`;
        button.innerHTML = `<i class="fas fa-${icon}"></i> ${text}`;
        button.onclick = onClick;
        container.appendChild(button);
    }

    // Helper function to get appropriate icon for status
    function getStatusIcon(status) {
        switch(status.toLowerCase()) {
            case 'terminer': return 'check-circle';
            case 'en cours': return 'spinner';
            case 'annuler': return 'times-circle';
            default: return 'hourglass-start';
        }
    }

    // Update order status and start timer
    function updateStatusAndStartTimer(documentID, newStatus) {
        fetch('updateStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ documentID: documentID, newStatus: newStatus })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                // Update the modal content
                showTimer();
                startTimer();

                // Remove cancel button and update buttons - only show Finish button
                const modalActions = document.getElementById('modalActions');
                modalActions.innerHTML = '';
                addActionButton(modalActions, 'Finish', 'flag-checkered', () => {
                    showFinishForm(documentID);
                }, 'btn-finish');

                // Update status display in modal
                const statusBadge = document.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = 'status-badge badge-en-cours';
                    statusBadge.innerHTML = '<i class="fas fa-spinner"></i> En Cours';
                }
            } else {
                alert('Error updating status: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error. Please try again.');
        });
    }

    // Update order status
    function updateStatus(documentID, newStatus) {
        fetch('updateStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ documentID: documentID, newStatus: newStatus })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (newStatus === 'Terminer') {
                    alert('Task completed successfully!');
                } else if (newStatus === 'Annuler') {
                    alert('Task cancelled successfully!');
                }
                location.reload();
            } else {
                alert('Error updating status: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error. Please try again.');
        });
    }

    // Show finish form with input for number of baskets
    function showFinishForm(documentID) {
        const modalActions = document.getElementById('modalActions');

        // Create finish form
        const finishForm = document.createElement('div');
        finishForm.className = 'finish-form';
        finishForm.innerHTML = `
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="numberOFPanier" style="display: block; margin-bottom: 5px; font-weight: 500;">
                    Number of Baskets (Nombre de Paniers):
                </label>
                <input type="number"
                       id="numberOFPanier"
                       name="numberOFPanier"
                       min="1"
                       required
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                       placeholder="Enter number of baskets">
            </div>`;

        // Clear modal actions and add form
        modalActions.innerHTML = '';
        modalActions.appendChild(finishForm);

        // Add finish button
        addActionButton(modalActions, 'Complete Task', 'check', () => {
            const numberOFPanier = document.getElementById('numberOFPanier').value;

            if (!numberOFPanier || numberOFPanier < 1) {
                alert('Please enter a valid number of baskets');
                return;
            }

            completeTask(documentID, numberOFPanier);
        }, 'btn-finish');

        // Add cancel button
        addActionButton(modalActions, 'Cancel', 'times', () => {
            // Go back to just showing the finish button
            modalActions.innerHTML = '';
            addActionButton(modalActions, 'Finish', 'flag-checkered', () => {
                showFinishForm(documentID);
            }, 'btn-finish');
        }, 'btn-cancel');
    }

    // Complete task with basket number
    function completeTask(documentID, numberOFPanier) {
        stopTimer();

        fetch('updateStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                documentID: documentID,
                newStatus: 'En attente de ramassing',
                numberOFPanier: parseInt(numberOFPanier),
                nbrColier: parseInt(numberOFPanier) // nbrColier is the number user entered
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Task completed successfully! Baskets: ' + numberOFPanier);
                location.reload();
            } else {
                alert('Error completing task: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error. Please try again.');
        });
    }

    // Timer functions
    function showTimer() {
        document.getElementById('timerContainer').style.display = 'block';
    }

    function hideTimer() {
        document.getElementById('timerContainer').style.display = 'none';
        stopTimer();
    }

    function startTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }

        startTime = new Date();
        timerInterval = setInterval(updateTimerDisplay, 1000);
    }

    function stopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }

    function updateTimerDisplay() {
        if (!startTime) return;

        const now = new Date();
        const elapsed = now - startTime;

        const hours = Math.floor(elapsed / 3600000);
        const minutes = Math.floor((elapsed % 3600000) / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);

        const timeString =
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        document.getElementById('timerDisplay').textContent = timeString;
    }

    // Show error message in modal
    function showErrorInModal(message) {
        document.getElementById('productDetails').innerHTML = `
            <div class="error-message">
                ${message}
            </div>`;
    }

    // Set up modal close functionality
    function setupModalClose() {
        document.querySelector('.close').addEventListener('click', function() {
            hideTimer();
            document.getElementById('productModal').style.display = 'none';
        });

        window.onclick = function(event) {
            if (event.target === document.getElementById('productModal')) {
                hideTimer();
                document.getElementById('productModal').style.display = 'none';
            }
        };

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                hideTimer();
                document.getElementById('productModal').style.display = 'none';
            }
        });
    }

    // Set up refresh button
    function setupRefreshButton() {
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });
        }
    }

    // Update current date and time
    function updateDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        const dateTimeElement = document.getElementById('currentDateTime');
        if (dateTimeElement) {
            dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
        }
    }
</script>
</body>
</html>
