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

// Mengambil daftar pelanggan dari tabel Pelanggan
$pelangganQuery = "SELECT * FROM Pelanggan";
$pelangganResult = mysqli_query($conn, $pelangganQuery);

// Mengambil daftar prioritas dari tabel Prioritas
$prioritasQuery = "SELECT * FROM Prioritas";
$prioritasResult = mysqli_query($conn, $prioritasQuery);

// Mengambil daftar status cucian dari tabel StatusCucian
$statusQuery = "SELECT * FROM StatusCucian";
$statusResult = mysqli_query($conn, $statusQuery);

// Inisialisasi variabel error
$error = '';

// Memproses saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
    // Mengambil data dari form
    $idPelanggan = $_POST['id_pelanggan'];
    $idPrioritas = $_POST['id_prioritas'];
    $idStatusCucian = $_POST['id_status_cucian'];

    // Validasi data
    if (empty($idPelanggan) || empty($idPrioritas) || empty($idStatusCucian)) {
        $error = "Silakan lengkapi semua field.";
    } else {
        // Mendapatkan durasi berdasarkan id_prioritas dari tabel Prioritas
        $durasiQuery = "SELECT durasi FROM Prioritas WHERE id_prioritas = '$idPrioritas'";
        $durasiResult = mysqli_query($conn, $durasiQuery);
        $durasi = mysqli_fetch_assoc($durasiResult)['durasi'];

        // Menghitung tanggal selesai berdasarkan durasi
        $tglMasuk = date('Y-m-d');
        $tglSelesai = date('Y-m-d', strtotime($tglMasuk . ' + ' . $durasi . ' days'));

        // Menyimpan data permintaan cucian ke tabel PermintaanCucian
        $insertQuery = "INSERT INTO PermintaanCucian (id_pelanggan, id_prioritas, tgl_masuk, tgl_selesai, id_status_cucian)
                        VALUES ('$idPelanggan', '$idPrioritas', '$tglMasuk', '$tglSelesai', '$idStatusCucian')";
        mysqli_query($conn, $insertQuery);

        // Mengalihkan ke halaman edit_permintaan_cucian.php dengan mengirim nomor cucian sebagai parameter
        $noCucian = mysqli_insert_id($conn);
        header("Location: edit_permintaan_cucian.php?no_cucian=$noCucian");
        exit;
    }
}

mysqli_close($conn);
?>

<script>
    $(document).ready(function() {
        $('#id_prioritas').change(function() {
            var idPrioritas = $(this).val();
            
            // Mengambil durasi dari database berdasarkan id_prioritas yang dipilih
            $.ajax({
                url: 'get_durasi.php',
                type: 'POST',
                data: {
                    id_prioritas: idPrioritas
                },
                dataType: 'json',
                success: function(response) {
                    var durasi = response.durasi;

                    // Menghitung tanggal selesai berdasarkan durasi
                    var tglMasuk = new Date().toISOString().split('T')[0];
                    var tglSelesai = new Date(new Date(tglMasuk).getTime() + durasi * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

                    // Mengisi nilai tanggal masuk dan tanggal selesai
                    $('#tgl_masuk').val(tglMasuk);
                    $('#tgl_selesai').val(tglSelesai);
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
                        <a href="permintaan_cucian.php" class="btn btn-primary">Kembali ke Daftar Permintaan Cucian</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Form Tambah Permintaan Cucian -->
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="id_pelanggan">Pelanggan</label>
                            <select class="form-control" id="id_pelanggan" name="id_pelanggan">
                                <?php while ($row = mysqli_fetch_assoc($pelangganResult)): ?>
                                    <option value="<?php echo $row['id_pelanggan']; ?>"><?php echo $row['nama']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_prioritas">Prioritas</label>
                            <select class="form-control" id="id_prioritas" name="id_prioritas">
                                <?php while ($row = mysqli_fetch_assoc($prioritasResult)): ?>
                                    <option value="<?php echo $row['id_prioritas']; ?>"><?php echo $row['jenis_prioritas']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tgl_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tgl_selesai">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" readonly>
                        </div>
                        <div class="form-group">
                            <label for="id_status_cucian">Status Cucian</label>
                            <select class="form-control" id="id_status_cucian" name="id_status_cucian">
                                <?php while ($row = mysqli_fetch_assoc($statusResult)): ?>
                                    <option value="<?php echo $row['id_status_cucian']; ?>"><?php echo $row['jenis_status_cucian']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary" value="Lanjutkan ke Detail Cucian">
                            <a href="permintaan_cucian.php" class="btn btn-secondary">Batal</a>
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
