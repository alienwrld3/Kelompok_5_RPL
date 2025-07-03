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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Klinikku</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"    />

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

                        <!-- Profil Pengguna -->
                        <div class="user-info px-3 py-2 d-flex align-items-center">
                            <img src="assets/img/1702635599933.jpg" class="rounded-circle object-fit-cover"
                                style="width: 48px; height: 48px;" alt="User" />
                            <div class="ms-2">
                                <div><?= $nama ?></div>
                                <small class="text-success"><?= $role ?></small>
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
            <!-- Navbar utama -->
            <nav class="navbar navbar-expand navbar-white bg-white px-3 border-bottom">
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <div class="d-flex flex-grow-1 align-items-center">
                    <!-- Judul Halaman -->
                    <div class="d-flex align-items-center">
                        <h1 class="mb-0" style="font-size: 36px; font-weight: bold;">Dashboard</h1>
                    </div>

                    <!-- Spacer -->
                    <div class="flex-grow-1"></div>

                    <!-- Display Date dan Time -->
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
                <!-- Konten dashboard -->
                <div class="container-fluid p-4 bg-light">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active" style="font-size: 20px;">Informasi umum data gudang</li>
                    </ol>
                    <div class="row bg-light py-4">
                        <!-- Card Info obat Hijau Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-success mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <svg width="37" height="37" viewBox="0 0 37 37" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1_123)">
                                            <path
                                                d="M16.1875 20.0418H12.3333V15.4168H16.1875V11.5627H20.8125V15.4168H24.6666V20.0418H20.8125V23.896H16.1875V20.0418ZM18.5 3.0835L6.16663 7.7085V17.0972C6.16663 24.8827 11.4237 32.1439 18.5 33.9168C25.5762 32.1439 30.8333 24.8827 30.8333 17.0972V7.7085L18.5 3.0835ZM27.75 17.0972C27.75 23.2639 23.8187 28.9681 18.5 30.7102C13.1812 28.9681 9.24996 23.2793 9.24996 17.0972V9.85141L18.5 6.38266L27.75 9.85141V17.0972Z"
                                                fill="#01A768" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1_123">
                                                <rect width="37" height="37" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>

                                    <div class="text-center mt-2">
                                        <div class="fw-bold fs-3">Good</div>
                                        <div class="fw-bold">Status Gudang</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-success bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none" href="#">
                                        Lihat Laporan
                                    </a>
                                    <div class="small text-dark"><i class="bi bi-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Info obat Biru Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-primary mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <svg width="37" height="37" viewBox="0 0 37 37" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1_212)">
                                            <path
                                                d="M30.8333 9.25016H24.6666V6.16683C24.6666 4.471 23.2791 3.0835 21.5833 3.0835H15.4166C13.7208 3.0835 12.3333 4.471 12.3333 6.16683V9.25016H6.16665C4.47081 9.25016 3.08331 10.6377 3.08331 12.3335V30.8335C3.08331 32.5293 4.47081 33.9168 6.16665 33.9168H30.8333C32.5291 33.9168 33.9166 32.5293 33.9166 30.8335V12.3335C33.9166 10.6377 32.5291 9.25016 30.8333 9.25016ZM15.4166 6.16683H21.5833V9.25016H15.4166V6.16683ZM30.8333 30.8335H6.16665V12.3335H30.8333V30.8335Z"
                                                fill="#03A9F5" />
                                            <path
                                                d="M20.0416 15.417H16.9583V20.042H12.3333V23.1253H16.9583V27.7503H20.0416V23.1253H24.6666V20.042H20.0416V15.417Z"
                                                fill="#03A9F5" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1_212">
                                                <rect width="37" height="37" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>

                                    <div class="text-center mt-2">
                                        <?php
                                        // Query untuk menghitung jumlah obat yang ada di inventaris
                                        $query_total_obat = "SELECT SUM(stok) AS total_obat FROM obat"; // Total stok obat
                                        $result = mysqli_query($conn, $query_total_obat);
                                        $row = mysqli_fetch_assoc($result);
                                        $total_obat = $row['total_obat']; // Mendapatkan jumlah total stok obat
                                        ?>
                                        <div class="fw-bold fs-3"><?= $total_obat ?></div>
                                        <div class="fw-bold">Obat Tersedia</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-primary bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none"
                                        href="InventarisObat.php">Lihat
                                        Inventaris</a>
                                    <div class="small text-dark"><i class="bi bi-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card info obat Kuning Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-warning mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37"
                                        fill="rgb(255, 193, 7)" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                                        <path
                                            d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z" />
                                        <path
                                            d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                    </svg>

                                    <div class="text-center mt-2">
                                        <?php
                                        // Query untuk menghitung jumlah obat stok kurang
                                        $query_stok_kurang = "SELECT COUNT(*) AS stok_kurang 
                                                                FROM obat 
                                                                WHERE stok < 20 AND status_hapus = 0";
                                        $result = mysqli_query($conn, $query_stok_kurang);
                                        $row = mysqli_fetch_assoc($result);
                                        $total_stok_kurang = $row['stok_kurang'];
                                        ?>
                                        <div class="fw-bold fs-3"><?= $total_stok_kurang ?></div>
                                        <div class="fw-bold">Stok Obat Hampir Habis</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-warning bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none"
                                        href="InventarisObat.php">Lihat
                                        Detail</a>
                                    <div class="small text-dark"><i class="bi bi-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card info obat Merah Samar -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border border-danger mb-4">
                                <div class="card-body bg-white text-dark d-flex flex-column justify-content-center align-items-center"
                                    style="height: 150px;">
                                    <svg width="37" height="37" viewBox="0 0 37 37" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.5 9.23475L30.1088 29.2918H6.89127L18.5 9.23475ZM18.5 3.0835L1.54169 32.3752H35.4584L18.5 3.0835ZM20.0417 24.6668H16.9584V27.7502H20.0417V24.6668ZM20.0417 15.4168H16.9584V21.5835H20.0417V15.4168Z"
                                            fill="#F0483E" />
                                    </svg>

                                    <div class="text-center mt-2">
                                        <?php
                                        // Query untuk menghitung jumlah obat yang kadaluarsa
                                        $query_kadaluarsa_count = "SELECT COUNT(*) AS total_kadaluarsa 
                                           FROM obat 
                                           WHERE tgl_kadaluarsa < CURDATE() AND status_hapus = 0"; // Kondisi untuk memilih obat yang sudah kadaluarsa
                                        $result = mysqli_query($conn, $query_kadaluarsa_count);
                                        $row = mysqli_fetch_assoc($result);
                                        $total_kadaluarsa = $row['total_kadaluarsa']; // Mendapatkan jumlah obat yang kadaluarsa
                                        ?>
                                        <div class="fw-bold fs-3"><?= $total_kadaluarsa ?></div>
                                        <div class="fw-bold">Obat Kadaluarsa</div>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-danger bg-opacity-25 text-dark d-flex align-items-center justify-content-between">
                                    <a class="small text-dark stretched-link text-decoration-none"
                                        href="InventarisObat.php">Selesaikan</a>
                                    <div class="small text-dark"><i class="bi bi-chevron-right"></i></div>
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
                                    <!-- Dropdown Filter Bulan -->
                                    <div class="dropdown m-0">
                                        <button class="btn btn-transparent text-dark dropdown-toggle" type="button"
                                            id="dropdownQuickReport" data-bs-toggle="dropdown" aria-expanded="false">
                                            <!-- Default value showing current month -->
                                            July 2025
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownQuickReport"
                                            id="dropdownMenu1">
                                            <!-- Dropdown items will be generated dynamically by JavaScript -->
                                        </ul>
                                    </div>
                                    <script>
                                        // JavaScript untuk menampilkan dropdown bulan dinamis
                                        document.addEventListener("DOMContentLoaded", function () {
                                            // Ambil tanggal bulan ini
                                            const currentDate = new Date();
                                            const currentMonth = currentDate.getMonth();  // 0 - 11 (0 = Januari)
                                            const currentYear = currentDate.getFullYear();

                                            // Dapatkan bulan ini dalam format: "Bulan Tahun" (e.g. "Juli 2025")
                                            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

                                            const dropdownMenu1 = document.getElementById("dropdownMenu1");
                                            const currentMonthText = months[currentMonth] + " " + currentYear;

                                            // Set default value pada dropdown
                                            document.getElementById("dropdownQuickReport").textContent = currentMonthText;

                                            // Array untuk menyimpan 4 bulan (bulan ini + 3 bulan sebelumnya)
                                            const monthsToDisplay = [currentMonthText];

                                            // Tambahkan 3 bulan sebelumnya
                                            for (let i = 1; i <= 3; i++) {
                                                const previousMonthDate = new Date(currentYear, currentMonth - i, 1); // Dapatkan tanggal untuk bulan sebelumnya
                                                const previousMonthText = months[previousMonthDate.getMonth()] + " " + previousMonthDate.getFullYear();
                                                monthsToDisplay.push(previousMonthText);
                                            }

                                            // Tambahkan "All Time" di awal
                                            monthsToDisplay.unshift('All Time');

                                            // Buat dropdown items
                                            monthsToDisplay.forEach(monthText => {
                                                const menuItem = document.createElement("li");
                                                const anchorTag = document.createElement("a");
                                                anchorTag.classList.add("dropdown-item");
                                                anchorTag.href = "#";
                                                anchorTag.textContent = monthText;

                                                // Set onclick event untuk mengubah teks dropdown dan mengirim filter bulan
                                                anchorTag.onclick = function () {
                                                    document.getElementById("dropdownQuickReport").textContent = monthText;
                                                    // Kirim data bulan yang dipilih ke server melalui AJAX
                                                    sendMonthData(monthText);
                                                };

                                                menuItem.appendChild(anchorTag);
                                                dropdownMenu1.appendChild(menuItem);
                                            });
                                        });

                                        // Fungsi untuk mengirim filter bulan ke server menggunakan AJAX
                                        function sendMonthData(monthText) {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.open("POST", "RiwayatTransaksi.php", true);
                                            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                                            // Kirim nilai bulan yang dipilih
                                            xhttp.send("filter_month=" + monthText);

                                            // Menunggu response dari server
                                            xhttp.onreadystatechange = function () {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    // Tampilkan data yang diterima dari server
                                                    document.getElementById("reportResults").innerHTML = this.responseText;
                                                }
                                            };
                                        }

                                    </script>
                                </div>
                                <div class="card-body">
                                    <?php
                                    //Query menampilkan obat terjual
                                    $query = "SELECT SUM(sk.jumlah_keluar) AS total_terjual
                                                FROM stokkeluar sk
                                                WHERE sk.jenis_pengeluaran = 'Transaksi'";
                                    $result = mysqli_query($conn, $query);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $total_terjual = $row['total_terjual'] ?? 0;
                                    }
                                    ?>
                                    <div class="row text-center">
                                        <div class="col">
                                            <h4 class="fw-bold mb-0"><?= $total_terjual ?></h4>
                                            <small class="text-muted">Obat Terjual</small>
                                        </div>
                                        <div class="col">
                                            <?php
                                            // Query untuk menghitung jenis obat terjual
                                            $query_nama_obat = "SELECT COUNT(nama_obat) AS total_nama_obat FROM obat";
                                            $result = mysqli_query($conn, $query_nama_obat);
                                            $row = mysqli_fetch_assoc($result);
                                            $total_nama_obat = $row['total_nama_obat'];
                                            ?>
                                            <h4 class="fw-bold mb-0"><?= $total_nama_obat ?></h4>
                                            <small class="text-muted">Jenis Obat di Gudang</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customers -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                    <h6 class="mb-0 fw-bold">Transaksi</h6>
                                    <!-- Dropdown Filter Bulan -->
                                    <div class="dropdown m-0">
                                        <button class="btn btn-transparent text-dark dropdown-toggle" type="button"
                                            id="dropdownTransaksi" data-bs-toggle="dropdown" aria-expanded="false">
                                            <!-- Default value showing current month -->
                                            July 2025
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownTransaksi"
                                            id="dropdownMenu2">
                                            <!-- Dropdown items will be generated dynamically by JavaScript -->
                                        </ul>
                                    </div>
                                    <script>
                                        // JavaScript untuk menampilkan dropdown bulan dinamis
                                        document.addEventListener("DOMContentLoaded", function () {
                                            // Ambil tanggal bulan ini
                                            const currentDate = new Date();
                                            const currentMonth = currentDate.getMonth();  // 0 - 11 (0 = Januari)
                                            const currentYear = currentDate.getFullYear();

                                            // Dapatkan bulan ini dalam format: "Bulan Tahun" (e.g. "Juli 2025")
                                            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

                                            const dropdownMenu2 = document.getElementById("dropdownMenu2");
                                            const currentMonthText = months[currentMonth] + " " + currentYear;

                                            // Set default value pada dropdown
                                            document.getElementById("dropdownTransaksi").textContent = currentMonthText;

                                            // Array untuk menyimpan 4 bulan (bulan ini + 3 bulan sebelumnya)
                                            const monthsToDisplay = [currentMonthText];

                                            // Tambahkan 3 bulan sebelumnya
                                            for (let i = 1; i <= 3; i++) {
                                                const previousMonthDate = new Date(currentYear, currentMonth - i, 1); // Dapatkan tanggal untuk bulan sebelumnya
                                                const previousMonthText = months[previousMonthDate.getMonth()] + " " + previousMonthDate.getFullYear();
                                                monthsToDisplay.push(previousMonthText);
                                            }

                                            // Tambahkan "All Time" di awal
                                            monthsToDisplay.unshift('All Time');

                                            // Buat dropdown items
                                            monthsToDisplay.forEach(monthText => {
                                                const menuItem = document.createElement("li");
                                                const anchorTag = document.createElement("a");
                                                anchorTag.classList.add("dropdown-item");
                                                anchorTag.href = "#";
                                                anchorTag.textContent = monthText;

                                                // Set onclick event untuk mengubah teks dropdown dan mengirim filter bulan
                                                anchorTag.onclick = function () {
                                                    document.getElementById("dropdownTransaksi").textContent = monthText;
                                                    // Kirim data bulan yang dipilih ke server melalui AJAX
                                                    sendMonthData(monthText);
                                                };

                                                menuItem.appendChild(anchorTag);
                                                dropdownMenu1.appendChild(menuItem);
                                            });
                                        });

                                        // Fungsi untuk mengirim filter bulan ke server menggunakan AJAX
                                        function sendMonthData(monthText) {
                                            var xhttp = new XMLHttpRequest();
                                            xhttp.open("POST", "RiwayatTransaksi.php", true);
                                            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                                            // Kirim nilai bulan yang dipilih
                                            xhttp.send("filter_month=" + monthText);

                                            // Menunggu response dari server
                                            xhttp.onreadystatechange = function () {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    // Tampilkan data yang diterima dari server
                                                    document.getElementById("reportResults").innerHTML = this.responseText;
                                                }
                                            };
                                        }

                                    </script>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col">
                                            <?php
                                            // Query untuk menghitung jumlah Transaksi dibuat
                                            $query_transaksi = "SELECT COUNT(*) AS total_transaksi FROM transaksi";
                                            $result = mysqli_query($conn, $query_transaksi);
                                            $row = mysqli_fetch_assoc($result);
                                            $total_transaksi = $row['total_transaksi'];
                                            ?>
                                            <h4 class="fw-bold mb-0"><?= $total_transaksi ?></h4>
                                            <small class="text-muted">Transaksi Dibuat</small>
                                        </div>
                                        <?php
                                        //Query untuk mencari Obat paling sering terjual
                                        $query = "SELECT o.nama_obat, COUNT(*) AS total_terjual
                                                    FROM stokkeluar sk
                                                    JOIN obat o ON sk.id_obat = o.id_obat
                                                    WHERE sk.jenis_pengeluaran = 'Transaksi'
                                                    GROUP BY o.nama_obat
                                                    ORDER BY total_terjual DESC
                                                    LIMIT 1";
                                        $result = mysqli_query($conn, $query);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $nama_obat = $row['nama_obat'];
                                            $total_terjual = $row['total_terjual'];
                                        }
                                        ?>
                                        <div class="col">
                                            <h4 class="fw-bold mb-0"><?= $nama_obat ?></h4>
                                            <small class="text-muted">Item Paling Sering Terjual</small>
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
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>