<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern Table with Popup Details</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #eef2ff;
      --success: #22c55e;
      --success-light: #dcfce7;
      --warning: #f59e0b;
      --warning-light: #fef3c7;
      --info: #3b82f6;
      --info-light: #dbeafe;
      --danger: #ef4444;
      --danger-light: #fee2e2;
      --dark: #1f2937;
      --light: #f9fafb;
      --gray: #6b7280;
      --gray-light: #e5e7eb;
      --border-radius: 12px;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
      --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
      color: var(--dark);
      line-height: 1.5;
      padding: 2rem;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--dark);
    }

    .table-container {
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    thead {
      background-color: var(--primary-light);
    }

    th {
      padding: 1rem 1.25rem;
      text-align: left;
      font-weight: 600;
      color: var(--primary);
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
    }

    td {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--gray-light);
      font-weight: 500;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover td {
      background-color: var(--primary-light);
      cursor: pointer;
    }

    tr.selected-row td {
      background-color: var(--primary-light);
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.375rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .badge-completed {
      background-color: var(--success-light);
      color: var(--success);
    }

    .badge-inprogress {
      background-color: var(--warning-light);
      color: var(--warning);
    }

    .badge-pending {
      background-color: var(--info-light);
      color: var(--info);
    }

    .badge-cancelled {
      background-color: var(--danger-light);
      color: var(--danger);
    }

    /* Modal Styles */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
      backdrop-filter: blur(4px);
    }

    .modal.active {
      opacity: 1;
      visibility: visible;
    }

    .modal-content {
      background-color: white;
      border-radius: var(--border-radius);
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: var(--shadow-lg);
      transform: translateY(20px);
      transition: var(--transition);
    }

    .modal.active .modal-content {
      transform: translateY(0);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem;
      border-bottom: 1px solid var(--gray-light);
    }

    .modal-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark);
    }

    .close {
      font-size: 1.5rem;
      color: var(--gray);
      cursor: pointer;
      transition: var(--transition);
      background: none;
      border: none;
    }

    .close:hover {
      color: var(--danger);
    }

    .modal-body {
      padding: 1.5rem;
    }

    .time-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .time-box {
      background-color: var(--light);
      border-radius: var(--border-radius);
      padding: 1rem;
      box-shadow: var(--shadow-sm);
    }

    .time-label {
      font-size: 0.875rem;
      color: var(--gray);
      margin-bottom: 0.5rem;
    }

    .time-value {
      font-weight: 600;
      font-size: 1.125rem;
    }

    .action-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 1.5rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.625rem 1.25rem;
      border-radius: var(--border-radius);
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      border: none;
    }

    .btn-primary {
      background-color: var(--primary);
      color: white;
    }

    .btn-primary:hover {
      background-color: #3a56d4;
    }

    .section-title {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--dark);
    }

    .zones-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 1rem;
    }

    .zone {
      background-color: white;
      border-radius: var(--border-radius);
      padding: 1rem;
      text-align: center;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
      border: 1px solid var(--gray-light);
    }

    .zone:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .zone-name {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--primary);
    }

    .zone-value {
      font-size: 1.25rem;
      font-weight: 700;
    }

    /* Animation for table rows */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    tbody tr {
      animation: fadeIn 0.3s ease-out forwards;
      opacity: 0;
    }

    tbody tr:nth-child(1) { animation-delay: 0.1s; }
    tbody tr:nth-child(2) { animation-delay: 0.2s; }
    tbody tr:nth-child(3) { animation-delay: 0.3s; }
    tbody tr:nth-child(4) { animation-delay: 0.4s; }
    tbody tr:nth-child(5) { animation-delay: 0.5s; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      body {
        padding: 1rem;
      }

      .time-info {
        grid-template-columns: 1fr;
      }

      .zones-container {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
      }
    }

    @media print {
      body * {
        visibility: hidden;
      }
      .modal, .modal * {
        visibility: visible;
      }
      .modal {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        background-color: white;
      }
      .modal-content {
        margin: 0;
        padding: 0;
        box-shadow: none;
        border: none;
      }
      .close, .btn {
        display: none;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1 class="title">Historique des Commandes</h1>
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
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>1</td>
        <td>19/05/2025</td>
        <td>08:15:23</td>
        <td>08:30:00</td>
        <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminé</span></td>
      </tr>
      <tr>
        <td>2</td>
        <td>19/05/2025</td>
        <td>09:45:12</td>
        <td>10:00:00</td>
        <td><span class="status-badge badge-inprogress"><i class="fas fa-spinner fa-pulse"></i> En Cours</span></td>
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
        <td><span class="status-badge badge-cancelled"><i class="fas fa-times-circle"></i> Annulé</span></td>
      </tr>
      <tr>
        <td>5</td>
        <td>18/05/2025</td>
        <td>16:05:33</td>
        <td>16:15:00</td>
        <td><span class="status-badge badge-completed"><i class="fas fa-check-circle"></i> Terminé</span></td>
      </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="detailsModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Détails de la commande #<span id="orderNumber"></span></h2>
      <button class="close">&times;</button>
    </div>

    <div class="modal-body">
      <div class="time-info">
        <div class="time-box">
          <div class="time-label">Heure de création</div>
          <div class="time-value" id="creationTime">-</div>
        </div>
        <div class="time-box">
          <div class="time-label">Heure de début</div>
          <div class="time-value" id="startTime">-</div>
        </div>
        <div class="time-box">
          <div class="time-label">Temps écoulé</div>
          <div class="time-value" id="elapsedTime">-</div>
        </div>
      </div>

      <div class="action-bar">
        <button class="btn btn-primary" onclick="printModal()">
          <i class="fas fa-print"></i> Imprimer
        </button>
      </div>

      <h3 class="section-title">Zones</h3>
      <div class="zones-container" id="zonesContainer">
        <!-- Zones will be added here dynamically -->
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailsModal');
    const closeBtn = modal.querySelector('.close');
    const tableRows = document.querySelectorAll('#mainTable tbody tr');

    // Close modal when clicking X
    closeBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeModal();
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });

    tableRows.forEach(row => {
      row.addEventListener('click', function() {
        // Remove selected class from all rows
        tableRows.forEach(r => r.classList.remove('selected-row'));
        // Add selected class to clicked row
        this.classList.add('selected-row');

        // Get row data
        const cells = this.cells;
        const orderNumber = cells[0].textContent;
        const creationTime = cells[2].textContent;
        const startTime = cells[3].textContent;

        // Calculate elapsed time if start time exists
        let elapsedTime = '-';
        if (startTime !== '-') {
          const [startHours, startMins, startSecs] = startTime.split(':').map(Number);
          const [creationHours, creationMins, creationSecs] = creationTime.split(':').map(Number);

          const startTotal = startHours * 3600 + startMins * 60 + startSecs;
          const creationTotal = creationHours * 3600 + creationMins * 60 + creationSecs;

          const diffSeconds = startTotal - creationTotal;
          const minutes = Math.floor(diffSeconds / 60);
          const seconds = diffSeconds % 60;

          elapsedTime = `${minutes}m ${seconds}s`;
        }

        // Update modal content
        document.getElementById('orderNumber').textContent = orderNumber;
        document.getElementById('creationTime').textContent = creationTime;
        document.getElementById('startTime').textContent = startTime;
        document.getElementById('elapsedTime').textContent = elapsedTime;

        // Generate zones
        const zonesContainer = document.getElementById('zonesContainer');
        zonesContainer.innerHTML = '';

        const zones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
        zones.forEach(zone => {
          const zoneDiv = document.createElement('div');
          zoneDiv.className = 'zone';

          const zoneName = document.createElement('div');
          zoneName.className = 'zone-name';
          zoneName.textContent = `Zone ${zone}`;

          const basketNumber = document.createElement('div');
          basketNumber.className = 'zone-value';
          basketNumber.textContent = Math.floor(Math.random() * 10) + 1;

          zoneDiv.appendChild(zoneName);
          zoneDiv.appendChild(basketNumber);
          zonesContainer.appendChild(zoneDiv);
        });

        // Show modal
        openModal();
      });
    });
  });

  function openModal() {
    const modal = document.getElementById('detailsModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    const modal = document.getElementById('detailsModal');
    modal.classList.remove('active');
    document.querySelector('.selected-row')?.classList.remove('selected-row');
    document.body.style.overflow = 'auto';
  }

  function printModal() {
    window.print();
  }
</script>
</body>
</html>