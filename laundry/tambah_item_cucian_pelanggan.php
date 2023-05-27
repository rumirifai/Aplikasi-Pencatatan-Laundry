<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['id_pelanggan'])) {
  header("Location: login.php");
  exit;
}

// Memeriksa apakah parameter no_cucian ada
if (!isset($_GET['no_cucian'])) {
  header("Location: edit_permintaan_cucian_pelanggan.php");
  exit;
}

$noCucian = $_GET['no_cucian'];

// Mengambil prioritas cucian berdasarkan no_cucian
$permintaanQuery = "SELECT id_prioritas FROM PermintaanCucian WHERE no_cucian = '$noCucian'";
$permintaanResult = mysqli_query($conn, $permintaanQuery);
$permintaanData = mysqli_fetch_assoc($permintaanResult);
$idPrioritas = $permintaanData['id_prioritas'];

// Mengambil daftar item dengan prioritas yang sama
$itemQuery = "SELECT * FROM Item WHERE id_prioritas = '$idPrioritas'";
$itemResult = mysqli_query($conn, $itemQuery);

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Tambah" ditekan
if (isset($_POST['tambah'])) {
  // Mengambil data dari form
  $idItem = $_POST['id_item'];
  $ukuran = $_POST['ukuran'];
  $warna = $_POST['warna'];
  $jumlahItem = $_POST['jumlah_item'];

  // Validasi data
  if (empty($idItem) || empty($ukuran) || empty($warna) || empty($jumlahItem)) {
    $error = "Silakan lengkapi semua field.";
  } else {
    // Mengambil harga per item dari tabel Item berdasarkan id_item
    $itemQuery = "SELECT harga_per_item FROM Item WHERE id_item = '$idItem'";
    $itemResult = mysqli_query($conn, $itemQuery);
    $itemData = mysqli_fetch_assoc($itemResult);
    $hargaItemCucian = $itemData['harga_per_item'] * $jumlahItem;

    // Memasukkan data item cucian ke dalam tabel ItemCucian
    $insertQuery = "INSERT INTO ItemCucian (no_cucian, id_item, ukuran, warna, jumlah_item, harga_itemCucian, id_prioritas) VALUES ('$noCucian', '$idItem', '$ukuran', '$warna', '$jumlahItem', '$hargaItemCucian', '$idPrioritas')";
    mysqli_query($conn, $insertQuery);

    // Mengalihkan kembali ke halaman edit_permintaan_cucian_pelanggan.php setelah item cucian ditambahkan
    header("Location: edit_permintaan_cucian_pelanggan.php?no_cucian=$noCucian");
    exit;
  }
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

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
          <h2>Tambah Item Cucian</h2>
          <div class="text-right">
            <a href="edit_permintaan_cucian_pelanggan.php?no_cucian=<?php echo $noCucian; ?>" class="btn btn-primary">Kembali ke Daftar Item Cucian</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="POST">
            <div class="form-group">
              <label for="id_item">Jenis Pakaian</label>
              <select class="form-control" id="id_item" name="id_item">
                <?php while ($row = mysqli_fetch_assoc($itemResult)) : ?>
                  <option value="<?php echo $row['id_item']; ?>"><?php echo $row['jenis_item']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="ukuran">Ukuran</label>
              <input type="text" class="form-control" id="ukuran" name="ukuran">
            </div>
            <div class="form-group">
              <label for="warna">Warna</label>
              <select class="form-control" id="warna" name="warna">
                <option value="hitam">Hitam</option>
                <option value="putih">Putih</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>
            <div class="form-group">
              <label for="jumlah_item">Jumlah Item</label>
              <input type="number" class="form-control" id="jumlah_item" name="jumlah_item" min="1">
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
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
