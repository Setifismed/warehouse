<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow | Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="zone-inventaire/dashboard-style-pharmacy.css">
</head>
<body>
<div class="container">
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-clipboard-list logo-icon"></i>
                <h1>TaskFlow</h1>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard-pharmacy.html" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="inventaire-pharmacy.html"><i class="fas fa-tasks"></i> Inventaire</a></li>
            <li><a href="en_attente.html"><i class="fas fa-clock"></i> En Attente</a></li>
            <li><a href="en_cours.html"><i class="fas fa-spinner"></i> En Cours</a></li>
            <li><a href="terminer.html"><i class="fas fa-check-circle"></i> Terminer</a></li>
            <li><a href="annuler.html"><i class="fas fa-times-circle"></i> Annuler</a></li>
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
                    <div class="user-avatar">A</div>
                    <div>
                        <div class="user-name">Anis</div>
                        <div class="user-role">Zone Name</div>
                    </div>
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
            <table>
                <thead>
                <tr>
                    <th>N°</th>
                    <th>Date</th>
                    <th>Heure Creation</th>
                    <th>Heure Début</th>
                    <th>Etat</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>19/05/2025</td>
                    <td>08:15:23</td>
                    <td>08:30:00</td>
                    <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminer</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>19/05/2025</td>
                    <td>09:45:12</td>
                    <td>10:00:00</td>
                    <td><span class="status-badge badge-inprogress"><i class="fas fa-spinner fa-spin"></i> En Cours</span></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>19/05/2025</td>
                    <td>10:30:45</td>
                    <td>-</td>
                    <td><span class="status-badge badge-pending"><i class="fas fa-clock"></i> En Attente</span></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>18/05/2025</td>
                    <td>14:22:10</td>
                    <td>-</td>
                    <td><span class="status-badge badge-cancelled"><i class="fas fa-times-circle"></i> Annuler</span></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>18/05/2025</td>
                    <td>16:05:33</td>
                    <td>16:15:00</td>
                    <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminer</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeInput = document.getElementById('barcode');
        const verificationPopup = document.getElementById('verificationPopup');
        const popupBarcode = document.getElementById('popupBarcode');
        const popupNumber = document.getElementById('popupNumber');
        const popupUser = document.getElementById('popupUser');
        const closePopup = document.querySelector('.close-popup');
        const confirmBtn = document.getElementById('confirmBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const currentUser = "Anis";

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
            document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });
    });
</script>
</body>
</html>
