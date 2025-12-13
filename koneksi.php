<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sistem_penilaian_pekerja';

$koneksi = mysqli_connect("localhost", "root", "ocean", "sistem_penilaian_pekerja");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");
?>