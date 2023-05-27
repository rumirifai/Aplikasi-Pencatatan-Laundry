<?php include 'header.php'; ?>

<style>
    /* Additional styles to adjust the content position */
    #main {
        padding-top: 70px;
        /* Adjust the value as needed */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
include "config.php";

// Memeriksa apakah pengguna telah login
session_start();

// Periksa apakah pengguna telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Memeriksa apakah parameter no_cucian ada
if (!isset($_GET['no_cucian'])) {
    header("Location: permintaan_cucian.php");
    exit;
}

$noCucian = $_GET['no_cucian'];

// Mengambil data permintaan cucian berdasarkan no_cucian
$query = "SELECT * FROM PermintaanCucian WHERE no_cucian = '$noCucian'";
$result = mysqli_query($conn, $query);
$permintaan = mysqli_fetch_assoc($result);

// Memeriksa apakah permintaan cucian ditemukan
if (!$permintaan) {
    header("Location: permintaan_cucian.php");
    exit;
}

// Mengambil daftar pelanggan dari tabel Pelanggan
$pelangganQuery = "SELECT * FROM Pelanggan";
$pelangganResult = mysqli_query($conn, $pelangganQuery);

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Mengambil daftar status cucian dari tabel StatusCucian
$statusQuery = "SELECT * FROM StatusCucian";
$statusResult = mysqli_query($conn, $statusQuery);

$queryTotalItem = "SELECT total_item FROM PermintaanCucian WHERE no_cucian = '$noCucian'";
$resultTotalItem = mysqli_query($conn, $queryTotalItem);
$totalItem = mysqli_fetch_assoc($resultTotalItem)['total_item'];

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
    // Mengambil data dari form
    $idPelanggan = $_POST['id_pelanggan'];
    $idPrioritas = $_POST['id_prioritas'];
    $tglMasuk = $_POST['tgl_masuk'];
    $tglSelesai = $_POST['tgl_selesai'];
    $idStatusCucian = $_POST['id_status_cucian'];

    // Validasi data
    if (empty($idPelanggan) || empty($idPrioritas) || empty($tglMasuk) || empty($tglSelesai) || empty($idStatusCucian)) {
        $error = "Silakan lengkapi semua field.";
    } else {
        // Mengupdate data permintaan cucian di tabel PermintaanCucian
        $updateQuery = "UPDATE PermintaanCucian SET id_pelanggan = '$idPelanggan', id_prioritas = '$idPrioritas', tgl_masuk = '$tglMasuk', tgl_selesai = '$tglSelesai', total_item = '$totalItem', id_status_cucian = '$idStatusCucian' WHERE no_cucian = '$noCucian'";
        mysqli_query($conn, $updateQuery);

        // Mengalihkan kembali ke halaman permintaan_cucian.php setelah permintaan cucian diperbarui
        header("Location: permintaan_cucian.php");
        exit;
    }
}
$queryItem = "SELECT ic.*, i.jenis_item, i.harga_per_item
              FROM ItemCucian ic
              INNER JOIN Item i ON ic.id_item = i.id_item
              WHERE ic.no_cucian = $noCucian";
$resultItem = mysqli_query($conn, $queryItem);

mysqli_close($conn);
?>
<?php include 'header.php'; ?>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Edit Permintaan Cucian</h2>
                    <div class="text-right">
                        <a href="permintaan_cucian.php" class="btn btn-primary">Kembali ke Daftar Permintaan Cucian</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Form Edit Permintaan Cucian -->
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="id_pelanggan">Pelanggan</label>
                            <select class="form-control" id="id_pelanggan" name="id_pelanggan">
                                <?php while ($row = mysqli_fetch_assoc($pelangganResult)): ?>
                                    <option value="<?php echo $row['id_pelanggan']; ?>" <?php if ($row['id_pelanggan'] == $permintaan['id_pelanggan'])
                                           echo 'selected'; ?>><?php echo $row['nama']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_prioritas">Prioritas</label>
                            <select class="form-control" id="id_prioritas" name="id_prioritas">
                                <?php while ($row = mysqli_fetch_assoc($prioritasResult)): ?>
                                    <option value="<?php echo $row['id_prioritas']; ?>" <?php if ($row['id_prioritas'] == $permintaan['id_prioritas'])
                                           echo 'selected'; ?>><?php echo $row['jenis_prioritas']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tgl_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk"
                                value="<?php echo $permintaan['tgl_masuk']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="tgl_selesai">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                value="<?php echo $permintaan['tgl_selesai']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="total_item">Total Item</label>
                            <input type="number" class="form-control" id="total_item"
                                value="<?php echo $permintaan['total_item']; ?>" disabled>
                        </div>


                        <div class="form-group">
                            <label for="id_status_cucian">Status Cucian</label>
                            <select class="form-control" id="id_status_cucian" name="id_status_cucian">
                                <?php while ($row = mysqli_fetch_assoc($statusResult)): ?>
                                    <option value="<?php echo $row['id_status_cucian']; ?>" <?php if ($row['id_status_cucian'] == $permintaan['id_status_cucian'])
                                           echo 'selected'; ?>>
                                        <?php echo $row['jenis_status_cucian']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary" value="Simpan">
                            <a href="permintaan_cucian.php" class="btn btn-secondary">Batal</a>
                        </div>
                        <div class="text-danger">
                            <?php echo $error; ?>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <!-- Daftar Item -->
                    <h4>Daftar Item</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Item</th>
                                <th>Jumlah</th>
                                <th>Harga per Item</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $totalHarga = 0;
                            while ($row = mysqli_fetch_assoc($resultItem)):
                                $jumlah = $row['jumlah_item'];
                                $hargaPerItem = $row['harga_per_item'];
                                $totalHargaItem = $jumlah * $hargaPerItem;
                                $totalHarga += $totalHargaItem;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $no; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['jenis_item']; ?>
                                    </td>
                                    <td>
                                        <?php echo $jumlah; ?>
                                    </td>
                                    <td>
                                        <?php echo $hargaPerItem; ?>
                                    </td>
                                    <td>
                                        <?php echo $totalHargaItem; ?>
                                    </td>
                                    <td>
                                        <a href="edit_item_cucian.php?id=<?php echo $row['no_itemCucian']; ?>&no_cucian=<?php echo $noCucian; ?>"
                                            class="btn btn-sm btn-primary edit-btn">Edit</a>

                                        <a href="hapus_item_cucian.php?id=<?php echo $row['no_itemCucian']; ?>&no_cucian=<?php echo $noCucian; ?>"
                                            class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            endwhile;
                            ?>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td><strong>
                                        <?php echo $totalHarga; ?>
                                    </strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <a href="tambah_item_cucian.php?no_cucian=<?php echo $noCucian; ?>"
                            class="btn btn-primary">Tambah
                            Item</a>

                    </div>
                    <div id="edit-form-container"></div>
                </div>
            </div>
        </div>
    </section>
</main>


<?php include 'footer.php'; ?>