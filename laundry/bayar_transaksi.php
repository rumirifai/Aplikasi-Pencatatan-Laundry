<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

// Pastikan parameter no_transaksi telah diberikan
if (!isset($_GET['no_transaksi'])) {
    header("Location: daftar_transaksi_pelanggan.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$no_transaksi = $_GET['no_transaksi'];

// Periksa apakah transaksi milik pelanggan yang sedang login
$query = "SELECT t.*, c.jenis_prioritas, s.jenis_status_transaksi
          FROM Transaksi t
          INNER JOIN PermintaanCucian pc ON t.no_cucian = pc.no_cucian
          INNER JOIN Prioritas c ON pc.id_prioritas = c.id_prioritas
          INNER JOIN StatusTransaksi s ON t.id_status_transaksi = s.id_status_transaksi
          WHERE t.id_pelanggan = '$id_pelanggan' AND t.no_transaksi = '$no_transaksi'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // Jika transaksi tidak ditemukan, kembalikan ke halaman daftar transaksi
    header("Location: daftar_transaksi_pelanggan.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Periksa apakah transaksi sudah dibayar
if ($row['jenis_status_transaksi'] == 'Sudah Dibayar') {
    // Jika sudah dibayar, kembalikan ke halaman daftar transaksi
    header("Location: daftar_transaksi_pelanggan.php");
    exit();
}

// Proses pembayaran transaksi
// ... (Lakukan proses pembayaran sesuai dengan kebutuhan Anda)

// Ubah status transaksi menjadi "Sudah Dibayar"
$query_update = "UPDATE Transaksi SET id_status_transaksi = (SELECT id_status_transaksi FROM StatusTransaksi WHERE jenis_status_transaksi = 'Sudah Dibayar') WHERE no_transaksi = '$no_transaksi'";
mysqli_query($conn, $query_update);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Pembayaran Transaksi</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Transaksi #<?php echo $row['no_transaksi']; ?></h5>
                            <p class="card-text">
                                Tanggal Transaksi: <?php echo $row['tgl_transaksi']; ?>
                            </p>
                            <p class="card-text">
                                Jenis Prioritas: <?php echo $row['jenis_prioritas']; ?>
                            </p>
                            <p class="card-text">
                                Status Transaksi: Sudah Dibayar
                            </p>
                            <p class="card-text">
                                Subtotal: <?php echo $row['subtotal']; ?>
                            </p>
                            <p class="card-text">Terima kasih telah melakukan pembayaran. Transaksi Anda telah selesai.</p>
                            <a href="daftar_transaksi_pelanggan.php" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div
