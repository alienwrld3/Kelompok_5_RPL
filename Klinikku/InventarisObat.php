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

//PHP Logic detail obat
$medicine = null;
if (isset($_GET['id_obat'])) {
    // Ambil id_obat dari URL
    $id_obat = $_GET['id_obat'];

    // Query untuk mengambil detail obat berdasarkan id_obat
    $query = "SELECT * FROM obat WHERE id_obat = $id_obat AND status_hapus = 0";
    $result = mysqli_query($conn, $query);

    // Cek apakah query berhasil dan data ada
    if (mysqli_num_rows($result) > 0) {
        $medicine = mysqli_fetch_assoc($result);  // Ambil data obat
    }
}

//PHP Logic tambah obat
if (isset($_POST['submit_newobat'])) {
    $modal_id = '';
    // Ambil data dari form
    $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $id_obat = mysqli_real_escape_string($conn, $_POST['id_obat']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $tgl_produksi = mysqli_real_escape_string($conn, $_POST['tgl_produksi']);
    $tgl_kadaluarsa = mysqli_real_escape_string($conn, $_POST['tgl_kadaluarsa']);
    $cara_pakai = mysqli_real_escape_string($conn, $_POST['cara_pakai']);
    $efek_samping = mysqli_real_escape_string($conn, $_POST['efek_samping']);
    $jenis_obat = mysqli_real_escape_string($conn, $_POST['jenis_obat']);
    $id_staff = $_SESSION['id_user'];

    $check_query = "SELECT * FROM obat WHERE id_obat = '$id_obat' AND status_hapus = 0";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika ID Obat sudah ada, beri tahu pengguna
        $modal_id = 'obatExistModal';
    } else {
        // Query untuk memasukkan data ke tabel obat
        $insert_query_obat = "INSERT INTO obat (id_obat, nama_obat, jenis_obat, stok, tgl_produksi, tgl_kadaluarsa, cara_pakai, efek_samping) 
                          VALUES ('$id_obat', '$nama_obat','$jenis_obat', '$stok', '$tgl_produksi', '$tgl_kadaluarsa', '$cara_pakai', '$efek_samping')";

        $insert_query_stokmasuk = "INSERT INTO stokmasuk (id_obat, jumlah_masuk, id_staff)
                            VALUES ('$id_obat', '$stok', $id_staff)";


        if (mysqli_query($conn, $insert_query_obat)) {
            if (mysqli_query($conn, $insert_query_stokmasuk)) {
                $modal_id = 'successModal';
            } else {
                $modal_id = 'failModal';
            }
        } else {
            $modal_id = 'failModal';

        }
    }
    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: InventarisObat.php");
        exit();
    }
}

//PHP Logic tambah Stok Obat
if (isset($_POST['submit_newstock'])) {
    $modal_id = '';
    $id_obat = mysqli_real_escape_string($conn, $_POST['id_obat']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $id_staff = $_SESSION['id_user'];

    $update_query = "UPDATE obat SET stok = stok + '$stok' WHERE id_obat = '$id_obat'";
    $insert_query_stokmasuk = "INSERT INTO stokmasuk (id_obat, jumlah_masuk, id_staff)
                            VALUES ('$id_obat', '$stok', $id_staff)";

    if (mysqli_query($conn, $update_query) && mysqli_query($conn, $insert_query_stokmasuk)) {
        $modal_id = 'successModal';
    } else {
        $modal_id = 'failModal';
    }

    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: InventarisObat.php");
        exit();
    }
}

//PHP Logic tambah grup
if (isset($_POST['submit_group'])) {
    $modal_id = '';
    // Ambil nama grup obat dari form
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);

    // Query untuk memeriksa apakah grup sudah ada
    $check_query = "SELECT * FROM grup WHERE nama_grup = '$group_name'";
    $check_result = mysqli_query($conn, $check_query);

    // Jika grup sudah ada
    if (mysqli_num_rows($check_result) > 0) {
        $modal_id = 'groupExistModal';
    } else {
        // Jika grup belum ada, insert data grup baru ke tabel grup
        $insert_query = "INSERT INTO grup (nama_grup) VALUES ('$group_name')";

        if (mysqli_query($conn, $insert_query)) {
            $modal_id = 'successModal';
        } else {
            $modal_id = 'failModal';
        }
    }

    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: InventarisObat.php");
        exit();
    }
}

//PHP Logic Edit Obat

if (isset($_POST['submit_editobat'])) {
    $modal_id = '';
    $id_obat_awal = mysqli_real_escape_string($conn, $_POST['id_obat']);
    $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $id_obat = mysqli_real_escape_string($conn, $_POST['idobat']);
    $jenis_obat = mysqli_real_escape_string($conn, $_POST['jenis_obat']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $tgl_produksi = mysqli_real_escape_string($conn, $_POST['tgl_produksi']);
    $tgl_kadaluarsa = mysqli_real_escape_string($conn, $_POST['tgl_kadaluarsa']);
    $cara_pakai = mysqli_real_escape_string($conn, $_POST['cara_pakai']);
    $efek_samping = mysqli_real_escape_string($conn, $_POST['efek_samping']);

    $query = "SELECT * FROM obat WHERE id_obat = $id_obat_awal AND status_hapus = 0";
    $result = mysqli_query($conn, $query);

    $query = "SELECT * FROM obat WHERE id_obat = $id_obat_awal AND status_hapus = 0";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika obat ditemukan, lakukan update
        $update_query = "UPDATE obat 
                         SET nama_obat = '$nama_obat', 
                             id_obat = '$id_obat', 
                             jenis_obat = '$jenis_obat', 
                             stok = '$stok', 
                             tgl_produksi = '$tgl_produksi', 
                             tgl_kadaluarsa = '$tgl_kadaluarsa', 
                             cara_pakai = '$cara_pakai', 
                             efek_samping = '$efek_samping'
                         WHERE id_obat = $id_obat_awal";

        if (mysqli_query($conn, $update_query)) {
            $modal_id = 'successModal';
        } else {
            $modal_id = 'failModal';
        }
    }
    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: InventarisObat.php");
        exit();
    }
}

//PHP Logic Hapus Obat
if (isset($_POST['submit_deleteobat'])) {
    $modal_id = '';
    $id_obat = mysqli_real_escape_string($conn, $_POST['id_obat']);
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    $id_staff = $_SESSION['id_user'];
    $jenis_pengeluaran = ($alasan == 'Kadaluarsa') ? 'Kadaluarsa' : 'Lainnya';

    $query_stok = "SELECT stok FROM obat WHERE id_obat = '$id_obat' AND status_hapus = 0";
    $result_stok = mysqli_query($conn, $query_stok);

    if (mysqli_num_rows($result_stok) > 0) {
        $row = mysqli_fetch_assoc($result_stok);
        $jumlah_keluar = $row['stok'];

        $query_stokkeluar = "INSERT INTO stokkeluar (id_obat, jenis_pengeluaran, jumlah_keluar, id_staff)
                             VALUES ('$id_obat', '$jenis_pengeluaran', '$jumlah_keluar', '$id_staff')";

        if (mysqli_query($conn, $query_stokkeluar)) {
            // Hapus obat setelah dicatat di stokkeluar
            $query_delete = "UPDATE obat SET status_hapus = 1 WHERE id_obat = '$id_obat'";
            if (mysqli_query($conn, $query_delete)) {
                $modal_id = 'successModal';
            } else {
                $modal_id = 'failModal';
            }
        } else {
            $modal_id = 'failModal';
        }
    } else {
        $modal_id = 'failModal';
    }
    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: InventarisObat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Inventaris Obat - Klinikku</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
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
                        <!-- Garis Divider -->
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

                        <!-- Garis Divider -->
                        <hr class="sidebar-divider my-0">

                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon">
                                <i class="bi bi-grid-1x2"></i>
                            </div>
                            Dashboard
                        </a>
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
                        <hr class="sidebar-divider my-0">
                    </div>
                </div>
            </nav>
        </div>

        <!-- Konten kanan -->
        <div id="layoutSidenav_content">
            <!-- Navbar Utama -->
            <nav class="navbar navbar-expand navbar-white bg-white px-3 border-bottom">
                <!-- Sidebar Toggle -->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <div class="d-flex flex-grow-1 align-items-center">
                    <!-- Judul Halaman -->
                    <div class="d-flex align-items-center">
                        <h1 class="mb-0" style="font-size: 36px; font-weight: bold;">Inventaris Obat</h1>
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
                <div class="container-fluid p-4 bg-light">
                    <!-- Card untuk tabel Obat Kadaluarsa -->
                    <div class="card mb-4 border-danger bg-white">
                        <div
                            class="card-header d-flex justify-content-start align-items-center bg-danger bg-opacity-25">
                            <div class="w-10 mt-2 ms-5">
                                <svg width="37" height="37" viewBox="0 0 37 37" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.5 9.23475L30.1088 29.2918H6.89127L18.5 9.23475ZM18.5 3.0835L1.54169 32.3752H35.4584L18.5 3.0835ZM20.0417 24.6668H16.9584V27.7502H20.0417V24.6668ZM20.0417 15.4168H16.9584V21.5835H20.0417V15.4168Z"
                                        fill="#F0483E" />
                                </svg>
                            </div>
                            <div class="w-10 mt-2 ms-2">
                                <h3 class="mt-0">Obat Kadaluarsa</h3>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <?php
                            //query untuk menampilkan data obat kadaluarsa
                            $query_kadaluarsa = "SELECT * FROM obat WHERE tgl_kadaluarsa < CURDATE() AND status_hapus = 0";  // Kondisi untuk memilih obat yang sudah kadaluarsa
                            $result = mysqli_query($conn, $query_kadaluarsa);

                            if (mysqli_num_rows($result) > 0) {
                                ?>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item active">Obat yang melebihi batas tanggal kadaluarsa</li>
                                </ol>
                                <table id="datatablesSimple2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>ID Obat</th>
                                            <th>Jumlah Stok</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>ID Obat</th>
                                            <th>Jumlah Stok</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>
                                                <td>" . $no++ . "</td>
                                                <td>" . $row['nama_obat'] . "</td>
                                                <td>" . $row['id_obat'] . "</td>
                                                <td>" . $row['stok'] . "</td>
                                                <td>" . $row['tgl_kadaluarsa'] . "</td>
                                                <td><a href='#' class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#deleteMedicineModal' data-id='" . $row['id_obat'] . "'>Buang</a></td>
                                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>Data tidak ditemukan</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo "<div style='text-align: center; font-size: 18px; color: red;'>Tidak ada obat kadaluarsa</div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Card untuk tabel Obat stok Kurang -->
                    <div class="card mb-4 border border-warning bg-white">
                        <div
                            class="card-header d-flex justify-content-start align-items-center bg-warning bg-opacity-25">
                            <div class="w-10 mt-2 ms-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" fill="rgb(255, 193, 7)"
                                    class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                                    <path
                                        d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z" />
                                    <path
                                        d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                </svg>
                            </div>
                            <div class="w-10 mt-2 ms-2">
                                <h3 class="mt-0">Stok Kurang</h3>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <?php
                            //query untuk menampilkan data obat stok Kurang
                            $query_stok_kurang = "SELECT * FROM obat WHERE stok < 20 AND status_hapus = 0";
                            $result = mysqli_query($conn, $query_stok_kurang);

                            if (mysqli_num_rows($result) > 0) {
                                ?>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item active">Obat dengan stok kurang dari 20 akan dianggap kurang
                                    </li>
                                </ol>
                                <table id="datatablesSimple3">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>ID Obat</th>
                                            <th>Jumlah Stok</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>ID Obat</th>
                                            <th>Jumlah Stok</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>
                                                <td>" . $no++ . "</td>
                                                <td>" . $row['nama_obat'] . "</td>
                                                <td>" . $row['id_obat'] . "</td>
                                                <td>" . $row['stok'] . "</td>
                                                <td>" . $row['tgl_kadaluarsa'] . "</td>
                                                <td><a href='#' data-bs-toggle='modal' class='text-decoration-none' data-bs-target='#addStockModal' data-id='" . $row['id_obat'] . "'>Tambah Stok</a></td>
                                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>Data tidak ditemukan</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo "<div style='text-align: center; font-size: 18px; color: rgb(255, 193, 7);'>Seluruh Stok Obat sudah terpenuhi</div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Card untuk tabel daftar obat -->
                    <div class="card mb-4 bg-white border border-primary">
                        <div
                            class="card-header d-flex justify-content-start align-items-center bg-primary bg-opacity-10">
                            <div class="w-10 mt-2 ms-5">
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
                            </div>

                            <div class="w-10 mt-2 ms-3">
                                <h3 class="mt-2">Daftar Obat</h3>
                            </div>

                            <!-- Button tambah obat di sebelah kanan -->
                            <div class="d-flex flex-column align-items-end ms-auto" style="width: 30%;">
                                <button class="btn btn-danger text-white btn-sm w-50 mt-2 mb-3"
                                    style="height: 36px; font-size: 16px;" data-bs-toggle="modal"
                                    data-bs-target="#addMedicineModal">
                                    + Obat Baru
                                </button>
                                <button class="btn btn-warning text-white btn-sm w-50 mb-2"
                                    style="height: 36px; font-size: 16px;" data-bs-toggle="modal"
                                    data-bs-target="#addGroupModal">
                                    + Tambah Grup
                                </button>
                            </div>
                        </div>

                        <!-- Card body untuk tabel obat -->
                        <div class="card-body bg-white">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Obat yang tersedia di Gudang</li>
                            </ol>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>ID Obat</th>
                                        <th>Nama Grup</th>
                                        <th>Jumlah Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>ID Obat</th>
                                        <th>Nama Grup</th>
                                        <th>Jumlah Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    //query untuk menampilkan data obat
                                    $query = "SELECT obat.id_obat, obat.nama_obat, obat.stok, grup.nama_grup 
                                                  FROM obat 
                                                  LEFT JOIN obat_grup ON obat.id_obat = obat_grup.id_obat 
                                                  LEFT JOIN grup ON obat_grup.id_grup = grup.id_grup
                                                  WHERE status_hapus = 0";
                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                    <td>" . $no++ . "</td>
                                                    <td>" . $row['nama_obat'] . "</td>
                                                    <td>" . $row['id_obat'] . "</td>
                                                    <td>" . $row['nama_grup'] . "</td>
                                                    <td>" . $row['stok'] . "<a href='#' data-bs-toggle='modal' data-bs-target='#addStockModal' data-id='" . $row['id_obat'] . "'><i class='bi bi-plus-square-fill' style='float: right; color: green; font-size: 18px;'></i></a></td>
                                                    <td><a href='InventarisObat.php?id_obat=" . $row['id_obat'] . "' class='text-decoration-none'>Lihat Detail</a></td>
                                                </tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Modal untuk Menambah Grup Obat -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGroupModalLabel">Tambah Grup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="InventarisObat.php" method="POST" id="addGroupForm">
                        <div class="mb-3">
                            <label for="groupName" class="form-label">Nama Grup</label>
                            <input type="text" class="form-control" name="group_name" id="newGroupName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="submit_group"
                        form="addGroupForm">Tambah</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal untuk Menampilkan Detail Obat -->
    <?php
    if ($medicine != null) {
        ?>
        <div class="modal fade" id="viewMedicineModal" tabindex="-1" aria-labelledby="viewMedicineModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMedicineModalLabel">Detail Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Medicine Details -->
                        <div id="medicineDetails">
                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="detailNamaObat" class="form-label">Nama Obat</label>
                                    <input type="text" class="form-control" id="detailNamaObat"
                                        value="<?= $medicine['nama_obat']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="detailIdObat" class="form-label">ID Obat</label>
                                    <input type="text" class="form-control" id="detailIdObat"
                                        value="<?= $medicine['id_obat']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="detailStok" class="form-label">Stok</label>
                                    <input type="text" class="form-control" id="detailStok"
                                        value="<?= $medicine['stok']; ?>" readonly>
                                </div>
                                <div class="col-md-8">
                                    <label for="detailJenisObat" class="form-label">Jenis Obat</label>
                                    <input type="text" class="form-control" id="detailJenisObat"
                                        value="<?= $medicine['jenis_obat']; ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="detailCaraPakai" class="form-label">Cara Pakai</label>
                                    <input type="text" class="form-control" id="detailCaraPakai"
                                        value="<?= $medicine['cara_pakai']; ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="detailEfekSamping" class="form-label">Efek Samping</label>
                                    <input type="text" class="form-control" id="detailEfekSamping"
                                        value="<?= $medicine['efek_samping']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="detailTglProduksi" class="form-label">Tanggal Produksi</label>
                                    <input type="text" class="form-control" id="detailTglProduksi"
                                        value="<?= $medicine['tgl_produksi']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="detailExp" class="form-label">Tanggal Kadaluarsa</label>
                                    <input type="text" class="form-control" id="detailExp"
                                        value="<?= $medicine['nama_obat']; ?>" readonly>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal"
                            data-bs-target="#editMedicineModal">Edit</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteMedicineModal">Buang</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('viewMedicineModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                modalEl.addEventListener("hidden.bs.modal", () => {
                    history.replaceState({}, "", window.location.pathname);
                })
            });
        </script>
        <?php
    }
    ?>

    <!-- Modal untuk Tambah Obat -->
    <div class="modal fade" id="addMedicineModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Tambah Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="InventarisObat.php" method="POST" id="addMedicineForm">
                        <div class="mb-3">
                            <label for="addMedicineName" class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" name="nama_obat" id="addMedicineName" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMedicineID" class="form-label">ID Obat</label>
                            <input type="text" class="form-control" name="id_obat" id="addMedicineID" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMedicineStock" class="form-label">Stok Awal</label>
                            <input type="number" class="form-control" name="stok" id="addMedicineStock" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMedicineType" class="form-label">Jenis Obat</label>
                            <input type="text" class="form-control" name="jenis_obat" id="addMedicineType" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductionDate" class="form-label">Tanggal Produksi</label>
                            <input type="date" class="form-control" name="tgl_produksi" id="addProductionDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="addExpiryDate" class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" name="tgl_kadaluarsa" id="addExpiryDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUsage" class="form-label">Cara Pakai</label>
                            <textarea class="form-control" name="cara_pakai" id="addUsage" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addSideEffects" class="form-label">Efek Samping</label>
                            <textarea class="form-control" name="efek_samping" id="addSideEffects" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="submit_newobat"
                        form="addMedicineForm">Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah Stok Obat -->
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Tambah Stok Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="InventarisObat.php" method="POST" id="addStockForm">
                        <input type="hidden" id="addStockObatId" name="id_obat">
                        <div class="mb-3">
                            <label for="addMedicineStock" class="form-label">Jumlah Stok Tambahan</label>
                            <input type="number" class="form-control" name="stok" id="addMedicineStock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="submit_newstock"
                        form="addStockForm">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("addStockModal");
            const obatIdEl = document.getElementById("addStockObatId");
            modal.addEventListener("show.bs.modal", (e) => {
                const element = e.relatedTarget;
                let obatId = element.dataset["id"];
                if (!obatId) {
                    const params = new URLSearchParams(window.location.search);
                    obatId = params.get("id_obat");
                }
                obatIdEl.value = obatId;
            });
        })

    </script>

    <!-- Modal untuk Mengedit Obat -->
    <div class="modal fade" id="editMedicineModal" tabindex="-1" aria-labelledby="editMedicineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMedicineModalLabel">Edit Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Edit Medicine Form -->
                    <form action="InventarisObat.php" method="POST" id="editMedicineForm">
                        <input type="hidden" id="editMedicineObatId" name="id_obat">
                        <div class="mb-3">
                            <label for="editMedicineName" class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" name="nama_obat" id="editMedicineName" value="<?= $medicine['nama_obat']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editMedicineID" class="form-label">ID Obat</label>
                            <input type="text" class="form-control" name="idobat" id="editMedicineID" value="<?= $medicine['id_obat']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editMedicineType" class="form-label">Jenis Obat</label>
                            <input type="text" class="form-control" name="jenis_obat" id="editMedicineType" value="<?= $medicine['jenis_obat']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editMedicineStock" class="form-label">Stok Obat</label>
                            <input type="number" class="form-control" name="stok" id="editMedicineStock" value="<?= $medicine['stok']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editProductionDate" class="form-label">Tanggal Produksi</label>
                            <input type="date" class="form-control" name="tgl_produksi" id="editProductionDate" value="<?= $medicine['tgl_produksi']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editExpiryDate" class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" name="tgl_kadaluarsa" id="editExpiryDate" value="<?= $medicine['tgl_kadaluarsa']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editUsage" class="form-label">Cara Pakai</label>
                            <textarea class="form-control" name="cara_pakai" id="editUsage"><?= $medicine['cara_pakai']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editSideEffects" class="form-label">Efek Samping</label>
                            <textarea class="form-control" name="efek_samping" id="editSideEffects"><?= $medicine['efek_samping']; ?></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="submit_editobat" form="editMedicineForm">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("editMedicineModal");
            const obatIdEl = document.getElementById("editMedicineObatId");
            modal.addEventListener("show.bs.modal", (e) => {
                const element = e.relatedTarget;
                let obatId = element.dataset["id"];
                if (!obatId) {
                    const params = new URLSearchParams(window.location.search);
                    obatId = params.get("id_obat");
                }
                obatIdEl.value = obatId;
            });
        })

    </script>

    <!-- Modal untuk Menghapus Obat -->
    <div class="modal fade" id="deleteMedicineModal" tabindex="-1" aria-labelledby="deleteMedicineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMedicineModalLabel">Konfirmasi Penghapusan Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus obat ini dari inventaris?
                    <form action="InventarisObat.php" method="POST" id="deleteMedicineForm">
                        <input type="hidden" id="deleteMedicineObatId" name="id_obat">
                        <div class="mb-3">
                            <br>
                            <label for="reasonSelect" class="form-label">Alasan Pengeluaran</label>
                            <select class="form-select" name="alasan" id="reasonSelect" required>
                                <option value="Kadaluarsa">Kadaluarsa</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger " name="submit_deleteobat"
                        form="deleteMedicineForm">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("deleteMedicineModal");
            const obatIdEl = document.getElementById("deleteMedicineObatId");
            modal.addEventListener("show.bs.modal", (e) => {
                const element = e.relatedTarget;
                let obatId = element.dataset["id"];
                if (!obatId) {
                    const params = new URLSearchParams(window.location.search);
                    obatId = params.get("id_obat");
                }
                obatIdEl.value = obatId;
            });
        })

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <?php

    $modal_id = "";

    if (isset($_SESSION["modal_id"])) {
        $modal_id = $_SESSION["modal_id"];
        unset($_SESSION["modal_id"]);
    }

    ?>
    <script>
        var modalId = '<?php echo $modal_id; ?>';

        // Tampilkan SweetAlert2 berdasarkan nilai modalId
        if (modalId) {
            if (modalId === 'groupExistModal') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Grup sudah ada!',
                    confirmButtonText: 'Tutup'
                });
            } else if (modalId === 'successModal') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Berhasil!',
                    confirmButtonText: 'Tutup'
                });
            } else if (modalId === 'failModal') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan',
                    confirmButtonText: 'Tutup'
                });
            } else if (modalId === 'obatExistModal') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'ID Obat sudah ada!',
                    confirmButtonText: 'Tutup'
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>