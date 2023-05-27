<?php
include "config.php";

// Memeriksa apakah parameter id dan no_cucian ada
if (!isset($_GET['id']) || !isset($_GET['no_cucian'])) {
    header("Location: permintaan_cucian.php");
    exit;
}

$idItemCucian = $_GET['id'];
$noCucian = $_GET['no_cucian'];

// Memeriksa apakah permintaan cucian terhubung dengan transaksi
$queryTransaksi = "SELECT * FROM Transaksi WHERE no_cucian = '$noCucian'";
$resultTransaksi = mysqli_query($conn, $queryTransaksi);

if (mysqli_num_rows($resultTransaksi) > 0) {
    // Jika terhubung dengan transaksi, alihkan kembali dengan pesan error
    header("Location: edit_permintaan_cucian.php?no_cucian=$noCucian&error=Permintaan cucian tidak dapat dihapus karena sudah terhubung dengan transaksi.");
    exit;
}

// Jika tidak terhubung dengan transaksi, lanjutkan proses penghapusan
$deleteQuery = "DELETE FROM ItemCucian WHERE no_itemCucian = '$idItemCucian'";
mysqli_query($conn, $deleteQuery);

// Alihkan kembali ke halaman edit_permintaan_cucian.php setelah item cucian dihapus
header("Location: edit_permintaan_cucian.php?no_cucian=$noCucian");
exit;
