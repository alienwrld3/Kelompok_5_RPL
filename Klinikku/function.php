<?php

//buat koneksi DB
$conn = mysqli_connect("localhost", "root", "", "klinikku");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>