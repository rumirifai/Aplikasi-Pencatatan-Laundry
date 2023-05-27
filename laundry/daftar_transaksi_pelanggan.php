<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$query = "SELECT t.*, c.jenis_prioritas, s.jenis_status_transaksi
          FROM Transaksi t
          INNER JOIN PermintaanCucian pc ON t.no_cucian = pc.no_cucian
          INNER JOIN Prioritas c ON pc.id_prioritas = c.id_prioritas
          INNER JOIN StatusTransaksi s ON t.id_status_transaksi = s.id_status_transaksi
          WHERE t.id_pelanggan = '$id_pelanggan'
          ORDER BY t.tgl_transaksi DESC";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Daftar Transaksi</h2>
                </div>
            </div>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-lg-4">
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
                                Status Transaksi: <?php echo $row['jenis_status_transaksi']; ?>
                            </p>
                            <p class="card-text">
                                Subtotal: <?php echo $row['subtotal']; ?>
                            </p>
                            <div class="button-group">
                                <?php if ($row['jenis_status_transaksi'] == 'Belum Bayar'): ?>
                                <a href="bayar_transaksi.php?no_transaksi=<?php echo $row['no_transaksi']; ?>"
                                    class="btn btn-success">Bayar</a>
                                <?php elseif ($row['jenis_status_transaksi'] == 'Sudah Dibayar'): ?>
                                <a href="download_receipt.php?no_transaksi=<?php echo $row['no_transaksi']; ?>"
                                    class="btn btn-primary">Download Receipt</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
