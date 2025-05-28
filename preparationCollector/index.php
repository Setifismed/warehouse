<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if zone session variable exists, if not redirect to login
if ($_SESSION['type'] != 'rammasseur') {
    header("Location: login.php");
    exit();
}
include('../include/pgsql_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setifismed</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="styles/css/style.css" type="text/css">
</head>
<body>
<div class="container">
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-clipboard-list logo-icon"></i>
                <h1>Setifismed</h1>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard-pharmacy.html" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="inventaire-pharmacy.html"><i class="fas fa-tasks"></i> Inventaire</a></li>
            <li><a href="en_attente.html"><i class="fas fa-clock"></i> En Attente</a></li>
            <li><a href="en_cours.html"><i class="fas fa-spinner"></i> En Cours</a></li>
            <li><a href="terminer.html"><i class="fas fa-check-circle"></i> Terminer</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header>
            <div class="logo">
                <i class="fas fa-tasks"></i>
                <h1>Dashboard</h1>
            </div>
            <div class="header-right">
                <div class="datetime-container" id="currentDateTime"></div>
                <button class="btn" id="refreshBtn"><i class="fas fa-sync-alt"></i> Refresh</button>
                <div class="user-profile">
                    <div><div class="user-name"><?php echo($_SESSION['fullname'])?></div></div>
                </div>
            </div>
        </header>

        <div class="status-cards">
            <div class="status-card status-pending">
                <h3><i class="fas fa-clock"></i> En Attente</h3>
                <div class="count">24</div>
            </div>
            <div class="status-card status-inprogress">
                <h3><i class="fas fa-spinner"></i> En Cours</h3>
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
            <div class="input-field">
                <input type="text" id="barcode" placeholder=" " autocomplete="off" autofocus>
                <label for="barcode">Scan Barcode</label>
            </div>
        </div>

        <div id="verificationPopup" class="popup">
            <div class="popup-content">
                <span class="close-popup">&times;</span>
                <h3>Verify Barcode Scan</h3>
                <div class="verification-details">
                    <p><strong>Barcode:</strong> <span id="popupBarcode"></span></p>
                    <p><strong>Number:</strong> <span id="popupNumber"></span></p>
                    <p><strong>User:</strong> <span id="popupUser"></span></p>
                    <p><strong>Items in Basket:</strong> <span id="popupBasketCount">0</span></p>
                </div>
                <div class="popup-buttons">
                    <button id="confirmBtn" class="btn confirm">Confirm</button>
                    <button id="cancelBtn" class="btn cancel">Cancel</button>
                </div>
            </div>
        </div>
        <div class="table-container">
    <table id="mainTable">
        <thead>
            <tr>
                <th>N°</th>
                <th>Date</th>
                <th>Heure Création</th>
                <th>Heure Début</th>
                <th>État</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $today = date('Y-m-d');
        $query = 'SELECT * FROM public."ordersCollectors" WHERE rammaseur=$1 AND date=$2 ORDER BY id ASC';
        $result = pg_query_params($pg_conn, $query, array($_SESSION['fullname'], $today));
        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $id = htmlspecialchars($row['id']);
                $date = htmlspecialchars($row['date']);
                $heurCreation = htmlspecialchars($row['heurCreation']);
                $heurFin = $row['heurFin'] ? htmlspecialchars($row['heurFin']) : '-';
                $status = htmlspecialchars(trim($row['status']));
                $statusSlug = strtolower(str_replace(' ', '', $status));
                $statusIcon = 'clock';

                switch (strtolower($status)) {
                    case 'terminé':
                    case 'terminer':
                        $statusIcon = 'check-circle';
                        $statusSlug = 'completed';
                        break;
                    case 'en cours':
                        $statusIcon = 'spinner fa-pulse';
                        $statusSlug = 'inprogress';
                        break;
                    case 'en attente':
                        $statusIcon = 'clock';
                        $statusSlug = 'pending';
                        break;
                    case 'annulé':
                        $statusIcon = 'times-circle';
                        $statusSlug = 'cancelled';
                        break;
                }

                echo '<tr>';
                echo '<td>#' . $id . '</td>';
                echo '<td>' . $date . '</td>';
                echo '<td>' . $heurCreation . '</td>';
                echo '<td>' . $heurFin . '</td>';
                echo '<td><span class="status-badge badge-' . $statusSlug . '"><i class="fas fa-' . $statusIcon . '"></i> ' . ucfirst($status) . '</span></td>';
                echo '<td>
                        <button class="btn btn-sm btn-outline-primary me-1 view"
                                data-id="' . htmlspecialchars($row['documentID']) . '"
                                data-order-id="' . $id . '"
                                data-status="' . $status . '">
                            <i class="fas fa-eye"></i>
                        </button>
                      </td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6" class="text-center text-muted">Aucun ordre trouvé pour aujourd\'hui.</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Modal for zones -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Détails de la commande #<span id="orderNumber"></span></h2>
            <button class="close">&times;</button>
        </div>
        <div class="modal-body">
            <h3 class="section-title">Zones</h3>
            <div class="zones-container" id="zonesContainer"></div>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('detailsModal');
const closeModalButton = modal.querySelector('.close');
const zonesContainer = document.getElementById('zonesContainer');

let pollingInterval;
let currentDocumentID = null;

function fetchAndUpdateZones(documentID) {
    fetch(`getZones.php?documentID=${documentID}`)
        .then(res => res.json())
        .then(data => {
            const zones = ['A', 'B', 'C', 'D', 'E', 'F', 'L', 'P', 'K', 'J', 'I', 'H', 'G'];
            zonesContainer.innerHTML = '';

            let allValid = true;
            let hasBasket = false;

            zones.forEach(zone => {
                const zoneInfo = data.find(item => item.zone === zone);
                const basketNum = zoneInfo ? zoneInfo.basketnum : 0;
                const status = zoneInfo ? zoneInfo.status : null;

                const zoneCard = document.createElement('div');
                zoneCard.classList.add('zone-card');
                zoneCard.style.color = 'white';
                zoneCard.style.padding = '10px';
                zoneCard.style.margin = '5px';
                zoneCard.style.borderRadius = '5px';
                zoneCard.style.display = 'inline-block';
                zoneCard.style.minWidth = '60px';
                zoneCard.style.textAlign = 'center';

                if (basketNum > 0) {
                    hasBasket = true;

                    if (status && status.toLowerCase() === 'valide') {
                        zoneCard.style.backgroundColor = 'green';
                    } else if (
                        status &&
                        status.toLowerCase().includes('attente') &&
                        status.toLowerCase().includes('ramass')
                    ) {
                        zoneCard.style.backgroundColor = 'blue';
                        allValid = false;
                    } else {
                        zoneCard.style.backgroundColor = 'gray';
                        allValid = false;
                    }
                } else {
                    zoneCard.style.backgroundColor = 'gray';
                }

                zoneCard.innerHTML = `
                    <strong>${zone}</strong><br>
                    Paniers: ${basketNum}<br>
                    }
                `;
                zonesContainer.appendChild(zoneCard);
            });

            // Handle Print button logic
            let printBtn = document.getElementById('printBtn');
            if (!printBtn) {
                printBtn = document.createElement('button');
                printBtn.id = 'printBtn';
                printBtn.className = 'btn confirm';
                printBtn.textContent = 'Imprimer';
                printBtn.style.marginTop = '20px';
                printBtn.addEventListener('click', () => {
                    window.print(); // Replace with custom print logic if needed
                });
                zonesContainer.appendChild(printBtn);
            }

            // Show only if all non-zero zones are "valide"
            printBtn.style.display = (hasBasket && allValid) ? 'inline-block' : 'none';
        })
        .catch(error => console.error('Erreur de récupération des zones :', error));
}



function openModal(documentID) {
    currentDocumentID = documentID;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    fetchAndUpdateZones(documentID);
    pollingInterval = setInterval(() => {
        fetchAndUpdateZones(documentID);
    }, 5000);
}

function closeModal() {
    modal.classList.remove('active');
    document.querySelector('.selected-row')?.classList.remove('selected-row');
    document.body.style.overflow = 'auto';
    clearInterval(pollingInterval);
    pollingInterval = null;
}

closeModalButton.addEventListener('click', closeModal);

document.querySelectorAll('.view').forEach(button => {
    button.addEventListener('click', function () {
        const documentID = this.getAttribute('data-id');
        const status = this.getAttribute('data-status');
        const orderID = this.getAttribute('data-order-id');
        const row = this.closest('tr');
        
        // Console log the document details
        console.log('Clicked Order Details:', {documentID: documentID,orderID: orderID,status: status});

        if (status.toLowerCase() === 'en attente') {
            row.classList.add('selected-row');
            openModal(documentID);
            updateStatus(documentID, 'En cours');
        } 
    });
});

function updateStatus(documentID, newStatus) {
    fetch('updateStatus.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ documentID, newStatus }),
    })
    .then(response => {
        if (!response.ok) throw new Error('Status update failed');
        console.log('Status updated to', newStatus);
    })
    .catch(error => console.error('Erreur de mise à jour du statut :', error));
}
</script>
</body>
</html>