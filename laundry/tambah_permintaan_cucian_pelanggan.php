<?php include 'header_pelanggan.php'; ?>

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

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Lanjut ke Detail Cucian" ditekan
if (isset($_POST['lanjutkan'])) {
    // Mengambil data dari form
    $idPrioritas = $_POST['id_prioritas'];

    // Validasi data
    if (empty($idPrioritas)) {
        $error = "Silakan pilih prioritas cucian.";
    } else {
        // Mendapatkan durasi berdasarkan id_prioritas dari tabel Prioritas
        $durasiQuery = "SELECT durasi FROM Prioritas WHERE id_prioritas = '$idPrioritas'";
        $durasiResult = mysqli_query($conn, $durasiQuery);
        $durasi = mysqli_fetch_assoc($durasiResult)['durasi'];

        // Menghitung tanggal masuk dan tanggal keluar
        $tanggalMasuk = date('Y-m-d');
        $tanggalKeluar = date('Y-m-d', strtotime($tanggalMasuk . ' + ' . $durasi . ' days'));

        // Menyimpan data permintaan cucian ke tabel PermintaanCucian
        $idPelanggan = $_SESSION['id_pelanggan'];
        $insertQuery = "INSERT INTO PermintaanCucian (id_pelanggan, id_prioritas, tgl_masuk, tgl_selesai, id_status_cucian)
                        VALUES ('$idPelanggan', '$idPrioritas', '$tanggalMasuk', '$tanggalKeluar', '1')";
        mysqli_query($conn, $insertQuery);

        // Mengalihkan ke halaman edit_item_cucian_pelanggan.php setelah berhasil menyimpan
        $noCucian = mysqli_insert_id($conn);
header("Location: edit_permintaan_cucian_pelanggan.php?no_cucian=$noCucian");
exit;

    }
}

mysqli_close($conn);
?>

<!-- Script Ajax dan HTML Form -->
<script>
    $(document).ready(function () {
        $('#id_prioritas').change(function () {
            var idPrioritas = $(this).val();

            // Mengambil durasi dari database berdasarkan id_prioritas yang dipilih
            $.ajax({
                url: 'get_durasi.php',
                type: 'POST',
                data: {
                    id_prioritas: idPrioritas
                },
                dataType: 'json',
                success: function (response) {
                    var durasi = response.durasi;

                    // Menghitung tanggal keluar berdasarkan durasi
                    var tanggalMasuk = new Date().toISOString().split('T')[0];
                    var tanggalKeluar = new Date(new Date(tanggalMasuk).getTime() + durasi * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

                    // Mengisi nilai tanggal masuk dan tanggal keluar
                    $('#tanggal_masuk').val(tanggalMasuk);
                    $('#tanggal_keluar').val(tanggalKeluar);
                }
            });
        });
    });
</script>

<main id="main">
    <section id="admin" class="admin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Tambah Permintaan Cucian</h2>
                    <div class="text-right">
                        <a href="permintaan_cucian_pelanggan.php" class="btn btn-primary">Kembali ke Daftar Permintaan
                            Cucian</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Form Tambah Permintaan Cucian -->
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="id_prioritas">Prioritas</label>
                            <select class="form-control" id="id_prioritas" name="id_prioritas">
                                <?php while ($row = mysqli_fetch_assoc($prioritasResult)): ?>
                                    <option value="<?php echo $row['id_prioritas']; ?>"><?php echo $row['jenis_prioritas']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_keluar">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" readonly>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="lanjutkan" class="btn btn-primary"
                                value="Lanjut ke Detail Cucian">
                            <a href="permintaan_cucian_pelanggan.php" class="btn btn-secondary">Batal</a>
                        </div>
                        <div class="text-danger">
                            <?php echo $error; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
