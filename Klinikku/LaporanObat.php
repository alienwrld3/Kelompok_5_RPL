<?php
session_start();
require 'function.php';

//validasi jika belum pernah login 
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
} else {
    $id_user = $_SESSION['id_user'];
    // Query untuk mengambil nama pengguna dan role berdasarkan id_user
    $query_user = "SELECT * FROM pengguna WHERE id_pengguna = '$id_user'";
    $result_user = mysqli_query($conn, $query_user);

    // Jika query berhasil, ambil data pengguna
    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $user = mysqli_fetch_assoc($result_user);
        $nama = $user['nama'];
        $role = $user['role'];
    }
}

$sql = "SELECT id_laporan, nama_laporan, jenis_laporan, tgl_laporan FROM laporan ORDER BY tgl_laporan DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <!-- Sidebar kiri -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-header d-flex align-items-center">
                            <div class="me-auto ms-3 mb-3 ">
                                <img src="assets/img/Screenshot_2025-04-24_174922-removebg-preview.png" alt="Icon"
                                    style="width:50px;height:auto;" />
                                <span style="font-size: 24px; font-weight: bold; color: white;">Klinikku</span>
                            </div>
                        </div>

                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">

                        <div class="user-info px-3 py-2 d-flex align-items-center">
                            <img src="assets/img/1702635599933.jpg" class="rounded-circle object-fit-cover"
                                style="width: 48px; height: 48px;" alt="User" />
                            <div class="ms-2">
                                <div><?=$nama?></div>
                                <small class="text-success"><?=$role?></small>
                            </div>
                            <div class="dropdown ms-auto">
                                <!-- Ikon titik tiga yang membuka dropdown -->
                                <i class="bi bi-three-dots-vertical" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false"></i>

                                <!-- Menu Dropdown -->
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#">Detail Akun</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="confirmLogout()">Logout</a></li>

                                    <script>
                                        // Fungsi konfirmasi logout menggunakan JavaScript confirm()
                                        function confirmLogout() {
                                            var confirmation = confirm("Apakah Anda yakin ingin logout?");

                                            if (confirmation) {
                                                window.location.href = "logout.php";
                                            }
                                        }
                                    </script>

                                </ul>
                            </div>

                        </div>

                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">

                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon">
                                <i class="bi bi-grid-1x2"></i>
                            </div>
                            Dashboard
                        </a>
                        <!-- Sidebar Obat dan Transaksi -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseObat"
                            aria-expanded="false" aria-controls="collapseObat">
                            <div class="sb-nav-link-icon">
                                <i class="bi bi-prescription2"></i>
                            </div>
                            Obat dan Transaksi
                            <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseObat" aria-labelledby="headingObat"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="InventarisObat.php">Inventaris Obat</a>
                                <a class="nav-link" href="RiwayatTransaksi.php">Riwayat Transaksi</a>
                            </nav>
                        </div>

                        <!-- Sidebar Laporan -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLaporan" aria-expanded="false" aria-controls="collapseLaporan">
                            <div class="sb-nav-link-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            Laporan
                            <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLaporan" aria-labelledby="headingLaporan">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="LaporanObat.php">Laporan Obat</a>
                                <a class="nav-link" href="LaporanTransaksi.php">Laporan Transaksi</a>
                            </nav>
                        </div>
                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">
                    </div>
                </div>
            </nav>
        </div>

        <!-- Konten kanan -->
        <div id="layoutSidenav_content">
            <!-- Navbar berada di dalam main, atas konten dashboard -->
            <nav class="navbar navbar-expand navbar-white bg-white px-3 border-bottom">
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <div class="d-flex flex-grow-1 align-items-center">
                    <!-- Judul Halaman -->
                    <div class="d-flex align-items-center">
                        <h1 class="mb-0" style="font-size: 36px; font-weight: bold;">Laporan Obat</h1>
                    </div>

                    <!-- Spacer -->
                    <div class="flex-grow-1"></div>

                    <!-- Good Morning and Date Display -->
                    <div class="d-flex align-items-center me-3">
                        <div class="me-auto" style="text-align: right;">
                            <span style="font-weight: bold; font-size: 36px;" id="logo"></span>
                            <span style="font-weight: bold; font-size: 24px;" id="greeting"></span>
                            <br>
                            <span style="font-size: 16px;" id="currentDateTime" class="ms-2"></span>
                        </div>
                    </div>

                </div>
            </nav>

            <main>
                <div class="container-fluid py-4 px-3" style="background: #f6f8fa;">
                    <!-- Judul & Filter -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="text-muted" style="font-size: 1rem;">
                                Supply related report of the pharmacy.
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary rounded-3 px-4 fw-semibold" style="font-size: 1rem;">
                                Download Report <i class="bi bi-chevron-down ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3 mb-2">
                            <label for="dateRange" class="form-label fw-semibold">Date Range</label>
                            <input type="text" id="dateRange" class="form-control" value="01 December 2021 - 31 December 2021" readonly style="background: #f3f6fa;">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="medicineGroup" class="form-label fw-semibold">Medicine Group</label>
                            <select id="medicineGroup" class="form-select" style="background: #f3f6fa;">
                                <option selected>- Select Group -</option>
                            </select>
                        </div>
                    </div>
                    <!-- Chart & Table -->
                    <div class="row">
                        <!-- Chart Area -->
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Medicine Stock</h6>
                                    <canvas id="medicineStockChart" height="220"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Table Area -->
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="row fw-bold border-bottom pb-2 mb-2" style="font-size: 1.02rem;">
                                        <div class="col-2">NO ID</div>
                                        <div class="col-4">NAMA LAPORAN</div>
                                        <div class="col-3">JENIS LAPORAN</div>
                                        <div class="col-3">WAKTU</div>
                                    </div>
                                    <div style="height: 285px; overflow-y: auto;">
                                    <?php 
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                    ?>
                                        <div class="row border-bottom py-2 align-items-center" style="font-size: .98rem;">
                                            <div class="col-2"><?php echo $row['id_laporan']?></div>
                                            <div class="col-4"><?php echo $row['nama_laporan']?></div>
                                            <div class="col-3"><?php echo $row['jenis_laporan']?></div>
                                            <div class="col-3"><?php echo $row['tgl_laporan']?></div>
                                        </div>
                                    <?php 
                                            }
                                        } else {
                                            echo '
                                                <div class="row border-bottom py-2 align-items-center" style="font-size: .98rem;">
                                                    <div class="col-2">DATA</div>
                                                    <div class="col-4">TIDAK</div>
                                                    <div class="col-3">DITEMUKAN</div>
                                                    <div class="col-3"> </div>
                                                </div>
                                                ';
                                        }
                                        echo "</div>";
                                        $conn->close();
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart.js CDN (optional, if not already loaded) -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Dummy data untuk chart, sesuaikan sesuai kebutuhan dari backend
                    const ctx = document.getElementById('medicineStockChart').getContext('2d');
                    const medicineStockChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['1 Dec', '8 Dec', '16 Dec', '31 Dec'],
                            datasets: [{
                                    label: 'Stock',
                                    data: [35, 150, 110, 146],
                                    borderColor: '#42a5f5',
                                    backgroundColor: 'rgba(66, 165, 245, 0.15)',
                                    fill: true,
                                    pointBackgroundColor: '#42a5f5',
                                    tension: 0.45
                                },
                                {
                                    label: 'Min Level',
                                    data: [20, 40, 60, 40],
                                    borderColor: '#f44336',
                                    backgroundColor: 'rgba(244, 67, 54, 0.10)',
                                    fill: true,
                                    pointBackgroundColor: '#f44336',
                                    tension: 0.2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    min: 0,
                                    max: 200,
                                    grid: {
                                        color: "#e0e6ed"
                                    },
                                    ticks: {
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 14
                                        }
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 5,
                                    hoverRadius: 7
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    });
                </script>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>