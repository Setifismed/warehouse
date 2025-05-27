<?php
include ('../include/sqlserver_connection.php');

// Initialize variables
$searchTerm = $_GET['search'] ?? '';
$zoneFilter = $_GET['zone'] ?? '';
$letterFilter = $_GET['letter'] ?? '';
$rowsPerPage = $_GET['rows'] ?? 25;
$page = $_GET['page'] ?? 1;

try {
        // Base query
        $sql = "SELECT TOP (50) [Oid]
                        ,[SyncId]
                        ,[Reference]
                        ,[Date]
                        ,[Date2]
                        ,[Deadline]
                        ,[ThirdParty]
                        ,[Type]
                        ,[DocumentState]
                        ,[DocumentCategory1]
                        ,[DocumentCategory2]
                        ,[DocumentCategory3]
                        ,[DocumentCategory4]
                        ,[DocumentCategory5]
                        ,[WarehouseSource]
                        ,[ValidationCount]
                        ,[Amount]
                        ,[IsDiscountPercent]
                        ,[DiscountAmount]
                        ,[DiscountPercent]
                        ,[AmountDiscounted]
                        ,[AmountVAT]
                        ,[AmountATI]
                        ,[Stamp]
                        ,[Note]
                        ,[Label]
                        ,[AmountInLetter]
                        ,[AmountInLetter2]
                        ,[AmountInLetter3]
                        ,[Payment]
                        ,[Commercial]
                        ,[Margin]
                        ,[GroundForInvalidation]
                        ,[Company]
                        ,[ThirdPartyReference]
                        ,[ResponsibilityCenter]
                        ,[Edited]
                        ,[EditedBy]
                        ,[LastEditedDate]
                        ,[Transfer]
                        ,[SourceOrder]
                        ,[MethodOfPayment]
                        ,[CreationDate]
                        ,[Status]
                        ,[Priority]

        FROM [IT-test].[dbo].[COM_Document]
        WHERE
                MONTH([CreationDate]) = MONTH(GETDATE()) AND
                YEAR([CreationDate]) = YEAR(GETDATE()) and DocumentState=1
ORDER BY [CreationDate];";
        $countSql = "SELECT COUNT(*) AS total
FROM [dbo].[COM_Document]
WHERE YEAR(CreationDate) = YEAR(GETDATE())
        AND MONTH(CreationDate) = MONTH(GETDATE());";
        // Get total count
        $stmt = $conn->prepare($countSql);
        $stmt->execute();
        $totalRows = $stmt->fetch()['total'];
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
}

// Zones for filter dropdown
$zones = ['Zone A', 'Zone B', 'Zone C', 'Zone D'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Logistique</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="styles/css/style.css">
</head>

<body>
<!-- Sidebar -->
<?php include("sidemenu.php"); ?>
<!-- Main Content -->
<div class="main-content" id="main-content">
        <!-- Topbar -->
        <div class="topbar">
                <div class="document-tabs">
                        <div class="document-tab active" onclick="showTab('livraison')">Bon de Livraison</div>
                        <div class="document-tab" onclick="showTab('preparation')">Bon de Préparation</div>
                        <div class="document-tab" onclick="showTab('ramassage')">Bon de Ramassage</div>
                </div>

                <div class="search-bar">
                        <div class="input-group">
                                <input type="text" class="form-control" placeholder="Rechercher...">
                                <button class="btn btn-primary" type="button"><i class="bi bi-search"></i></button>
                        </div>
                </div>

                <div class="ms-3">
                        <span class="badge bg-primary">En ligne</span>
                </div>
        </div>

        <!-- Content Area - Livraison -->
        <div id="livraison-content" class="content-area tab-content active">
                <div class="row mb-4">
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">142</div>
                                        <div class="stats-label">Commandes aujourd'hui</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">87</div>
                                        <div class="stats-label">Préparées</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">32</div>
                                        <div class="stats-label">En cours</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">23</div>
                                        <div class="stats-label">En retard</div>
                                </div>
                        </div>
                </div>

                <div class="row mt-4">
                        <div class="col-md-12">
                                <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                                <span>Dernières Commandes</span>
                                                <button class="btn btn-sm btn-primary">Voir tout</button>
                                        </div>
                                        <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                        <thead>
                                                        <tr>
                                                                <th>Bon Commande</th>
                                                                <th>Client</th>
                                                                <th>Zone</th>
                                                                <th>Lignes</th>
                                                                <th>Statut</th>
                                                                <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                                <td>CMD-2023-0456</td>
                                                                <td>Société A</td>
                                                                <td>Zone 1</td>
                                                                <td>12</td>
                                                                <td><span class="badge bg-success">Prête</span></td>
                                                                <td>
                                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                                </td>
                                                        </tr>
                                                        <!-- Additional rows here -->
                                                        </tbody>
                                                </table>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>

    <!-- Content Area - Préparation -->
<div id="preparation-content" class="content-area tab-content">
        <div class="row mb-4">
                <div class="col-md-3">
                        <div class="card stats-card">
                                <div class="stats-value"><?php echo $totalRows; ?></div>
                                <div class="stats-label">Documents ce mois</div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="card stats-card">
                                <div class="stats-value"><?php echo count($results); ?></div>
                                <div class="stats-label">Affichés</div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="card stats-card">
                                <div class="stats-value"><?php echo date('m/Y'); ?></div>
                                <div class="stats-label">Période</div>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="card stats-card">
                                <div class="stats-value"><?php echo round(count($results)/$totalRows*100, 1); ?>%</div>
                                <div class="stats-label">Couverture</div>
                        </div>
                </div>
        </div>

        <div class="row">
                <div class="col-md-12">
                        <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Documents du mois</span>
                                        <div class="d-flex">
                                                <select class="form-select form-select-sm me-2" style="width: 100px;">
                                                        <option value="25" <?php echo $rowsPerPage == 25 ? 'selected' : ''; ?>>25</option>
                                                        <option value="50" <?php echo $rowsPerPage == 50 ? 'selected' : ''; ?>>50</option>
                                                        <option value="100" <?php echo $rowsPerPage == 100 ? 'selected' : ''; ?>>100</option>
                                                </select>
                                                <button class="btn btn-sm btn-primary">Exporter</button>
                                        </div>
                                </div>
                                <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                     <thead>
    <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>Référence</th>
        <th>Date</th>
        <th>Tiers</th>
        <th>Montant</th>
        <th>Catégorie</th>
        <th>Entrepôt</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($results as $index => $row): ?>
    <tr>
        <td><input type="checkbox" class="row-checkbox" data-index="<?php echo $index; ?>"></td>
        <td><?php echo htmlspecialchars($row['Reference'] ?? ''); ?></td>
        <td><?php echo date('d/m/Y', strtotime($row['CreationDate'] ?? '')); ?></td>
        <td><?php echo htmlspecialchars($row['ThirdParty'] ?? ''); ?></td>
        <td><?php echo number_format($row['AmountATI'] ?? 0, 2, ',', ' '); ?> €</td>
        <td><?php echo htmlspecialchars($row['DocumentCategory1'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($row['WarehouseSource'] ?? ''); ?></td>
        <td>
            <?php
            $state = $row['DocumentState'] ?? 0;
            $badgeClass = $state == 1 ? 'bg-success' : ($state == 2 ? 'bg-warning' : 'bg-secondary');
            $stateText = $state == 1 ? 'Validé' : ($state == 2 ? 'En cours' : 'Inconnu');
            ?>
            <span class="badge <?php echo $badgeClass; ?>"><?php echo $stateText; ?></span>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i></button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                                        </table>
                                        <button id="show-selected" class="btn btn-sm btn-warning mt-3">Afficher la sélection</button>

                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                        <div>
                                                Affichage de <?php echo min(count($results), $rowsPerPage); ?> sur <?php echo $totalRows; ?> documents
                                        </div>
                                        <nav>
                                                <ul class="pagination pagination-sm mb-0">
                                                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                                                <a class="page-link" href="?page=<?php echo $page-1; ?>">Précédent</a>
                                                        </li>
                                                        <?php
                                                        $totalPages = ceil($totalRows / $rowsPerPage);
                                                        for ($i = 1; $i <= min($totalPages, 5); $i++):
                                                        ?>
                                                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                        <?php endfor; ?>
                                                        <?php if ($totalPages > 5): ?>
                                                                <li class="page-item disabled">
                                                                        <span class="page-link">...</span>
                                                                </li>
                                                        <?php endif; ?>
                                                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                                                <a class="page-link" href="?page=<?php echo $page+1; ?>">Suivant</a>
                                                        </li>
                                                </ul>
                                        </nav>
                                </div>
                        </div>
                </div>
        </div>
</div>
<div class="modal fade" id="selectedModal" tabindex="-1" aria-labelledby="selectedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Documents sélectionnés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul id="selected-items-list" class="list-group"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirm-selection">OK</button>
            </div>
        </div>
    </div>
</div>


        <!-- Content Area - Ramassage -->
        <div id="ramassage-content" class="content-area tab-content">
                <div class="row mb-4">
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">56</div>
                                        <div class="stats-label">Ramassages aujourd'hui</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">34</div>
                                        <div class="stats-label">En attente</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">12</div>
                                        <div class="stats-label">En cours</div>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="card stats-card">
                                        <div class="stats-value">10</div>
                                        <div class="stats-label">Terminés</div>
                                </div>
                        </div>
                </div>

                <div class="row">
                        <div class="col-md-12">
                                <div class="card">
                                        <div class="card-header">
                                                Liste des Ramassages
                                        </div>
                                        <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                        <thead>
                                                        <tr>
                                                                <th>N° Ramassage</th>
                                                                <th>Date</th>
                                                                <th>Livreur</th>
                                                                <th>Statut</th>
                                                                <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                                <td>RAM-2023-0456</td>
                                                                <td>12/05/2023</td>
                                                                <td>Pierre Martin</td>
                                                                <td><span class="badge bg-warning">En cours</span></td>
                                                                <td>
                                                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                                                </td>
                                                        </tr>
                                                        <!-- Additional rows here -->
                                                        </tbody>
                                                </table>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title" id="productSelectionModalLabel">Select Products & Assign User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <!-- Product Selection Section -->
                                        <div class="mb-4">
                                                <h6 class="fw-bold mb-3">Available Products</h6>
                                                <div class="row g-3">
                                                        <!-- Product Card 1 -->
                                                        <div class="col-md-4">
                                                                <div class="card product-card h-100">
                                                                        <div class="card-img-top bg-light p-3 text-center">
                                                                                <img src="https://via.placeholder.com/150" alt="Product" class="img-fluid" style="height: 100px; object-fit: contain;">
                                                                        </div>
                                                                        <div class="card-body">
                                                                                <h6 class="card-title">Premium Widget</h6>
                                                                                <p class="card-text text-muted small">SKU: PW-1001</p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                        <span class="fw-bold">$49.99</span>
                                                                                        <button class="btn btn-sm btn-outline-primary">Add</button>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                        <!-- Product Card 2 -->
                                                        <div class="col-md-4">
                                                                <div class="card product-card h-100">
                                                                        <div class="card-img-top bg-light p-3 text-center">
                                                                                <img src="https://via.placeholder.com/150" alt="Product" class="img-fluid" style="height: 100px; object-fit: contain;">
                                                                        </div>
                                                                        <div class="card-body">
                                                                                <h6 class="card-title">Deluxe Gadget</h6>
                                                                                <p class="card-text text-muted small">SKU: DG-2002</p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                        <span class="fw-bold">$79.99</span>
                                                                                        <button class="btn btn-sm btn-outline-primary">Add</button>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                        <!-- Product Card 3 -->
                                                        <div class="col-md-4">
                                                                <div class="card product-card h-100">
                                                                        <div class="card-img-top bg-light p-3 text-center">
                                                                                <img src="https://via.placeholder.com/150" alt="Product" class="img-fluid" style="height: 100px; object-fit: contain;">
                                                                        </div>
                                                                        <div class="card-body">
                                                                                <h6 class="card-title">Basic Tool</h6>
                                                                                <p class="card-text text-muted small">SKU: BT-3003</p>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                        <span class="fw-bold">$29.99</span>
                                                                                        <button class="btn btn-sm btn-outline-primary">Add</button>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>

                                        <!-- Selected Products Section -->
                                        <div class="mb-4">
                                                <h6 class="fw-bold mb-3">Selected Products</h6>
                                                <div class="table-responsive">
                                                        <table class="table table-sm">
                                                                <thead>
                                                                <tr>
                                                                        <th>Product</th>
                                                                        <th>Price</th>
                                                                        <th>Qty</th>
                                                                        <th>Total</th>
                                                                        <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                        <td>Premium Widget</td>
                                                                        <td>$49.99</td>
                                                                        <td>
                                                                                <input type="number" class="form-control form-control-sm" value="1" min="1" style="width: 60px;">
                                                                        </td>
                                                                        <td>$49.99</td>
                                                                        <td class="text-end">
                                                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                                        </td>
                                                                </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                        <td colspan="3" class="fw-bold">Subtotal</td>
                                                                        <td colspan="2" class="fw-bold">$49.99</td>
                                                                </tr>
                                                                </tfoot>
                                                        </table>
                                                </div>
                                        </div>

                                        <!-- User Selection Section -->
                                        <div class="mb-3">
                                                <h6 class="fw-bold mb-3">Assign to User</h6>
                                                <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                        <input type="text" class="form-control" placeholder="Search users...">
                                                </div>

                                                <div class="user-list" style="max-height: 200px; overflow-y: auto;">
                                                        <div class="list-group">
                                                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center active">
                                                                        <div>
                                                                                <span class="fw-bold">John Doe</span>
                                                                                <span class="d-block small text-white-50">john@example.com</span>
                                                                        </div>
                                                                        <i class="bi bi-check-circle-fill"></i>
                                                                </a>
                                                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                                <span class="fw-bold">Jane Smith</span>
                                                                                <span class="d-block small text-muted">jane@example.com</span>
                                                                        </div>
                                                                </a>
                                                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                                <span class="fw-bold">Robert Johnson</span>
                                                                                <span class="d-block small text-muted">robert@example.com</span>
                                                                        </div>
                                                                </a>
                                                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                                <span class="fw-bold">Sarah Williams</span>
                                                                                <span class="d-block small text-muted">sarah@example.com</span>
                                                                        </div>
                                                                </a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Confirm Selection
                                        </button>
                                </div>
                        </div>
                </div>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
                // Tab switching functionality
                function showTab(tabName) {
                        // Hide all tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                                content.classList.remove('active');
                        });

                        // Remove active class from all tabs
                        document.querySelectorAll('.document-tab').forEach(tab => {
                                tab.classList.remove('active');
                        });

                        // Show selected tab content
                        document.getElementById(tabName + '-content').classList.add('active');

                        // Add active class to clicked tab
                        event.currentTarget.classList.add('active');
                }
        </script>
        <script>
document.getElementById('show-selected').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const list = document.getElementById('selected-items-list');
    list.innerHTML = '';

    if (checkboxes.length === 0) {
        alert('Aucune ligne sélectionnée.');
        return;
    }

    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        const ref = row.children[1].textContent;
        const tiers = row.children[3].textContent;
        const montant = row.children[4].textContent;
        const item = `<li class="list-group-item">${ref} - ${tiers} - ${montant}</li>`;
        list.insertAdjacentHTML('beforeend', item);
    });

    // Show modal
    const myModal = new bootstrap.Modal(document.getElementById('selectedModal'));
    myModal.show();
});

// OK button
document.getElementById('confirm-selection').addEventListener('click', function () {
    alert('Traitement terminé !');
    const modalEl = document.getElementById('selectedModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
});

// Select all checkbox
document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

</body>
</html>