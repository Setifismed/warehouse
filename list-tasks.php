<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
            --gray: #6b7280;
            --gray-light: #e5e7eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            text-align: left;
            padding: 1rem 1.25rem;
            position: sticky;
            top: 0;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-light);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-completed {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-inprogress {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-pending {
            background-color: rgba(156, 163, 175, 0.1);
            color: var(--gray);
        }

        .badge-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .action-icon {
            cursor: pointer;
            color: var(--primary);
            font-size: 1.25rem;
            transition: transform 0.2s, color 0.2s;
        }

        .action-icon:hover {
            color: #3a56d4;
            transform: scale(1.1);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            z-index: 101;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: slideDown 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-light);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: var(--danger);
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-light);
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--gray-light);
        }

        .btn-outline:hover {
            background-color: var(--gray-light);
        }

        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .confirmed {
            background-color: rgba(16, 185, 129, 0.05);
        }

        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: var(--gray-light);
            color: var(--gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }

        .step-active .step-number {
            background-color: var(--primary);
            color: white;
        }

        .step-completed .step-number {
            background-color: var(--success);
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: var(--gray);
            text-align: center;
        }

        .step-active .step-label {
            color: var(--primary);
            font-weight: 500;
        }

        .progress-line {
            position: absolute;
            top: 1rem;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--gray-light);
            z-index: 0;
        }

        .progress-fill {
            height: 100%;
            background-color: var(--primary);
            transition: width 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            th, td {
                padding: 0.75rem;
            }

            .modal {
                width: 95%;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Liste des Commandes</h2>
            <div>
                <button class="btn btn-outline">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table>
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
                <tr>
                    <td>1</td>
                    <td>19/05/2025</td>
                    <td>08:15:23</td>
                    <td>08:30:00</td>
                    <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminé</span></td>
                    <td><i class="fas fa-plus-circle action-icon" onclick="openPopup()"></i></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>19/05/2025</td>
                    <td>09:45:12</td>
                    <td>10:00:00</td>
                    <td><span class="status-badge badge-inprogress"><i class="fas fa-spinner fa-spin"></i> En Cours</span></td>
                    <td><i class="fas fa-plus-circle action-icon" onclick="openPopup()"></i></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>19/05/2025</td>
                    <td>10:30:45</td>
                    <td>-</td>
                    <td><span class="status-badge badge-pending"><i class="fas fa-clock"></i> En Attente</span></td>
                    <td><i class="fas fa-plus-circle action-icon" onclick="openPopup()"></i></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>18/05/2025</td>
                    <td>14:22:10</td>
                    <td>-</td>
                    <td><span class="status-badge badge-cancelled"><i class="fas fa-times-circle"></i> Annulé</span></td>
                    <td><i class="fas fa-plus-circle action-icon" onclick="openPopup()"></i></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>18/05/2025</td>
                    <td>16:05:33</td>
                    <td>16:15:00</td>
                    <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminé</span></td>
                    <td><i class="fas fa-plus-circle action-icon" onclick="openPopup()"></i></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="overlay" id="overlay"></div>

<div class="modal" id="modal">
    <div class="modal-header">
        <h3 class="modal-title">Détails des Produits</h3>
        <button class="modal-close" onclick="closePopup()">&times;</button>
    </div>

    <div class="progress-tracker">
        <div class="progress-line">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-step step-active" id="step1">
            <div class="step-number">1</div>
            <div class="step-label">Produit A</div>
        </div>
        <div class="progress-step" id="step2">
            <div class="step-number">2</div>
            <div class="step-label">Produit B</div>
        </div>
        <div class="progress-step" id="step3">
            <div class="step-number">3</div>
            <div class="step-label">Produit C</div>
        </div>
    </div>

    <div class="modal-body">
        <table id="productTable">
            <thead>
            <tr>
                <th>Adresse</th>
                <th>Nom du Produit</th>
                <th>Quantité</th>
                <th>Lots</th>
                <th>PPA</th>
                <th>Date d'Expiration</th>
                <th>Quantité Préparée</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Adresse 1</td>
                <td>Produit A</td>
                <td>100</td>
                <td>Lot 1</td>
                <td>PPA 1</td>
                <td>2025-12-31</td>
                <td><input type="text" class="form-control" placeholder="Quantité Préparée"></td>
            </tr>
            <tr style="display: none;">
                <td>Adresse 2</td>
                <td>Produit B</td>
                <td>200</td>
                <td>Lot 2</td>
                <td>PPA 2</td>
                <td>2025-11-30</td>
                <td><input type="text" class="form-control" placeholder="Quantité Préparée"></td>
            </tr>
            <tr style="display: none;">
                <td>Adresse 3</td>
                <td>Produit C</td>
                <td>150</td>
                <td>Lot 3</td>
                <td>PPA 3</td>
                <td>2025-10-31</td>
                <td><input type="text" class="form-control" placeholder="Quantité Préparée"></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="modal-footer">
        <button class="btn btn-outline" onclick="closePopup()">Annuler</button>
        <button class="btn btn-primary" id="nextButton" onclick="confirmQuantity()">
            <i class="fas fa-arrow-right"></i> Confirmer
        </button>
    </div>
</div>

<script>
    let currentRow = 0;
    const totalRows = document.querySelectorAll('#productTable tbody tr').length;

    function updateProgress() {
        // Update progress bar
        const progress = (currentRow / (totalRows - 1)) * 100;
        document.getElementById('progressFill').style.width = `${progress}%`;

        // Update step indicators
        for (let i = 1; i <= totalRows; i++) {
            const step = document.getElementById(`step${i}`);
            if (i - 1 < currentRow) {
                step.classList.remove('step-active');
                step.classList.add('step-completed');
            } else if (i - 1 === currentRow) {
                step.classList.add('step-active');
                step.classList.remove('step-completed');
            } else {
                step.classList.remove('step-active', 'step-completed');
            }
        }
    }

    function openPopup() {
        currentRow = 0;
        updatePopup();
        document.getElementById('modal').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
        updateProgress();
    }

    function closePopup() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    function updatePopup() {
        const rows = document.querySelectorAll('#productTable tbody tr');
        rows.forEach((row, index) => {
            row.style.display = (index === currentRow || row.classList.contains('confirmed')) ? '' : 'none';
        });

        const button = document.getElementById('nextButton');
        if (currentRow === totalRows - 1) {
            button.innerHTML = '<i class="fas fa-check"></i> Confirmer Final';
        } else {
            button.innerHTML = '<i class="fas fa-arrow-right"></i> Confirmer';
        }

        updateProgress();
    }

    function confirmQuantity() {
        const quantityInput = document.querySelector(`#productTable tbody tr:nth-child(${currentRow + 1}) input[type="text"]`);
        const quantity = quantityInput.value.trim();
        const productName = quantityInput.closest('tr').cells[1].innerText;

        if (quantity && !isNaN(quantity)) {
            // Mark the product as confirmed
            quantityInput.closest('tr').classList.add('confirmed');

            // Move to the next product
            currentRow++;
            if (currentRow < totalRows) {
                updatePopup();
            } else {
                // All products confirmed
                setTimeout(() => {
                    alert('Tous les produits ont été confirmés avec succès.');
                    closePopup();
                }, 300);
            }
        } else {
            alert('Veuillez entrer une quantité valide pour ' + productName);
            quantityInput.focus();
        }
    }

    // Close modal when clicking outside
    document.getElementById('overlay').addEventListener('click', closePopup);
</script>

</body>
</html>

