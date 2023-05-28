<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$query = "SELECT pc.*, p.jenis_prioritas, s.jenis_status_cucian, t.no_transaksi
          FROM PermintaanCucian pc
          INNER JOIN Prioritas p ON pc.id_prioritas = p.id_prioritas
          INNER JOIN StatusCucian s ON pc.id_status_cucian = s.id_status_cucian
          LEFT JOIN Transaksi t ON pc.no_cucian = t.no_cucian
          WHERE pc.id_pelanggan = '$id_pelanggan'
          ORDER BY pc.tgl_masuk DESC";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<link rel="stylesheet" href="assets/vendor/DataTables/dataTables.css">
<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Permintaan Cucian</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a href="tambah_permintaan_cucian_pelanggan.php" class="btn btn-primary mb-3">Tambah Permintaan
                        Cucian</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table id="permintaan-table" class="display">
                        <thead>
                            <tr>
                                <th>Nomor Cucian</th>
                                <th>Jenis Prioritas</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>Status Cucian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        <?php echo $row['no_cucian']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['jenis_prioritas']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['tgl_masuk']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['tgl_selesai']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['jenis_status_cucian']; ?>
                                    </td>
                                    <td>
                                        <a href="detail_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                            class="btn btn-primary">Detail</a>
                                        <?php if ($row['jenis_status_cucian'] != 'Selesai' && $row['jenis_status_cucian'] != 'Sedang diproses' && !$row['no_transaksi']): ?>
                                            <div>
                                                <a href="edit_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                                    class="btn btn-secondary">Edit</a>
                                                <a href="hapus_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                                    class="btn btn-danger">Hapus</a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($row['total_item'] > 0): ?>
                                            <?php if ($row['no_transaksi']): ?>
                                                <span class="btn btn-success disabled">Sudah Masuk ke Transaksi</span>
                                            <?php else: ?>
                                                <a href="buat_transaksi_pelanggan.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                                                    class="btn btn-success">Lanjutkan ke Transaksi</a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="btn btn-success disabled">Total Item Kosong</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#permintaan-table').DataTable();
  });
</script>