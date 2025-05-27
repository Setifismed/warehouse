<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/css/style.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include("../styles/pages/sidemenu.php"); ?>
    <!-- Navbar -->
    <?php include("../styles/pages/navbar.php"); ?>
    <!-- Main Content -->
    <div class="main-content">
        <?php
        include ('../include/sqlserver_connection.php');
        // Initialize variables
        $searchTerm = $_GET['search'] ?? '';
        $zoneFilter = $_GET['zone'] ?? '';
        $letterFilter = $_GET['letter'] ?? '';
        $rowsPerPage = $_GET['rows'] ?? 25;
        $page = $_GET['page'] ?? 1;

        try {
            // Base query for products
            $sql = "SELECT oid, label1, LogicalQuantity FROM [dbo].[COM_Item] WHERE 1=1";
            $countSql = "SELECT COUNT(*) as total FROM [dbo].[COM_Item] WHERE 1=1";

            // Add search filter
            if (!empty($searchTerm)) {
                $sql .= " AND label1 LIKE :search";
                $countSql .= " AND label1 LIKE :search";
            }
            // Add letter filter
            if (!empty($letterFilter)) {
                $sql .= " AND label1 LIKE :letter";
                $countSql .= " AND label1 LIKE :letter";
            }

            // Get total count
            $stmt = $conn->prepare($countSql);
            if (!empty($searchTerm)) {
                $stmt->bindValue(':search', '%' . $searchTerm . '%');
            }
            if (!empty($letterFilter)) {
                $stmt->bindValue(':letter', $letterFilter . '%');
            }
            $stmt->execute();
            $totalRows = $stmt->fetch()['total'];

            // Add sorting and pagination
            $sql .= " ORDER BY label1 OFFSET :offset ROWS FETCH NEXT :rows ROWS ONLY";
            // Calculate pagination
            $offset = ($page - 1) * $rowsPerPage;
            $start = $offset + 1;
            $end = min($offset + $rowsPerPage, $totalRows);

            // Prepare and execute main query
            $stmt = $conn->prepare($sql);
            if (!empty($searchTerm)) {
                $stmt->bindValue(':search', '%' . $searchTerm . '%');
            }
            if (!empty($letterFilter)) {
                $stmt->bindValue(':letter', $letterFilter . '%');
            }
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':rows', $rowsPerPage, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate stats from database
            // Total products (already have from $totalRows)

            // In stock count
            $inStockSql = "SELECT COUNT(*) as in_stock FROM [dbo].[COM_Item] WHERE LogicalQuantity > 0";
            $stmt = $conn->query($inStockSql);
            $inStock = $stmt->fetch()['in_stock'];

            // Categories count (assuming you have a category field or table)

            // Low stock count (assuming low stock is quantity < 10)
            $lowStockSql = "SELECT COUNT(*) as low_stock FROM [dbo].[COM_Item] WHERE LogicalQuantity > 0 AND LogicalQuantity < 10";
            $stmt = $conn->query($lowStockSql);
            $lowStock = $stmt->fetch()['low_stock'];

            $stats = [
                'total_products' => $totalRows,
                'in_stock' => $inStock,
                'categories' => '10',
                'low_stock' => $lowStock
            ];

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        // Zones for filter dropdown
        $zones = ['Zone A', 'Zone B', 'Zone C', 'Zone D'];
        ?>
        <div class="card inventory-card">
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_products']); ?></div>
                    <div class="label">Total Products</div>
                </div>
                <div class="stat-card success">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['in_stock']); ?></div>
                    <div class="label">In Stock</div>
                </div>
                <div class="stat-card warning">
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['categories']); ?></div>
                    <div class="label">Categories</div>
                </div>
                <div class="stat-card danger">
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['low_stock']); ?></div>
                    <div class="label">Low Stock</div>
                </div>
            </div>
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">Product Inventory</h3>
                    <div class="header-controls">
                        <form method="get" class="search-box" id="searchForm">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search by ID or name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                            <input type="hidden" name="zone" value="<?php echo htmlspecialchars($zoneFilter); ?>">
                            <input type="hidden" name="letter" value="<?php echo htmlspecialchars($letterFilter); ?>">
                            <input type="hidden" name="rows" value="<?php echo $rowsPerPage; ?>">
                        </form>
                        <div class="filter-controls">
                            <form method="get" class="filter-form" id="filterForm">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <input type="hidden" name="rows" value="<?php echo $rowsPerPage; ?>">
                                <select name="zone" class="zone-filter">
                                    <option value="">Filter by Zone</option>
                                    <?php foreach ($zones as $zone): ?>
                                    <option value="<?php echo htmlspecialchars($zone); ?>" <?php echo $zoneFilter === $zone ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($zone); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="letter" class="letter-filter">
                                    <option value="">Filter by First Letter</option>
                                    <?php foreach (range('A', 'Z') as $letter): ?>
                                    <option value="<?php echo $letter; ?>" <?php echo $letterFilter === $letter ? 'selected' : ''; ?>>
                                        <?php echo $letter; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <a href="add_product.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Product
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                        <tr>
                            <th>ID <i class="fas fa-sort"></i></th>
                            <th>Product Name</th>
                            <th>Qty <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['oid']); ?></td>
                            <td><?php echo htmlspecialchars($product['label1']); ?></td>
                            <td><?php echo htmlspecialchars($product['LogicalQuantity']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="table-pagination">
                    <div class="pagination-info">
                        <span>Showing <span class="current-range"><?php echo "$start-$end"; ?></span> of <span class="total-items"><?php echo number_format($totalRows); ?></span> products</span>
                        <div class="rows-per-page">
                            <span>Rows per page:</span>
                            <form method="get" class="rows-form" id="rowsForm">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <input type="hidden" name="zone" value="<?php echo htmlspecialchars($zoneFilter); ?>">
                                <input type="hidden" name="letter" value="<?php echo htmlspecialchars($letterFilter); ?>">
                                <select name="rows" onchange="document.getElementById('rowsForm').submit()">
                                    <option value="5" <?php echo $rowsPerPage == 5 ? 'selected' : ''; ?>>5</option>
                                    <option value="10" <?php echo $rowsPerPage == 10 ? 'selected' : ''; ?>>10</option>
                                    <option value="25" <?php echo $rowsPerPage == 25 ? 'selected' : ''; ?>>25</option>
                                    <option value="50" <?php echo $rowsPerPage == 50 ? 'selected' : ''; ?>>50</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="pagination-controls">
                        <?php if ($page > 1): ?>
                            <a href="?<?php
                                $params = $_GET;
                                $params['page'] = $page - 1;
                                echo http_build_query($params);
                            ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php
                        $totalPages = ceil($totalRows / $rowsPerPage);
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);

                        for ($i = $startPage; $i <= $endPage; $i++):
                            $params = $_GET;
                            $params['page'] = $i;
                        ?>
                            <a href="?<?php echo http_build_query($params); ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?<?php
                                $params = $_GET;
                                $params['page'] = $page + 1;
                                echo http_build_query($params);
                            ?>" class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar collapse
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.createElement('button');
            mobileMenuBtn.className = 'btn btn-sm btn-secondary mobile-menu-btn';
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            document.querySelector('.navbar-left').prepend(mobileMenuBtn);

            function checkScreenSize() {
                if (window.innerWidth <= 992) {
                    mobileMenuBtn.style.display = 'block';
                    sidebar.classList.remove('active');
                    sidebar.classList.remove('collapsed');
                } else {
                    mobileMenuBtn.style.display = 'none';
                    sidebar.classList.add('active');
                }
            }

            // Initial check
            checkScreenSize();

            // Check on resize
            window.addEventListener('resize', checkScreenSize);

            // Toggle sidebar
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            // Auto-submit search when typing stops for 500ms
            const searchInput = document.querySelector('.search-box input');
            let searchTimer;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function() {
                        document.getElementById('searchForm').submit();
                    }, 500);
                });
            }

            // Submit filter forms when changed
            const zoneFilter = document.querySelector('.zone-filter');
            const letterFilter = document.querySelector('.letter-filter');

            if (zoneFilter) {
                zoneFilter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            if (letterFilter) {
                letterFilter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            // Sort table columns
            const sortIcons = document.querySelectorAll('.modern-table th i');
            sortIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const th = this.parentElement;
                    const columnIndex = Array.from(th.parentElement.children).indexOf(th);
                    alert(`Sorting by column ${columnIndex + 1}`);
                    // You would implement actual sorting logic here
                });
            });
        });
    </script>
</body>
</html>