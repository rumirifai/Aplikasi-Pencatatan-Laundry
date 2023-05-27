<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  // Mengambil data dari form
  $jenisItem = $_POST['jenis_item'];
  $hargaPerItem = $_POST['harga_per_item'];
  $idPrioritas = $_POST['id_prioritas'];

  // Validasi data
  if (empty($jenisItem) || empty($hargaPerItem) || empty($idPrioritas)) {
    $error = "Silakan lengkapi semua field.";
  } else {
    // Menyimpan data ke tabel Item
    $insertQuery = "INSERT INTO Item (jenis_item, harga_per_item, id_prioritas) VALUES ('$jenisItem', '$hargaPerItem', '$idPrioritas')";
    mysqli_query($conn, $insertQuery);

    // Mengalihkan ke halaman daftar_item.php setelah item ditambahkan
    header("Location: daftar_item.php");
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
          <h2>Tambah Item</h2>
          <div class="text-right">
            <a href="daftar_item.php" class="btn btn-primary">Kembali ke Daftar Item</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="POST">
            <div class="form-group">
              <label for="jenis_item">Jenis Item</label>
              <input type="text" class="form-control" id="jenis_item" name="jenis_item">
            </div>
            <div class="form-group">
              <label for="harga_per_item">Harga per Item</label>
              <input type="number" step="0.01" class="form-control" id="harga_per_item" name="harga_per_item" min="0.1">
            </div>
            <div class="form-group">
              <label for="id_prioritas">Prioritas</label>
              <select class="form-control" id="id_prioritas" name="id_prioritas">
                <?php while ($row = mysqli_fetch_assoc($prioritasResult)) : ?>
                  <option value="<?php echo $row['id_prioritas']; ?>"><?php echo $row['jenis_prioritas']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="text-danger mb-3"><?php echo $error; ?></div>
            <button type="submit" name="simpan" class="btnbtn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
