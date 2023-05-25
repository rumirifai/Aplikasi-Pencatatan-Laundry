<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mendapatkan nama pelanggan dari session atau sumber lainnya
$nama = $_SESSION['username'];

// Mengambil data Permintaan Cucian Terkini
$queryPermintaanCucian = "SELECT * FROM PermintaanCucian ORDER BY tgl_masuk DESC LIMIT 5";
$resultPermintaanCucian = mysqli_query($conn, $queryPermintaanCucian);

// Mengambil data Transaksi Terkini
$queryTransaksi = "SELECT * FROM Transaksi ORDER BY no_transaksi DESC LIMIT 5";
$resultTransaksi = mysqli_query($conn, $queryTransaksi);

mysqli_close($conn);
?>

<?php include 'head.php'; ?>
<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px; /* Adjust the value as needed */
  }

  .card {
    background-color: #f6f6f6;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 4px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
  }

  .card h5 {
    margin: 0;
    color: #333333;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <h4>Selamat datang, <?php echo $nama; ?></h4>

      <div class="card">
        <h5>Permintaan Cucian Terkini</h5>
        <table>
          <thead>
            <tr>
              <th>No. Cucian</th>
              <th>Tanggal Masuk</th>
              <th>Status Cucian</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultPermintaanCucian)) {
              echo "<tr>";
              echo "<td>" . $row['no_cucian'] . "</td>";
              echo "<td>" . $row['tgl_masuk'] . "</td>";
              echo "<td>" . $row['status_cucian'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h5>Transaksi Terkini</h5>
        <table>
          <thead>
            <tr>
              <th>No. Transaksi</th>
              <th>Status Transaksi</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultTransaksi)) {
              echo "<tr>";
              echo "<td>" . $row['no_transaksi'] . "</td>";
              echo "<td>" . $row['status_transaksi'] . "</td>";
              echo "<td>" . $row['subtotal'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
