<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['zone'])) {
    header("Location: login.php");
    exit();
}

$zone = $_SESSION['zone'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartPrep - Gestion des bons</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="inventaire.css">
</head>
<body>

<?php include('sidemenu.php');?>
<div class="container">
  <header>
    <div class="logo">
      <i class="fas fa-clipboard-list"></i>
      <h1>SmartPrep</h1>
    </div>
    <div class="user-profile">
      <div class="user-avatar">JD</div>
      <div>
        <div style="font-weight: 600;"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
        <div style="font-size: 14px; color: var(--gray);"><?php echo htmlspecialchars($_SESSION['zone']); ?></div>
      </div>
    </div>
  </header>

  <div class="barcode-section">
    <div class="barcode-container">
      <i class="fas fa-barcode barcode-icon"></i>
      <input type="text" class="barcode-input" placeholder="Scanner code-barres..." autofocus>
    </div>

    <div class="action-buttons">
      <button class="btn btn-accent">
        <i class="fas fa-exclamation-circle"></i>
        Réclamation
      </button>
      <button class="btn btn-primary">
        <i class="fas fa-clipboard-check"></i>
        Inventaire
      </button>
    </div>
  </div>

  <!-- Added search bar section -->
  <div class="search-container">
    <div class="search-box">
      <i class="fas fa-search search-icon"></i>
      <input type="text" class="search-input" placeholder="Rechercher un produit...">
    </div>
    <div class="table-actions">
      <button class="filter-btn">
        <i class="fas fa-filter"></i>
        <span>Filtrer</span>
      </button>
      <button class="filter-btn">
        <i class="fas fa-download"></i>
        <span>Exporter</span>
      </button>
    </div>
  </div>

<table class="data-table">
    <thead>
    <tr>
      <th>Produit</th>
      <th>Lot</th>
      <th>Date</th>
      <th>Quantité</th>
      <th>Zone</th>
      <th>Expiration</th>
    </tr>
    </thead>
    <tbody>
  <?php
include('../include/sqlserver_connection.php');
include('../include/functions.php'); // Make sure this points to where your function is defined

try {
    // Define or get the $zone dynamically (e.g., from GET or POST)
    $zoneLike = $zone . '%';
    echo('ZONE'.$zoneLike);

    $sql = "SELECT
        i.Label1 AS itemName,
        il.Label1 AS Zone,
        b.BatchNum,
        b.Date2 as pDate,
        b.ExpirationDate,
        b.LogicalQuantity
    FROM [IT-test].[dbo].[COM_Item] i
    INNER JOIN COM_ItemLocation il ON i.Location = il.Oid
    INNER JOIN COM_Batch b ON i.Oid = b.Item
    WHERE b.LogicalQuantity > 1 AND il.Label1 LIKE 'A%'
    ORDER BY i.Label1 ASC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $iconClass = "fas fa-pills";
        $bgColor = "#e2e8ff";
        $iconColor = "var(--primary)";

        $formattedPDate = formatDate($row['pDate']);
        $formattedExpDate = formatDate($row['ExpirationDate']);

        $badgeClass = (strtotime($row['ExpirationDate']) < time()) ? 'badge-danger' : 'badge-success';
        $badgeDisplay = ($formattedExpDate !== '—') ? date('m/Y', strtotime($formattedExpDate)) : '—';

        echo "<tr>
          <td>
            <div style='display: flex; align-items: center; gap: 12px;'>
              <div style='width: 40px; height: 40px; background: {$bgColor}; border-radius: 8px; display: flex; align-items: center; justify-content: center;'>
                <i class='{$iconClass}' style='color: {$iconColor};'></i>
              </div>
              <div>
                <div style='font-weight: 600;'>{$row['itemName']}</div>
                <div style='font-size: 14px; color: var(--gray);'>REF: {$row['BatchNum']}</div>
              </div>
            </div>
          </td>
          <td>{$row['BatchNum']}</td>
          <td>{$formattedPDate}</td>
          <td><strong>{$row['LogicalQuantity']}</strong> unités</td>
          <td>{$row['Zone']}</td>
          <td><span class='badge {$badgeClass}'>{$badgeDisplay}</span></td>
        </tr>";
    }

} catch (PDOException $e) {
    echo "<tr><td colspan='6'>Database error: " . $e->getMessage() . "</td></tr>";
}
?>


    </tbody>
</table>
</div>
</body>
</html>
