<?php
include "config.php";

// Memeriksa apakah pengguna telah login
session_start();

// Periksa apakah pengguna telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Memeriksa apakah parameter id dan no_cucian ada
if (!isset($_GET['id']) || !isset($_GET['no_cucian'])) {
  header("Location: edit_permintaan_cucian.php");
  exit;
}

$idItemCucian = $_GET['id'];
$noCucian = $_GET['no_cucian'];

// Mengambil data item cucian berdasarkan id_itemCucian
$query = "SELECT * FROM ItemCucian WHERE no_itemCucian = '$idItemCucian'";
$result = mysqli_query($conn, $query);
$itemCucian = mysqli_fetch_assoc($result);

// Memeriksa apakah item cucian ditemukan
if (!$itemCucian) {
  header("Location: edit_permintaan_cucian.php");
  exit;
}

// Memeriksa apakah item cucian telah dimasukkan ke dalam transaksi
$queryCheckTransaksi = "SELECT COUNT(*) AS jumlah_transaksi FROM Transaksi WHERE no_cucian = $noCucian";
$resultCheckTransaksi = mysqli_query($conn, $queryCheckTransaksi);
$rowCheckTransaksi = mysqli_fetch_assoc($resultCheckTransaksi);
$jumlahTransaksi = $rowCheckTransaksi['jumlah_transaksi'];

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
  // Mengambil data dari form
  $ukuran = $_POST['ukuran'];
  $warna = $_POST['warna'];
  $jumlahItem = $_POST['jumlah_item'];

  // Validasi data
  if (empty($ukuran) || empty($warna) || empty($jumlahItem)) {
    $error = "Silakan lengkapi semua field.";
  } else {
    // Mengupdate data item cucian di tabel ItemCucian
    $updateQuery = "UPDATE ItemCucian SET ukuran = '$ukuran', warna = '$warna', jumlah_item = '$jumlahItem' WHERE no_itemCucian = '$idItemCucian'";
    mysqli_query($conn, $updateQuery);

    // Mengalihkan kembali ke halaman edit_permintaan_cucian.php setelah item cucian diperbarui
    header("Location: edit_permintaan_cucian.php?no_cucian=$noCucian");
    exit;
  }
}

// Memproses saat tombol "Hapus" ditekan
if (isset($_POST['hapus'])) {
  // Memeriksa apakah item cucian sudah masuk ke dalam transaksi
  if ($jumlahTransaksi > 0) {
    $error = "Item cucian tidak dapat dihapus karena sudah masuk ke dalam transaksi.";
  } else {
    // Menghapus item cucian dari tabel ItemCucian
    $deleteQuery = "DELETE FROM ItemCucian WHERE no_itemCucian = '$idItemCucian'";
    mysqli_query($conn, $deleteQuery);

    // Mengalihkan kembali ke halaman edit_permintaan_cucian.php setelah item cucian dihapus
    header("Location: edit_permintaan_cucian.php?no_cucian=$noCucian");
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
          <h2>Edit Item Cucian</h2>
          <div class="text-right">
            <a href="_item_cucian.php?no_cucian=<?php echo $noCucian; ?>" class="btn btn-primary">Kembali ke Daftar Item Cucian</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="POST">
            <div class="form-group">
              <label for="ukuran">Ukuran</label>
              <input type="text" class="form-control" id="ukuran" name="ukuran" value="<?php echo $itemCucian['ukuran']; ?>">
            </div>
            <div class="form-group">
              <label for="warna">Warna</label>
              <select class="form-control" id="warna" name="warna">
                <option value="hitam" <?php if ($itemCucian['warna'] == 'hitam') echo 'selected'; ?>>Hitam</option>
                <option value="putih" <?php if ($itemCucian['warna'] == 'putih') echo 'selected'; ?>>Putih</option>
                <option value="lainnya" <?php if ($itemCucian['warna'] == 'lainnya') echo 'selected'; ?>>Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label for="jumlah_item">Jumlah Item</label>
              <input type="number" class="form-control" id="jumlah_item" name="jumlah_item" value="<?php echo $itemCucian['jumlah_item']; ?>">
            </div>
            <?php if ($jumlahTransaksi == 0) : ?>
              <div class="text-center">
                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
              </div>
            <?php else: ?>
              <div class="alert alert-info mt-3">Item Cucian telah masuk ke dalam transaksi dan tidak dapat diubah atau dihapus.</div>
            <?php endif; ?>
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
