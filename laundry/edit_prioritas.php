<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Periksa apakah parameter id_prioritas ada
if (!isset($_GET['id'])) {
  header("Location: prioritas.php");
  exit;
}

$idPrioritas = $_GET['id'];

// Mengambil data prioritas berdasarkan id_prioritas
$query = "SELECT * FROM Prioritas WHERE id_prioritas = '$idPrioritas'";
$result = mysqli_query($conn, $query);
$prioritas = mysqli_fetch_assoc($result);

// Memeriksa apakah prioritas ditemukan
if (!$prioritas) {
  header("Location: prioritas.php");
  exit;
}

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  // Mengambil data dari form
  $jenisPrioritas = $_POST['jenis_prioritas'];
  $keterangan = $_POST['keterangan'];
  $imageURL = $prioritas['image_url']; // Menggunakan URL gambar yang sudah ada sebagai default
  $durasi = intval($_POST['durasi']); // Mengonversi durasi menjadi integer

  // Validasi data
  if (empty($jenisPrioritas)) {
    $error = "Silakan lengkapi jenis prioritas.";
  } else {
    // Mengunggah gambar baru jika ada yang diunggah
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

    // Mengupdate data prioritas di tabel Prioritas
    $updateQuery = "UPDATE Prioritas SET jenis_prioritas = '$jenisPrioritas', keterangan = '$keterangan', image_url = '$imageURL', durasi = $durasi WHERE id_prioritas = '$idPrioritas'";
    mysqli_query($conn, $updateQuery);

    // Mengalihkan kembali ke halaman prioritas.php setelah prioritas diperbarui
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
          <h2>Edit Prioritas</h2>
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
              <input type="text" class="form-control" id="jenis_prioritas" name="jenis_prioritas" value="<?php echo $prioritas['jenis_prioritas']; ?>">
            </div>
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo $prioritas['keterangan']; ?></textarea>
            </div>
            <div class="form-group">
              <label for="durasi">Durasi</label>
              <input type="number" class="form-control" id="durasi" name="durasi" value="<?php echo $prioritas['durasi']; ?>" min="1">
            </div>
            <div class="form-group">
              <label for="image">Gambar</label>
              <input type="file" class="form-control-file" id="image" name="image">
              <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
            </div>
            <?php if ($error) : ?>
              <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
