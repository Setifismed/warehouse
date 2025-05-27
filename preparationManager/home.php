<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Logistique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <link rel="stylesheet" type="text/css" href="styles/css/style.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h3>LogiTrack</h3>
        <p class="text-muted">Gestion Logistique</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="#"><i class="bi bi-truck"></i> Livraisons</a></li>
        <li><a href="#"><i class="bi bi-box-seam"></i> Préparations</a></li>
        <li><a href="#"><i class="bi bi-arrow-left-right"></i> Transferts</a></li>
        <li><a href="#"><i class="bi bi-people"></i> Équipe</a></li>
        <li><a href="#"><i class="bi bi-gear"></i> Paramètres</a></li>
        <li><a href="#"><i class="bi bi-question-circle"></i> Aide</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="document-tabs">
            <div class="document-tab active">Bon de Livraison</div>
            <div class="document-tab">Bon de Préparation</div>
            <div class="document-tab">Bon de Ramassage</div>
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

    <!-- Content Area -->
    <div class="content-area">
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Temps Moyen de Préparation par Zone
                    </div>
                    <div class="chart-container">
                        <canvas id="preparationTimeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Moyenne de Lignes par Zone
                    </div>
                    <div class="chart-container">
                        <canvas id="linesPerZoneChart"></canvas>
                    </div>
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
                                <th>N° Commande</th>
                                <th>Client</th>
                                <th>Zone</th>
                                <th>Lignes</th>
                                <th>Temps Préparation</th>
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
                                <td>45 min</td>
                                <td><span class="badge bg-success">Prête</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CMD-2023-0457</td>
                                <td>Société B</td>
                                <td>Zone 3</td>
                                <td>8</td>
                                <td>32 min</td>
                                <td><span class="badge bg-warning text-dark">En cours</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>CMD-2023-0458</td>
                                <td>Société C</td>
                                <td>Zone 2</td>
                                <td>15</td>
                                <td>58 min</td>
                                <td><span class="badge bg-danger">En retard</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sample data for charts
    const zones = ['Zone 1', 'Zone 2', 'Zone 3', 'Zone 4'];
    const prepTimes = [45, 58, 32, 41];
    const avgLines = [12, 15, 8, 10];

    // Preparation Time Chart
    const prepTimeCtx = document.getElementById('preparationTimeChart').getContext('2d');
    const prepTimeChart = new Chart(prepTimeCtx, {
        type: 'bar',
        data: {
            labels: zones,
            datasets: [{
                label: 'Minutes',
                data: prepTimes,
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Minutes'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' minutes';
                        }
                    }
                }
            }
        }
    });

    // Average Lines Chart
    const avgLinesCtx = document.getElementById('linesPerZoneChart').getContext('2d');
    const avgLinesChart = new Chart(avgLinesCtx, {
        type: 'line',
        data: {
            labels: zones,
            datasets: [{
                label: 'Lignes',
                data: avgLines,
                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                borderColor: '#e74c3c',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de Lignes'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Tab switching functionality
    document.querySelectorAll('.document-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.document-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>