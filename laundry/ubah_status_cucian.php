<?php
include "config.php";

// Memeriksa apakah permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mengambil data yang dikirim melalui AJAX
  $noCucian = $_POST['no_cucian'];
  $jenisStatusCucian = $_POST['jenis_status_cucian'];

  // Perbarui data status cucian berdasarkan nomor cucian
  $updateQuery = "UPDATE PermintaanCucian SET jenis_status_cucian = '$jenisStatusCucian' WHERE no_cucian = '$noCucian'";
  $updateResult = mysqli_query($conn, $updateQuery);

  // Periksa hasil pembaruan
  if ($updateResult) {
    // Berhasil memperbarui data
    echo "success";
  } else {
    // Gagal memperbarui data
    echo "error";
  }
}

mysqli_close($conn);
?>
