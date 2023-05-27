<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  // Mengambil data dari form
  $jenisPrioritas = $_POST['jenis_prioritas'];
  $keterangan = $_POST['keterangan'];
  $imageURL = '';
  $durasi = $_POST['durasi'];

  // Validasi data
  if (empty($jenisPrioritas) || empty($keterangan) || empty($durasi)) {
    $error = "Silakan lengkapi semua field.";
  } else {
    // Mengunggah gambar
    if ($_FILES['image']['name']) {
      $targetDir = "uploads/";
      $fileName = basename($_FILES['image']['name']);
      $targetFilePath = $targetDir . $fileName;
      $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

      // Mengizinkan hanya beberapa tipe file tertentu
      $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
      if (in_array($fileType, $allowedTypes)) {
        // Memindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
          $imageURL = $targetFilePath;
        } else {
          $error = "Terjadi kesalahan saat mengunggah gambar.";
        }
      } else {
        $error = "Tipe file yang diunggah tidak didukung.";
      }
    }

    // Menyimpan data ke tabel Prioritas
    $insertQuery = "INSERT INTO Prioritas (jenis_prioritas, keterangan, image_url, durasi) VALUES ('$jenisPrioritas', '$keterangan', '$imageURL', '$durasi')";
    mysqli_query($conn, $insertQuery);

    // Mengalihkan ke halaman daftar_prioritas.php setelah prioritas ditambahkan
    header("Location: prioritas.php");
    exit;
  }
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px;
    /* Adjust the value as needed */
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Tambah Prioritas</h2>
          <div class="text-right">
            <a href="prioritas.php" class="btn btn-primary">Kembali ke Daftar Prioritas</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="jenis_prioritas">Jenis Prioritas</label>
              <input type="text" class="form-control" id="jenis_prioritas" name="jenis_prioritas">
            </div>
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label for="durasi">Durasi (dalam hari)</label>
              <input type="number" class="form-control" id="durasi" name="durasi" min="1">
            </div>
            <div class="form-group">
              <label for="image">Gambar</label>
              <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <div class="text-danger mb-3"><?php echo $error; ?></div>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
