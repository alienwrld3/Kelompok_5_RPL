<?php
session_start();
require 'function.php';

//validasi jika belum pernah login 
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <!-- Sidebar kiri -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="me-auto ms-3 mb-3 ">
                            <img src="assets/img/Screenshot 2025-04-24 174922.png" alt="Icon"
                                style="width:50px;height:auto;" />
                            <span style="font-size: 24px; font-weight: bold; color: white;">Klinikku</span>
                        </div>
                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">
                        <div class="sb-sidenav-header d-flex align-items-center">
                            <div class="me-auto ms-3 mb-3">
                                Username : Admin
                                <div class="small">Role : Admin</div>
                            </div>
                            <div class="user-dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user fa-fw"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <!-- Sidebar Obat dan Transaksi -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseObat"
                            aria-expanded="false" aria-controls="collapseObat">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Obat dan Transaksi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseObat" aria-labelledby="headingObat"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Inventaris Obat.html">Inventaris Obat</a>
                                <a class="nav-link" href="Riwayat Transaksi.html">Riwayat Transaksi</a>
                            </nav>
                        </div>

                        <!-- Sidebar Laporan -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLaporan" aria-expanded="false" aria-controls="collapseLaporan">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Laporan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLaporan" aria-labelledby="headingLaporan">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Laporan Obat.html">Laporan Obat</a>
                                <a class="nav-link" href="Laporan Transaksi.html">Laporan Transaksi</a>
                            </nav>
                        </div>
                        <!-- garis divider -->
                        <hr class="sidebar-divider my-0">

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Pages
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Konten kanan -->
        <div id="layoutSidenav_content">
            <!-- Navbar berada di dalam main, atas konten dashboard -->
            <nav class="navbar navbar-expand navbar-white bg-white px-3 mb-2">
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Container flex untuk search bar -->
                <div class="d-flex flex-grow-1 align-items-center">
                    <!-- Search bar -->
                    <div class="flex-grow-1" style="max-width: 50%;">
                        <form>
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari di sini..."
                                    aria-label="Search" />
                                <button class="btn btn-primary" type="submit" id="btnNavbarSearch">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Spacer -->
                    <div class="flex-grow-1"></div>

                    <!-- Good Morning and Date Display -->
                    <div class="d-flex align-items-center me-5">
                        <div class="me-auto" style="text-align: right;">
                            <span style="font-weight: bold; font-size: 36px;" id="logo"></span>
                            <span style="font-weight: bold; font-size: 24px;" id="greeting"></span>
                            <br>
                            <span style="font-size: 16px;" id="currentDateTime" class="ms-2"></span>
                        </div>
                    </div>

                </div>
            </nav>

            <script>
                // Fungsi untuk update greeting dan tanggal
                function updateGreetingAndTime() {
                    const now = new Date();
                    const currentHour = now.getHours();

                    //Menentukan greeting berdasarkan jam saat ini
                    let greeting = "Good Morning";
                    let logo = "☀️";

                    if (currentHour >= 12 && currentHour < 17) {
                        greeting = "Good Afternoon";
                        logo = "🌤️";
                    } else if (currentHour >= 17) {
                        greeting = "Good Evening";
                        logo = "🌙";
                    }

                    // Format tanggal
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const datePart = now.toLocaleDateString('en-US', options); 
                    const timePart = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false }); 
                    const formattedDate = `${datePart} • ${timePart}`; 

                    // Update ke HTML
                    document.getElementById("logo").textContent = logo;
                    document.getElementById("greeting").textContent = greeting;
                    document.getElementById("currentDateTime").textContent = formattedDate;
                }
                // Jalankan fungsi
                updateGreetingAndTime();

                // Optional: Update Setiap detik
                setInterval(updateGreetingAndTime, 1000);
            </script>

            <main>
                <!-- Konten dashboard -->
                <div class="container-fluid px-4 bg-light">
                    <h1 class="mt-0">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Informasi umum data gudang</li>
                    </ol>
                    <div class="row bg-light py-4">
                        <!-- Card Info obat Hijau Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-success mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <img src="logo.png" alt="Logo" style="max-height: 80px;" />
                                    <div class="text-center mt-2">
                                        <div class="fw-bold fs-3">Good</div>
                                        <div class="fw-bold">Status Gudang</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-success bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none" href="#">Lihat
                                        Laporan</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Info obat Biru Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-primary mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <img src="logo.png" alt="Logo" style="max-height: 80px;" />
                                    <div class="text-center mt-2">
                                        <div class="fw-bold fs-3">123</div>
                                        <div class="fw-bold">Obat Tersedia</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-primary bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none" href="#">Lihat
                                        Inventaris</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card info obat Kuning Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-warning mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <img src="logo.png" alt="Logo" style="max-height: 80px;" />
                                    <div class="text-center mt-2">
                                        <div class="fw-bold fs-3">05</div>
                                        <div class="fw-bold">Obat Stok Habis</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-warning bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none" href="#">Lihat
                                        Detail</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card info obat Merah Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-danger mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <img src="logo.png" alt="Logo" style="max-height: 80px;" />
                                    <div class="text-center mt-2">
                                        <div class="fw-bold fs-3">01</div>
                                        <div class="fw-bold">Obat Kadaluarsa</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-danger bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none"
                                        href="#">Selesaikan</a>
                                    <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="container-fluid px-4 bg-white">
                    <div class="row g-4 bg-white py-4">
                        <!-- Quick Report -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                    <h6 class="mb-0 fw-bold">Quick Report</h6>
                                    <div class="dropdown m-0">
                                        <button class="btn btn-transparent text-dark dropdown-toggle" type="button"
                                            id="dropdownQuickReport" data-bs-toggle="dropdown" aria-expanded="false">
                                            January 2025
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownQuickReport">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks1('Januari 2025')">Januari 2025</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks1('Desember 2024')">Desember 2024</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks1('November 2024')">November 2024</a></li>
                                            <script>
                                                function ubahTeks1(teks) {
                                                    document.getElementById("dropdownQuickReport").textContent = teks;
                                                }
                                            </script>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col">
                                            <h4 class="fw-bold mb-0">70,856</h4>
                                            <small class="text-muted">Qty of Medicines Sold</small>
                                        </div>
                                        <div class="col">
                                            <h4 class="fw-bold mb-0">5,288</h4>
                                            <small class="text-muted">Invoices Generated</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customers -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                    <h6 class="mb-0 fw-bold">Customers</h6>
                                    <div class="dropdown m-0">
                                        <button class="btn btn-transparent text-dark dropdown-toggle" type="button"
                                            id="dropdownCustomers" data-bs-toggle="dropdown" aria-expanded="false">
                                            January 2025
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownCustomers">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks2('Januari 2025')">Januari 2025</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks2('Desember 2024')">Desember 2024</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="ubahTeks2('November 2024')">November 2024</a></li>
                                            <script>
                                                function ubahTeks2(teks) {
                                                    document.getElementById("dropdownCustomers").textContent = teks;
                                                }
                                            </script>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col">
                                            <h4 class="fw-bold mb-0">845</h4>
                                            <small class="text-muted">Total no of Customers</small>
                                        </div>
                                        <div class="col">
                                            <h4 class="fw-bold mb-0">Adalimumab</h4>
                                            <small class="text-muted">Frequently bought Item</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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