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
    // Order Management Setup
    setupViewButtons();
    setupModalClose();
    setupRefreshButton();
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Barcode Verification Setup
    const barcodeInput = document.getElementById('barcode');
    const verificationPopup = document.getElementById('verificationPopup');
    const popupBarcode = document.getElementById('popupBarcode');
    const popupNumber = document.getElementById('popupNumber');
    const popupUser = document.getElementById('popupUser');
    const closePopup = document.querySelector('.close-popup');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const currentUser = "Anis";

    if (barcodeInput) {
        barcodeInput.focus();
        let lastInputTime = 0;

        barcodeInput.addEventListener('input', function(e) {
            const now = new Date().getTime();
            const timeSinceLastInput = now - lastInputTime;

            if (timeSinceLastInput < 50 && this.value.length > 3) {
                showVerificationPopup(this.value);
                this.value = '';
            }
            lastInputTime = now;
        });

        function showVerificationPopup(barcode) {
            const randomNumber = Math.floor(100000 + Math.random() * 900000);
            popupBarcode.textContent = barcode;
            popupNumber.textContent = randomNumber;
            popupUser.textContent = currentUser;
            verificationPopup.style.display = 'flex';
        }

        closePopup.addEventListener('click', function() {
            verificationPopup.style.display = 'none';
            barcodeInput.focus();
        });

        confirmBtn.addEventListener('click', function() {
            alert('Barcode verified successfully!');
            verificationPopup.style.display = 'none';
            barcodeInput.focus();
        });

        cancelBtn.addEventListener('click', function() {
            verificationPopup.style.display = 'none';
            barcodeInput.focus();
        });

        window.addEventListener('click', function(e) {
            if (e.target === verificationPopup) {
                verificationPopup.style.display = 'none';
                barcodeInput.focus();
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target !== barcodeInput) {
                barcodeInput.focus();
            }
        });
    }
});

</script>
</body>
</html>