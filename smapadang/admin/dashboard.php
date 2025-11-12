<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMA Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --light: #f1f5f9;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Card */
        .header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title h1 i {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stat-card-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .stat-info h3 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .stat-info p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }

        /* Main Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .search-box {
            position: relative;
            width: 300px;
            max-width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Modern Table */
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 18px 15px;
            font-weight: 600;
            text-align: left;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .modern-table thead th:first-child {
            border-radius: 15px 0 0 0;
        }

        .modern-table thead th:last-child {
            border-radius: 0 15px 0 0;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .modern-table tbody td {
            padding: 18px 15px;
            color: #475569;
            font-size: 14px;
            vertical-align: middle;
        }

        .school-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .school-img:hover {
            transform: scale(1.8);
            cursor: pointer;
            z-index: 10;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-a { background: #dcfce7; color: #166534; }
        .badge-b { background: #dbeafe; color: #1e40af; }
        .badge-c { background: #fef3c7; color: #92400e; }

        /* Buttons */
        .btn-modern {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #d97706);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-title h1 {
                font-size: 24px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                width: 100%;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .modern-table {
                min-width: 900px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-card">
            <div class="header-title">
                <h1>
                    <i class="fas fa-chart-line"></i>
                    Dashboard Admin
                </h1>
                <div class="header-actions">
                    <a href="tambah.php" class="btn-modern btn-success">
                        <i class="fas fa-plus-circle"></i>
                        Tambah Data SMA
                    </a>
                    <a href="logout.php" class="btn-modern btn-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <?php
            $total_sma = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sma"));
            $akreditasi_a = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sma WHERE akreditasi='A'"));
            $akreditasi_b = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sma WHERE akreditasi='B'"));
            $akreditasi_c = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sma WHERE akreditasi='C'"));
            ?>
            
            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-icon primary">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_sma; ?></h3>
                        <p>Total SMA</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-icon success">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $akreditasi_a; ?></h3>
                        <p>Akreditasi A</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-icon info">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $akreditasi_b; ?></h3>
                        <p>Akreditasi B</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-icon warning">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $akreditasi_c; ?></h3>
                        <p>Akreditasi C</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="table-card">
            <div class="table-header">
                <h2><i class="fas fa-list"></i> Data SMA Kota Padang</h2>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari SMA...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama SMA</th>
                            <th>Kecamatan</th>
                            <th>Akreditasi</th>
                            <th>Koordinat</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT * FROM sma ORDER BY nama_sma ASC");
                        while ($row = mysqli_fetch_assoc($data)) {
                            $foto = !empty($row['foto']) ? "../img/{$row['foto']}" : "../img/default.jpg";
                            
                            // Badge class based on akreditasi
                            $badge_class = 'badge-c';
                            if ($row['akreditasi'] == 'A') $badge_class = 'badge-a';
                            else if ($row['akreditasi'] == 'B') $badge_class = 'badge-b';
                            
                            echo "
                            <tr>
                                <td><strong>{$no}</strong></td>
                                <td><strong>{$row['nama_sma']}</strong></td>
                                <td><i class='fas fa-map-marker-alt' style='color: #64748b;'></i> {$row['kecamatan']}</td>
                                <td><span class='badge {$badge_class}'>{$row['akreditasi']}</span></td>
                                <td>
                                    <small style='color: #64748b;'>
                                        <i class='fas fa-location-dot'></i> {$row['latitude']}, {$row['longitude']}
                                    </small>
                                </td>
                                <td><img src='$foto' class='school-img' alt='{$row['nama_sma']}'></td>
                                <td>
                                    <div class='action-buttons'>
                                        <a href='edit.php?id={$row['id']}' class='btn-modern btn-warning btn-sm'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='hapus.php?id={$row['id']}' class='btn-modern btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data {$row['nama_sma']}?\")'>
                                            <i class='fas fa-trash'></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#dataTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        // Add animation on load
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>