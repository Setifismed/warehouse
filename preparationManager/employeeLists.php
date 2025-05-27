<?php
include('../include/pgsql_connection.php');
$query = "SELECT
                            COUNT(*) AS total,
                            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active,
                            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS inactive
                    FROM users";
$result = pg_query($pg_conn, $query);
$row = pg_fetch_assoc($result);
$totalEmployees = $row['total'];
$activeEmployees = $row['active'];
$inactiveEmployees = $row['inactive'];

 ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LogiTrack | Gestion d'Équipe</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <link rel="stylesheet" href="styles/css/style.css">
        <style>
                .stats-card {
                        border-radius: 10px;
                        border: none;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        transition: transform 0.3s ease;
                }
                .stats-card:hover {
                        transform: translateY(-5px);
                }
                .stats-card .card-body {
                        padding: 1.5rem;
                }
                .stats-card .icon {
                        font-size: 2rem;
                        margin-bottom: 1rem;
                }
                .stats-card .count {
                        font-size: 2rem;
                        font-weight: bold;
                }
                .stats-card .label {
                        font-size: 0.9rem;
                        color: #6c757d;
                }
                .card-active {
                        border-left: 4px solid #28a745;
                }
                .card-inactive {
                        border-left: 4px solid #ffc107;
                }
                .card-total {
                        border-left: 4px solid #007bff;
                }
        </style>
</head>
<body>
<!-- Sidebar -->
<?php include("sidemenu.php"); ?>

<!-- Main Content -->
<div class="main-content">
        <!-- Topbar -->
        <div class="topbar animate__animated animate__fadeIn">
                <h4><i class="bi bi-people-fill me-2"></i> Gestion d'Équipe</h4>
                <div class="d-flex align-items-center">
                        <button class="btn btn-light me-2 position-relative">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </button>
                        <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-1"></i> Admin
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                                </ul>
                        </div>
                </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row mb-4 animate__animated animate__fadeIn">
                <div class="col-md-4">
                        <div class="card stats-card card-total h-100">
                                <div class="card-body text-center">
                                        <div class="icon text-primary">
                                                <i class="bi bi-people-fill"></i>
                                        </div>
                                        <div class="count text-primary" id="totalEmployees"><?= $totalEmployees ?></div>
                                        <div class="label">Total Employés</div>
                                </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="card stats-card card-active h-100">
                                <div class="card-body text-center">
                                        <div class="icon text-success">
                                                <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <div class="count text-success" id="activeEmployees"><?= $activeEmployees ?></div>
                                        <div class="label">Employés Actifs</div>
                                </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="card stats-card card-inactive h-100">
                                <div class="card-body text-center">
                                        <div class="icon text-warning">
                                                <i class="bi bi-person-x-fill"></i>
                                        </div>
                                        <div class="count text-warning" id="inactiveEmployees"><?= $inactiveEmployees ?></div>
                                        <div class="label">Employés Inactifs</div>
                                </div>
                        </div>
                </div>
        </div>

        <!-- Team Members Card -->
        <div class="card animate__animated animate__fadeInUp">
                <div class="card-header">
                        <div>
                                <i class="bi bi-people me-2"></i>
                                <span>Membres de l'Équipe</span>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                <i class="bi bi-plus-lg me-1"></i> Nouveau Membre
                        </button>
                </div>
                <div class="table-responsive">
                        <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                        <th>ID</th>
                                        <th>Membre</th>
                                        <th>Type de Compte</th>
                                        <th>Zone</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php


$query = "SELECT * FROM users";
$result = pg_query($pg_conn, $query);

if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
                $zone = !empty($row['zone']) ? htmlspecialchars($row['zone']) : 'N/A';

                echo '<tr>';
                echo '<td class="fw-semibold text-primary">#' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>
                                <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle me-2">
                                                <i class="bi bi-person text-muted"></i>
                                        </div>
                                        <span>' . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . '</span>
                                </div>
                            </td>';
                echo '<td><span class="badge bg-light text-dark">' . $row['type'] . '</span></td>';
                echo '<td>' . $zone . '</td>';
                echo '<td><span>' . ucfirst($row['status']) . '</span></td>';
                echo '<td>
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editMemberModal" data-id="' . $row['id'] . '">
                                        <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-id="' . $row['id'] . '">
                                        <i class="bi bi-trash"></i>
                                </button>
                            </td>';
                echo '</tr>';
        }
} else {
        echo '<tr><td colspan="6" class="text-center text-muted">Aucun membre trouvé.</td></tr>';
}
?>

                                </tbody>
                        </table>
                </div>
                <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">Affichage de 1 à 3 sur 3 entrées</div>
                                <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0">
                                                <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1"><i class="bi bi-chevron-left"></i></a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item disabled">
                                                        <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                                                </li>
                                        </ul>
                                </nav>
                        </div>
                </div>
        </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="addMemberModalLabel"><i class="bi bi-person-plus me-2"></i>Ajouter un Membre</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <form id="addMemberForm" action="addEmployee.php" method="post">
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <div class="mb-3">
                                                                <label for="newMemberFirstName" class="form-label">Prénom</label>
                                                                <input type="text" class="form-control" id="newMemberFirstName" name="firstName" required>
                                                        </div>
                                                        <div class="mb-3">
                                                                <label for="newMemberLastName" class="form-label">Nom</label>
                                                                <input type="text" class="form-control" id="newMemberLastName" name="lastName" required>
                                                        </div>
                                                </div>
                                                <div class="col-md-6">
                                                        <div class="mb-3">
                                                                <label for="newMemberAccountType" class="form-label">Type de Compte</label>
                                                                <select class="form-select" id="newMemberAccountType" name="accountType" required>
                                                                        <option value="" selected disabled>Sélectionner un type</option>
                                                                        <option value="preparateur">Préparateur</option>
                                                                        <option value="rammaseur">Rammaseur</option>
                                                                        <option value="psycho">Psycho</option>
                                                                </select>
                                                        </div>
                                                        <div class="mb-3" id="zoneContainer" style="display: none;">
                                                                <label for="newMemberZone" class="form-label">Zone Assignée</label>
                                                                <select class="form-select" id="newMemberZone" name="zone">
                                                                        <option value="" selected disabled>Sélectionner une zone</option>
                                                                        <option value="zone1">Zone 1</option>
                                                                        <option value="zone2">Zone 2</option>
                                                                        <option value="zone3">Zone 3</option>
                                                                        <option value="all">Toutes zones</option>
                                                                </select>
                                                        </div>
                                                        <div class="mb-3 position-relative">
                                                                <label for="newMemberPassword" class="form-label">Mot de Passe</label>
                                                                <input type="password" class="form-control" id="newMemberPassword" name="password" required>
                                                                <i class="bi bi-eye password-toggle" data-target="newMemberPassword"></i>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary" name="addEmployee">
                                                        <i class="bi bi-save me-1"></i> Enregistrer
                                                </button>
                                        </div>
                                </form>
                        </div>
                </div>
        </div>
</div>

<!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMemberModalLabel"><i class="bi bi-person-gear me-2"></i>Modifier Membre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMemberForm" action="editEmployee.php" method="post">
                    <input type="hidden" id="memberId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="memberFirstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="memberFirstName" name="firstName" required>
                            </div>
                            <div class="mb-3">
                                <label for="memberLastName" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="memberLastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="memberAccountType" class="form-label">Type de Compte</label>
                                <select class="form-select" id="memberAccountType" name="accountType" required>
                                    <option value="preparateur">Préparateur</option>
                                    <option value="superviseur">Superviseur</option>
                                    <option value="psycho">Psycho</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="memberStatus" class="form-label">Statut</label>
                                <select class="form-select" id="memberStatus" name="status" required>
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="memberPassword" class="form-label">Nouveau Mot de Passe</label>
                        <input type="password" class="form-control" id="memberPassword" name="password" placeholder="Laisser vide pour ne pas modifier">
                        <i class="bi bi-eye password-toggle" data-target="memberPassword"></i>
                    </div>
                    <div class="mb-3" id="editZoneContainer" style="display: none;">
                        <label for="memberZone" class="form-label">Zone Assignée</label>
                        <select class="form-select" id="memberZone" name="zone">
                            <option value="zone1">Zone 1</option>
                            <option value="zone2">Zone 2</option>
                            <option value="zone3">Zone 3</option>
                            <option value="all">Toutes zones</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="editEmployee">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
                // Show/hide zone field based on account type selection in add modal
                const accountTypeSelect = document.getElementById('newMemberAccountType');
                const zoneContainer = document.getElementById('zoneContainer');

                if (accountTypeSelect) {
                        accountTypeSelect.addEventListener('change', function() {
                                zoneContainer.style.display = this.value === 'preparateur' ? 'block' : 'none';
                        });
                }

                // Show/hide zone field based on account type selection in edit modal
                const editAccountTypeSelect = document.getElementById('memberAccountType');
                const editZoneContainer = document.getElementById('editZoneContainer');

                if (editAccountTypeSelect) {
                        editAccountTypeSelect.addEventListener('change', function() {
                                editZoneContainer.style.display = this.value === 'preparateur' ? 'block' : 'none';
                        });
                }

                // Password toggle functionality
                document.querySelectorAll('.password-toggle').forEach(toggle => {
                        toggle.addEventListener('click', function() {
                                const targetId = this.getAttribute('data-target');
                                const passwordInput = document.getElementById(targetId);
                                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                passwordInput.setAttribute('type', type);
                                this.classList.toggle('bi-eye');
                                this.classList.toggle('bi-eye-slash');
                        });
                });
                 document.querySelectorAll('.btn-outline-primary').forEach(button => {
        button.addEventListener('click', function() {
            const memberId = this.getAttribute('data-id');
            // Fetch member data using AJAX
            fetch(`getMember.php?id=${memberId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('memberId').value = data.id;
                    document.getElementById('memberFirstName').value = data.firstname;
                    document.getElementById('memberLastName').value = data.lastname;
                    document.getElementById('memberAccountType').value = data.type;
                    // Show zone depending on account type
                    const editZoneContainer = document.getElementById('editZoneContainer');
                    editZoneContainer.style.display = data.type === 'preparateur' ? 'block' : 'none';
                    document.getElementById('memberZone').value = data.zone || '';
                });
        });
    });
        });
</script>
</body>
</html>