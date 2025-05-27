<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Preparation Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/css/style.css" type="text/css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --info-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --white-color: #ffffff;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: #333;
            overflow-x: hidden;
            transition: margin-left var(--transition-speed);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all var(--transition-speed);
        }

        .sidebar.collapsed + .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .card {
            background: var(--white-color);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background-color: #f8f9fa;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .stat-card {
            padding: 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--success-color), #38a3a5);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, var(--warning-color), #f3722c);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, var(--danger-color), #b5179e);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            color: var(--gray-color);
        }

        .search-box input {
            padding: 8px 15px 8px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.875rem;
            min-width: 250px;
            transition: border-color 0.2s;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.75rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a56d8;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: var(--gray-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #e3126e;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            position: relative;
            cursor: pointer;
        }

        .modern-table th:hover {
            background-color: #e9ecef;
        }

        .modern-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .modern-table tr:hover td {
            background-color: #f8f9fa;
        }

        .table-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.875rem;
            color: #495057;
        }

        .rows-per-page {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rows-per-page select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            font-size: 0.875rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-link:hover {
            background-color: #e9ecef;
        }

        .page-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            background: #e0e0e0;
            color: #333;
            display: inline-block;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-delayed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-transit {
            background: #cce5ff;
            color: #004085;
        }

        .status-ready {
            background: #fff3cd;
            color: #856404;
        }

        /* Loading state */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .sidebar {
                position: fixed;
                z-index: 1000;
                left: -100%;
                transition: left var(--transition-speed);
            }

            .sidebar.active {
                left: 0;
            }

            .mobile-menu-btn {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-controls {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }

            .filter-controls {
                flex-wrap: wrap;
            }

            .table-pagination {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include("../styles/pages/sidemenu.php"); ?>
    <!-- Navbar -->
    <?php include("../styles/pages/navbar.php"); ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="card inventory-card">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="value" id="current-deliveries">0</div>
                    <div class="label">Current Deliveries</div>
                </div>
                <div class="stat-card success">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="value" id="completed-deliveries">0</div>
                    <div class="label">Completed Today</div>
                </div>
                <div class="stat-card warning">
                    <div class="icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="value" id="items-to-prepare">0</div>
                    <div class="label">Items to Prepare</div>
                </div>
                <div class="stat-card danger">
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="value" id="delayed-deliveries">0</div>
                    <div class="label">Delayed Deliveries</div>
                </div>
            </div>
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">Delivery Preparation</h3>
                    <div class="header-controls">
                        <form method="get" class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search by ID or worker..." id="search-input">
                        </form>
                        <div class="filter-controls">
                            <button type="button" class="btn btn-primary btn-sm" id="new-delivery-btn">
                                <i class="fas fa-plus"></i> New Delivery
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" id="refresh-btn">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                        <tr>
                            <th data-sort="id">Delivery ID <i class="fas fa-sort"></i></th>
                            <th data-sort="items">Total Items <i class="fas fa-sort"></i></th>
                            <th data-sort="worker">Assigned Worker</th>
                            <th data-sort="status">Status <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="delivery-table-body">
                            <!-- Table content will be generated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="table-pagination">
                    <div class="pagination-info">
                        <span>Showing <span class="current-range">1-10</span> of <span class="total-items">50</span> deliveries</span>
                        <div class="rows-per-page">
                            <span>Rows per page:</span>
                            <select id="rows-per-page-select">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                    <div class="pagination-controls" id="pagination-controls">
                        <a href="#" class="page-link disabled" id="prev-page">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" class="page-link active" data-page="1">1</a>
                        <a href="#" class="page-link" data-page="2">2</a>
                        <a href="#" class="page-link" data-page="3">3</a>
                        <a href="#" class="page-link" data-page="4">4</a>
                        <a href="#" class="page-link" data-page="5">5</a>
                        <a href="#" class="page-link" id="next-page">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar functionality
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const mobileMenuBtn = document.createElement('button');
            const navbarLeft = document.querySelector('.navbar-left');

            // Create mobile menu button if it doesn't exist
            if (!document.querySelector('.mobile-menu-btn')) {
                mobileMenuBtn.className = 'btn btn-sm btn-secondary mobile-menu-btn';
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                if (navbarLeft) {
                    navbarLeft.prepend(mobileMenuBtn);
                }
            }

            // Toggle sidebar collapse (desktop)
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }

            // Toggle sidebar (mobile)
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Check screen size and set initial state
            function checkScreenSize() {
                if (window.innerWidth <= 992) {
                    // Mobile view
                    mobileMenuBtn.style.display = 'block';
                    sidebar.classList.remove('collapsed');

                    // Close sidebar by default on mobile
                    if (!sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                    }
                } else {
                    // Desktop view
                    mobileMenuBtn.style.display = 'none';
                    sidebar.classList.add('active');

                    // Restore collapsed state from localStorage
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                }
            }

            // Initial check
            checkScreenSize();

            // Check on resize with debounce
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(checkScreenSize, 250);
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992 &&
                    sidebar.classList.contains('active') &&
                    !event.target.closest('.sidebar') &&
                    !event.target.closest('.mobile-menu-btn')) {
                    sidebar.classList.remove('active');
                }
            });

            // Delivery Dashboard Functionality
            const statuses = ['Preparing', 'Ready for Dispatch', 'In Transit', 'Delivered', 'Delayed'];
            const workers = ['John Smith', 'Maria Garcia', 'Robert Johnson', 'Emily Davis', 'Michael Brown', 'Sarah Wilson'];
            const tableBody = document.getElementById('delivery-table-body');
            const searchInput = document.getElementById('search-input');
            const rowsPerPageSelect = document.getElementById('rows-per-page-select');
            const paginationControls = document.getElementById('pagination-controls');
            const prevPageBtn = document.getElementById('prev-page');
            const nextPageBtn = document.getElementById('next-page');
            const currentRangeSpan = document.querySelector('.current-range');
            const totalItemsSpan = document.querySelector('.total-items');
            const newDeliveryBtn = document.getElementById('new-delivery-btn');
            const refreshBtn = document.getElementById('refresh-btn');

            let currentPage = 1;
            let rowsPerPage = 10;
            let totalDeliveries = 50;
            let allDeliveries = [];
            let sortColumn = 'id';
            let sortDirection = 'asc';

            // Initialize the dashboard
            function initDashboard() {
                generateRandomStats();
                generateDeliveries();
                renderTable();
                setupEventListeners();
            }

            // Generate random stats for the cards
            function generateRandomStats() {
                document.getElementById('current-deliveries').textContent = Math.floor(Math.random() * 20) + 5;
                document.getElementById('completed-deliveries').textContent = Math.floor(Math.random() * 30) + 10;
                document.getElementById('items-to-prepare').textContent = Math.floor(Math.random() * 100) + 20;
                document.getElementById('delayed-deliveries').textContent = Math.floor(Math.random() * 10) + 1;
            }

            // Generate random delivery data
            function generateDeliveries() {
                allDeliveries = [];
                for (let i = 0; i < totalDeliveries; i++) {
                    const status = statuses[Math.floor(Math.random() * statuses.length)];
                    allDeliveries.push({
                        id: 'DL-' + (1000 + i),
                        items: Math.floor(Math.random() * 50) + 1,
                        worker: workers[Math.floor(Math.random() * workers.length)],
                        status: status,
                        statusClass: getStatusClass(status)
                    });
                }
            }

            // Get status class based on status
            function getStatusClass(status) {
                switch (status) {
                    case 'Delivered': return 'status-delivered';
                    case 'Delayed': return 'status-delayed';
                    case 'In Transit': return 'status-transit';
                    case 'Ready for Dispatch': return 'status-ready';
                    default: return '';
                }
            }

            // Render the table with current data
            function renderTable() {
                // Show loading state
                tableBody.innerHTML = '<tr><td colspan="5" class="loading" style="height: 200px;"></td></tr>';

                // Simulate API delay
                setTimeout(() => {
                    // Clear existing rows
                    tableBody.innerHTML = '';

                    // Sort deliveries
                    sortDeliveries();

                    // Get deliveries for current page
                    const startIdx = (currentPage - 1) * rowsPerPage;
                    const endIdx = startIdx + rowsPerPage;
                    const displayedDeliveries = allDeliveries.slice(startIdx, endIdx);

                    // Create table rows
                    displayedDeliveries.forEach(delivery => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${delivery.id}</td>
                            <td>${delivery.items}</td>
                            <td>${delivery.worker}</td>
                            <td><span class="status-badge ${delivery.statusClass}">${delivery.status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-secondary view-btn" data-id="${delivery.id}"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="${delivery.id}"><i class="fas fa-edit"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Update pagination info
                    updatePaginationInfo();
                }, 500);
            }

            // Sort deliveries based on current sort column and direction
            function sortDeliveries() {
                allDeliveries.sort((a, b) => {
                    let aValue, bValue;

                    switch (sortColumn) {
                        case 'id':
                            aValue = a.id;
                            bValue = b.id;
                            return sortDirection === 'asc'
                                ? aValue.localeCompare(bValue)
                                : bValue.localeCompare(aValue);

                        case 'items':
                            aValue = a.items;
                            bValue = b.items;
                            return sortDirection === 'asc'
                                ? aValue - bValue
                                : bValue - aValue;

                        case 'worker':
                            aValue = a.worker;
                            bValue = b.worker;
                            return sortDirection === 'asc'
                                ? aValue.localeCompare(bValue)
                                : bValue.localeCompare(aValue);

                        case 'status':
                            const statusOrder = ['Preparing', 'Ready for Dispatch', 'In Transit', 'Delivered', 'Delayed'];
                            aValue = statusOrder.indexOf(a.status);
                            bValue = statusOrder.indexOf(b.status);
                            return sortDirection === 'asc'
                                ? aValue - bValue
                                : bValue - aValue;

                        default:
                            return 0;
                    }
                });
            }

            // Update pagination information
            function updatePaginationInfo() {
                const startItem = (currentPage - 1) * rowsPerPage + 1;
                const endItem = Math.min(currentPage * rowsPerPage, totalDeliveries);
                currentRangeSpan.textContent = `${startItem}-${endItem}`;
                totalItemsSpan.textContent = totalDeliveries;

                // Update pagination controls
                updatePaginationControls();
            }

            // Update pagination controls
            function updatePaginationControls() {
                const totalPages = Math.ceil(totalDeliveries / rowsPerPage);

                // Clear existing page links (except prev/next)
                const pageLinks = paginationControls.querySelectorAll('.page-link:not(#prev-page):not(#next-page)');
                pageLinks.forEach(link => link.remove());

                // Add page links
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                // Add first page if needed
                if (startPage > 1) {
                    const firstPageLink = document.createElement('a');
                    firstPageLink.href = '#';
                    firstPageLink.className = 'page-link';
                    firstPageLink.textContent = '1';
                    firstPageLink.dataset.page = '1';
                    if (currentPage === 1) firstPageLink.classList.add('active');
                    paginationControls.insertBefore(firstPageLink, nextPageBtn);

                    if (startPage > 2) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        ellipsis.style.padding = '0 8px';
                        paginationControls.insertBefore(ellipsis, nextPageBtn);
                    }
                }

                // Add visible pages
                for (let i = startPage; i <= endPage; i++) {
                    const pageLink = document.createElement('a');
                    pageLink.href = '#';
                    pageLink.className = 'page-link';
                    if (i === currentPage) pageLink.classList.add('active');
                    pageLink.textContent = i;
                    pageLink.dataset.page = i;
                    paginationControls.insertBefore(pageLink, nextPageBtn);
                }

                // Add last page if needed
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        ellipsis.style.padding = '0 8px';
                        paginationControls.insertBefore(ellipsis, nextPageBtn);
                    }

                    const lastPageLink = document.createElement('a');
                    lastPageLink.href = '#';
                    lastPageLink.className = 'page-link';
                    lastPageLink.textContent = totalPages;
                    lastPageLink.dataset.page = totalPages;
                    if (currentPage === totalPages) lastPageLink.classList.add('active');
                    paginationControls.insertBefore(lastPageLink, nextPageBtn);
                }

                // Update prev/next buttons
                prevPageBtn.classList.toggle('disabled', currentPage === 1);
                nextPageBtn.classList.toggle('disabled', currentPage === totalPages);
            }

            // Setup event listeners
            function setupEventListeners() {
                // Search functionality
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    if (searchTerm.length > 2 || searchTerm.length === 0) {
                        currentPage = 1;
                        renderTable();
                    }
                });

                // Rows per page change
                rowsPerPageSelect.addEventListener('change', function() {
                    rowsPerPage = parseInt(this.value);
                    currentPage = 1;
                    renderTable();
                });

                // Pagination controls
                paginationControls.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (e.target.classList.contains('page-link') && !e.target.classList.contains('disabled')) {
                        if (e.target.id === 'prev-page') {
                            if (currentPage > 1) {
                                currentPage--;
                                renderTable();
                            }
                        } else if (e.target.id === 'next-page') {
                            const totalPages = Math.ceil(totalDeliveries / rowsPerPage);
                            if (currentPage < totalPages) {
                                currentPage++;
                                renderTable();
                            }
                        } else if (e.target.dataset.page) {
                            currentPage = parseInt(e.target.dataset.page);
                            renderTable();
                        }
                    }
                });

                // Table sorting
                document.querySelectorAll('.modern-table th[data-sort]').forEach(th => {
                    th.addEventListener('click', function() {
                        const column = this.dataset.sort;

                        // Update sort direction if clicking the same column
                        if (sortColumn === column) {
                            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            sortColumn = column;
                            sortDirection = 'asc';
                        }

                        // Update sort indicators
                        document.querySelectorAll('.modern-table th i').forEach(icon => {
                            icon.className = 'fas fa-sort';
                        });

                        const sortIcon = this.querySelector('i');
                        if (sortIcon) {
                            sortIcon.className = sortDirection === 'asc'
                                ? 'fas fa-sort-up'
                                : 'fas fa-sort-down';
                        }

                        renderTable();
                    });
                });

                // New delivery button
                if (newDeliveryBtn) {
                    newDeliveryBtn.addEventListener('click', function() {
                        alert('New delivery functionality would be implemented here');
                    });
                }

                // Refresh button
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        generateRandomStats();
                        generateDeliveries();
                        renderTable();
                    });
                }

                // View/edit buttons (delegated)
                tableBody.addEventListener('click', function(e) {
                    if (e.target.closest('.view-btn')) {
                        const deliveryId = e.target.closest('.view-btn').dataset.id;
                        alert(`View delivery ${deliveryId}`);
                    } else if (e.target.closest('.edit-btn')) {
                        const deliveryId = e.target.closest('.edit-btn').dataset.id;
                        alert(`Edit delivery ${deliveryId}`);
                    }
                });
            }

            // Initialize the dashboard
            initDashboard();
        });
    </script>
</body>
</html>