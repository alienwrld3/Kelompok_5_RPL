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
//PHP Logic detail Transaksi
$transaction = null;
if (isset($_GET['id_transaksi'])) {
    // Ambil id_transaksi dari URL
    $id_transaksi = $_GET['id_transaksi'];

    $query = "SELECT 
                t.id_transaksi, 
                t.tgl_transaksi, 
                t.keterangan,
                o.nama_obat, 
                sk.jumlah_keluar
              FROM transaksi t
              JOIN stokkeluar sk ON t.id_transaksi = sk.id_transaksi
              JOIN obat o ON sk.id_obat = o.id_obat
              WHERE t.id_transaksi = '$id_transaksi'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $transaction = [
            'id_transaksi' => $id_transaksi,
            'tgl_transaksi' => '',
            'keterangan' => '',
            'obat' => [],
            'id_staff' => $id_user, 
            'nama_staff' => $nama 
        ];

        while ($row = mysqli_fetch_assoc($result)) {
            $transaction['tgl_transaksi'] = $row['tgl_transaksi'];
            $transaction['keterangan'] = $row['keterangan'];

            $transaction['obat'][] = [
                'nama_obat' => $row['nama_obat'],
                'jumlah_keluar' => $row['jumlah_keluar']
            ];
        }
    }
}

//PHP Logic tambah transaksi
if (isset($_POST['submit_transaksi'])) {
    $modal_id = '';
    $id_staff = $_SESSION['id_user'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $id_obat = $_POST['id_obat'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $validTransaction = true;
    $outOfStock = false;
    $expiredMedicine = false;

    // Memeriksa status_hapus dan stok untuk setiap obat
    echo count($id_obat);
    for ($i = 0; $i < count($id_obat); $i++) {
        echo $i;
        // Cek status_hapus untuk setiap obat
        $query_check_status = "SELECT status_hapus, stok, tgl_kadaluarsa FROM obat WHERE id_obat = '$id_obat[$i]'";
        $result_check = mysqli_query($conn, $query_check_status);
        $row_check = mysqli_fetch_assoc($result_check);

        if ($row_check['status_hapus'] == 1) {
            $validTransaction = false;  // Jika ada yang status_hapus = true, transaksi tidak valid
            break;
        }

        // Cek jika jumlah keluar lebih besar dari stok yang tersedia
        if ($jumlah_keluar[$i] > $row_check['stok']) {
            $outOfStock = true;
            break;
        }

        //Cek jika ada obat kadaluarsa yang dipilih
        $currentDate = date('Y-m-d');
        if ($row_check['tgl_kadaluarsa'] < $currentDate) {
            $expiredMedicine = true;
            $_SESSION["id_obat_exp"] = $id_obat[$i];
            break;
        }
    }

    if ($validTransaction == true && $outOfStock == false && $expiredMedicine == false) {
        $query_transaksi = "INSERT INTO transaksi (id_staff, keterangan) VALUES ('$id_staff', '$keterangan')";
        if (mysqli_query($conn, $query_transaksi)) {
            $id_transaksi = mysqli_insert_id($conn); //mengambil id_transaksi terakhir

            for ($i = 0; $i < count($id_obat); $i++) {
                $query_stokkeluar = "INSERT INTO stokkeluar (id_transaksi, id_obat, jumlah_keluar, id_staff, jenis_pengeluaran)
                                 VALUES ($id_transaksi, $id_obat[$i], $jumlah_keluar[$i], $id_staff, 'Transaksi')";

                $query_update_stok = "UPDATE obat SET stok = stok - $jumlah_keluar[$i] WHERE id_obat = $id_obat[$i] AND status_hapus = 0";

                mysqli_query($conn, $query_stokkeluar);
                mysqli_query($conn, $query_update_stok);
            }
            $modal_id = "successModal";
        } else {
            $modal_id = "failModal";
        }
    } else if ($outOfStock == true) {
        $modal_id = "outStockModal";
    } else if ($expiredMedicine == true) {
        $modal_id = "expiredMedicineModal";
    } else {
        $modal_id = "failModal";
    }
    if ($modal_id != '') {
        $_SESSION["modal_id"] = $modal_id;
        header("Location: RiwayatTransaksi.php");
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
    <title>Riwayat Transaksi - Klinikku</title>
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
                        <!-- Logo Klinikku Sidebar -->
                        <div class="sb-sidenav-header d-flex align-items-center">
                            <div class="me-auto ms-3 mb-3">
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
                        <h1 class="mb-0" style="font-size: 36px; font-weight: bold;">Riwayat Transaksi</h1>
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
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Daftar Riwayat Transaksi</li>
                    </ol>

                    <!-- Card untuk tabel daftar transaksi -->
                    <div class="card mb-4 bg-white">
                        <div class="card-header d-flex justify-content-start align-items-center ">
                            <div class="w-10 mt-2 ms-5">
                                <i class="bi bi-journal-text" style="font-size: 3rem;"></i>
                            </div>

                            <div class="w-10 mt-2 ms-3">
                                <h3 class="mt-2">Daftar Transaksi</h3>
                            </div>

                            <!-- Button tambah transaksi sebelah kanan -->
                            <div class="d-flex flex-column align-items-end ms-auto" style="width: 30%;">
                                <button class="btn btn-light btn-outline-secondary btn-sm w-50 mt-2 mb-3"
                                    style="height: 36px; font-size: 16px;" data-bs-toggle="modal"
                                    data-bs-target="#addTransactionModal">
                                    + Transaksi Baru
                                </button>
                            </div>
                        </div>

                        <!-- Card body tabel transaksi -->
                        <div class="card-body bg-white">
                            <table id="datatablesSimple4">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Transaksi</th>
                                        <th>Nama Obat</th>
                                        <th>ID Obat</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Transaksi</th>
                                        <th>Nama Obat</th>
                                        <th>ID Obat</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    //query untuk menampilkan data obat
                                    $query = "SELECT transaksi.id_transaksi, GROUP_CONCAT(obat.id_obat SEPARATOR ', ') AS id_obat, GROUP_CONCAT(obat.nama_obat SEPARATOR ', ') AS nama_obat, transaksi.tgl_transaksi
                                                FROM transaksi
                                                JOIN stokkeluar ON transaksi.id_transaksi = stokkeluar.id_transaksi
                                                JOIN obat ON stokkeluar.id_obat = obat.id_obat
                                                WHERE stokkeluar.jenis_pengeluaran = 'Transaksi'
                                                GROUP BY transaksi.id_transaksi";
                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                    <td>" . $no++ . "</td>
                                                    <td>" . $row['id_transaksi'] . "</td>
                                                    <td>" . $row['nama_obat'] . "</td>
                                                    <td>" . $row['id_obat'] . "</td>
                                                    <td>" . $row['tgl_transaksi'] . "</td>
                                                    <td><a href='RiwayatTransaksi.php?id_transaksi=" . $row['id_transaksi'] . "' class='text-decoration-none'>Lihat Detail</a></td>
                                                  </tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal untuk Tambah Transaksi -->
                    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addTransModalLabel">Transaksi Baru</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for Adding Transaction -->
                                    <form action="RiwayatTransaksi.php" method="POST" id="addTransactionForm">
                                        <div id="medicineRows">
                                            <div class="row d-flex justify-content-between" id="medicineRow1">
                                                <div class="col-5">
                                                    <label for="addIdObat" class="form-label">ID Obat</label>
                                                    <input type="text" class="form-control" name="id_obat[]"
                                                        id="addIdObat" required>
                                                </div>
                                                <div class="col-5">
                                                    <label for="addJmlKeluar" class="form-label">Jumlah
                                                        Keluar</label>
                                                    <input type="number" class="form-control" name="jumlah_keluar[]"
                                                        id="addJmlKeluar" required>
                                                </div>
                                                <div class="col-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="addRow()">+</button>
                                                </div>
                                                <div class="col-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger removeRowBtn"
                                                        onclick="removeRow(1)">-</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-10 mt-3">
                                            <label for="addKeterangan" class="form-label">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" id="addKeterangan"
                                                required></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer d-flex justify-content-end">
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-success" name="submit_transaksi"
                                            form="addTransactionForm">Simpan
                                            Transaksi</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        let rowCount = 1; // Initialize row count

                        // Function to add new row for medicine
                        function addRow() {
                            rowCount++;
                            const newRow = document.createElement("div");
                            newRow.classList.add("row");
                            newRow.id = `medicineRow${rowCount}`;
                            newRow.innerHTML = `
                            <div class="col-5">
                                <label for="id_obat_${rowCount}" class="form-label">ID Obat</label>
                                <input type="text" class="form-control" name="id_obat[]" id="id_obat_${rowCount}" required>
                            </div>
                            <div class="col-5">
                                <label for="jumlah_keluar_${rowCount}" class="form-label">Jumlah Keluar</label>
                                <input type="number" class="form-control" name="jumlah_keluar[]" id="jumlah_keluar_${rowCount}" required>
                            </div>
                            <div class="col-1 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" onclick="addRow()">+</button>
                            </div>
                            <div class="col-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger removeRowBtn" onclick="removeRow(${rowCount})">-</button>
                            </div>
                        `;
                            document.getElementById("medicineRows").appendChild(newRow);
                        }

                        // Function to remove a row
                        function removeRow(rowId) {
                            const row = document.getElementById(`medicineRow${rowId}`);
                            row.remove();
                        }
                    </script>

                    <!-- Modal untuk Menampilkan Detail Transaksi -->
                    <?php
                    if ($transaction != null) {
                        ?>
                        <!-- Modal untuk Lihat Detail Transaksi -->
                        <div class="modal fade" id="viewTransactionModal" tabindex="-1"
                            aria-labelledby="viewTransactionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewTransactionModalLabel">Detail Transaksi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Content for Transaction Details (Nota) -->
                                        <?php if ($transaction): ?>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>ID : <span
                                                            id="transactionId"><?= htmlspecialchars($transaction['id_transaksi']) ?></span>
                                                    </h5>
                                                    <p>Date : <span
                                                            id="transactionDate"><?= htmlspecialchars($transaction['tgl_transaksi']) ?></span>
                                                    </p>
                                                </div>
                                                <!-- Garis Pemisah -->
                                                <hr>

                                                <div class="col-12 mt-1">
                                                    <h6>Admin</h6>
                                                    <p id="transactionStaffName"><?= htmlspecialchars($transaction['nama_staff']) ?> <br> <?= htmlspecialchars($transaction['id_staff']) ?>
                                                    </p>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <h6>Keterangan</h6>
                                                    <p id="transactionNote"><?= htmlspecialchars($transaction['keterangan']) ?>
                                                    </p>
                                                </div>
                                                <!-- Garis Pemisah -->
                                                <hr>

                                                <div class="col-12">
                                                    <h6>Detail Obat:</h6>
                                                    <ul id="medicinesList">
                                                        <?php foreach ($transaction['obat'] as $obat): ?>
                                                            <li><?= htmlspecialchars($obat['nama_obat']) ?> -
                                                                <?= htmlspecialchars($obat['jumlah_keluar']) ?> pcs
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-danger">Data transaksi tidak ditemukan atau tidak valid.</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <!-- Optional button for actions (e.g., for printing or updating) -->
                                        <!-- <button type="button" class="btn btn-primary">Cetak Nota</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const modalEl = document.getElementById('viewTransactionModal');
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
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <?php

    $modal_id = "";

    if (isset($_SESSION["modal_id"])) {
        $modal_id = $_SESSION["modal_id"];
        unset($_SESSION["modal_id"]);
    }
    $id_obat_exp = "";
    if (isset($_SESSION["id_obat_exp"])) {
        $id_obat_exp = $_SESSION["id_obat_exp"];
        unset($_SESSION["id_obat_exp"]);
    }
    ?>
    <script>
        var modalId = '<?php echo $modal_id; ?>';

        // Tampilkan SweetAlert2 berdasarkan nilai modalId
        if (modalId) {
            if (modalId === 'successModal') {
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
                    text: 'ID Obat tidak ada',
                    confirmButtonText: 'Tutup'
                });
            } else if (modalId === 'outStockModal') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Stok di gudang kurang',
                    confirmButtonText: 'Tutup'
                });
            } else if (modalId === 'expiredMedicineModal') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Obat dengan ID <?= $id_obat_exp ?> Sudah Kadaluarsa',
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