<?php
require('fpdf/fpdf.php');
include('config.php');

// Mendapatkan nomor transaksi dari parameter URL
$noTransaksi = $_GET['no_transaksi'];

// Query untuk mendapatkan informasi transaksi, pelanggan, dan item cucian
$query = "SELECT t.no_transaksi, t.tgl_transaksi, p.nama AS nama_pelanggan, pc.tgl_masuk, pc.tgl_selesai, pc.total_item, st.jenis_status_transaksi, t.subtotal, ic.jumlah_item, i.jenis_item, i.harga_per_item
          FROM Transaksi t
          INNER JOIN Pelanggan p ON t.id_pelanggan = p.id_pelanggan
          INNER JOIN PermintaanCucian pc ON t.no_cucian = pc.no_cucian
          INNER JOIN StatusTransaksi st ON t.id_status_transaksi = st.id_status_transaksi
          INNER JOIN ItemCucian ic ON pc.no_cucian = ic.no_cucian
          INNER JOIN Item i ON ic.id_item = i.id_item
          WHERE t.no_transaksi = $noTransaksi";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  // Membuat objek PDF dengan ukuran halaman A4
  $pdf = new FPDF();
  $pdf->AddPage('P', 'A4');

  // Judul halaman
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(0, 10, 'Receipt Transaksi', 0, 1, 'C');
  $pdf->Ln(10);

  // Informasi transaksi
  $row = mysqli_fetch_assoc($result);
  $pdf->SetFont('Arial', '', 12);
  $pdf->Cell(40, 10, 'No. Transaksi:', 0, 0);
  $pdf->Cell(0, 10, $row['no_transaksi'], 0, 1);
  $pdf->Cell(40, 10, 'Tanggal Transaksi:', 0, 0);
  $pdf->Cell(0, 10, $row['tgl_transaksi'], 0, 1);
  $pdf->Cell(40, 10, 'Nama Pelanggan:', 0, 0);
  $pdf->Cell(0, 10, $row['nama_pelanggan'], 0, 1);
  $pdf->Ln(10);

  // Informasi item cucian
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(40, 10, 'Item', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Jumlah', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Harga per Item', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Total Harga', 1, 0, 'C');
  $pdf->Ln();

  // Reset subtotal
  $subtotal = 0;

  // Retrieve item list and calculate subtotal
  mysqli_data_seek($result, 0); // Reset result pointer to the beginning
  while ($rowItem = mysqli_fetch_assoc($result)) {
    $hargaItem = $rowItem['jumlah_item'] * $rowItem['harga_per_item'];
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, $rowItem['jenis_item'], 1, 0, 'C');
    $pdf->Cell(40, 10, $rowItem['jumlah_item'], 1, 0, 'C');
    $pdf->Cell(40, 10, 'Rp. ' . $rowItem['harga_per_item'], 1, 0, 'R');
    $pdf->Cell(40, 10, 'Rp. ' . $hargaItem, 1, 0, 'R');
    $pdf->Ln();
    $subtotal += $hargaItem;
  }
  $pdf->Ln(10);

  // Informasi subtotal
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(0, 10, 'Subtotal: Rp. ' . $row['subtotal'], 0, 1);

  // Menutup objek PDF
  $pdf->Output();
} else {
  // Transaksi tidak ditemukan
  echo 'Transaksi tidak ditemukan.';
}

mysqli_close($conn);
?>
