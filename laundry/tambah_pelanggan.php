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

// Pesan kesalahan
$error = '';

// Memeriksa apakah formulir telah dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Memeriksa dan memvalidasi input
  $nama_pelanggan = $_POST['nama_pelanggan'];
  $alamat_pelanggan = $_POST['alamat_pelanggan'];
  $telepon_pelanggan = $_POST['telepon_pelanggan'];

  if (empty($nama_pelanggan) || empty($alamat_pelanggan) || empty($telepon_pelanggan)) {
    $error = 'Silakan lengkapi semua kolom';
  } elseif (!ctype_digit($telepon_pelanggan)) {
    $error = 'Nomor telepon hanya boleh terdiri dari angka';
  } else {
    // Menyimpan data pelanggan ke database
    $query = "INSERT INTO Pelanggan (nama, alamat, telepon) VALUES ('$nama_pelanggan', '$alamat_pelanggan', '$telepon_pelanggan')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Pelanggan berhasil ditambahkan, alihkan ke halaman daftar pelanggan
      header("Location: pelanggan.php");
      exit;
    } else {
      $error = 'Terjadi kesalahan saat menambahkan pelanggan';
    }
  }
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px; /* Adjust the value as needed */
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Tambah Pelanggan</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 offset-lg-3">
          <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>
          <form method="POST">
            <div class="form-group">
              <label for="nama_pelanggan">Nama Pelanggan</label>
              <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="alamat_pelanggan">Alamat Pelanggan</label>
              <input type="text" name="alamat_pelanggan" id="alamat_pelanggan" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="telepon_pelanggan">Telepon Pelanggan</label>
              <input type="tel" name="telepon_pelanggan" id="telepon_pelanggan" class="form-control" pattern="[0-9]+" required>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
