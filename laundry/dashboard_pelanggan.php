<?php
session_start();

include "config.php";

$id_pelanggan = isset($_SESSION['id_pelanggan']) ? $_SESSION['id_pelanggan'] : 0;

$queryPesanan = "SELECT pc.*, pri.jenis_prioritas, sc.jenis_status_cucian, SUM(ic.jumlah_item) AS total_item
                FROM PermintaanCucian pc
                LEFT JOIN Prioritas pri ON pc.id_prioritas = pri.id_prioritas
                LEFT JOIN StatusCucian sc ON pc.id_status_cucian = sc.id_status_cucian
                LEFT JOIN ItemCucian ic ON pc.no_cucian = ic.no_cucian
                WHERE pc.id_pelanggan = $id_pelanggan
                GROUP BY pc.no_cucian, pri.jenis_prioritas, sc.jenis_status_cucian
                ORDER BY pc.tgl_masuk DESC
                LIMIT 1";
$resultPesanan = mysqli_query($conn, $queryPesanan);
$rowPesanan = mysqli_fetch_assoc($resultPesanan);

$queryTransaksi = "SELECT t.*, sc.jenis_status_transaksi
                  FROM Transaksi t
                  LEFT JOIN StatusTransaksi sc ON t.id_status_transaksi = sc.id_status_transaksi
                  WHERE t.id_pelanggan = $id_pelanggan
                  ORDER BY t.tgl_transaksi DESC
                  LIMIT 1";
$resultTransaksi = mysqli_query($conn, $queryTransaksi);
$rowTransaksi = mysqli_fetch_assoc($resultTransaksi);

?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Dashboard Pelanggan</h2>
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h5>Pesanan Terkini</h5>
                </div>
                <div class="card-body">
                  <?php if ($rowPesanan) : ?>
                    <h6>No. Cucian: <?php echo $rowPesanan['no_cucian']; ?></h6>
                    <p>Tanggal Masuk: <?php echo $rowPesanan['tgl_masuk']; ?></p>
                    <p>Tanggal Selesai: <?php echo $rowPesanan['tgl_selesai']; ?></p>
                    <p>Status Cucian: <?php echo $rowPesanan['jenis_status_cucian']; ?></p>
                    <p>Total Item: <?php echo $rowPesanan['total_item']; ?></p>
                    <a href="permintaan_cucian_pelanggan.php" class="btn btn-primary">Lihat</a>
                  <?php else : ?>
                    <p>Tidak ada pesanan terkini.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h5>Transaksi Terkini</h5>
                </div>
                <div class="card-body">
                  <?php if ($rowTransaksi) : ?>
                    <h6>No. Transaksi: <?php echo $rowTransaksi['no_transaksi']; ?></h6>
                    <p>Tanggal Transaksi: <?php echo $rowTransaksi['tgl_transaksi']; ?></p>
                    <p>Status Transaksi: <?php echo $rowTransaksi['jenis_status_transaksi']; ?></p>
                    <p>Subtotal: <?php echo $rowTransaksi['subtotal']; ?></p>
                    <a href="daftar_transaksi_pelanggan.php" class="btn btn-primary">Lihat</a>
                  <?php else : ?>
                    <p>Tidak ada transaksi terkini.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
