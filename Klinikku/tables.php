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
        <title>Tables - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body class="sb-nav-fixed">
        <div id="layoutSidenav">
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="card mb-4">
                            <div class="card-body">
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
                                        // //query untuk menampilkan data obat
                                        // $query = "SELECT obat.id_obat, obat.nama_obat, obat.stok, grup.nama_grup 
                                        //           FROM obat 
                                        //           LEFT JOIN obat_grup ON obat.id_obat = obat_grup.id_obat 
                                        //           LEFT JOIN grup ON obat_grup.id_grup = grup.id_grup;";
                                        // $result = mysqli_query($conn, $query);

                                        // if (mysqli_num_rows($result) > 0) {
                                        //     $no = 1;
                                        //     while ($row = mysqli_fetch_assoc($result)) {
                                        //         echo "<tr>
                                        //             <td>" . $no++ . "</td>
                                        //             <td>" . $row['nama_obat'] . "</td>
                                        //             <td>" . $row['id_obat'] . "</td>
                                        //             <td>" . $row['nama_grup'] . "</td>
                                        //             <td>" . $row['stok'] . "</td>
                                        //             <td><a href='#' class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#viewMedicineModal' onclick='showMedicineDetail(\"" . $row['id_obat'] . "\")'>Lihat Detail</a></td>
                                        //         </tr>";
                                        //     }
                                        // } else {
                                        //     echo "<tr><td colspan='6'>Data tidak ditemukan</td></tr>";
                                        // }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
