<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

// Fetch data pelanggan
$query = "SELECT * FROM Pelanggan WHERE id_pelanggan = '$id_pelanggan'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Inisialisasi variabel pesan kesalahan
$error = '';

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    // Validasi input

    // Nama harus diisi
    if (empty($nama)) {
        $error = 'Nama harus diisi';
    }

    // Jika tidak ada kesalahan, update data profil
    if (empty($error)) {
        $query = "UPDATE Pelanggan SET nama = '$nama', alamat = '$alamat', telepon = '$telepon' WHERE id_pelanggan = '$id_pelanggan'";
        $update_result = mysqli_query($conn, $query);

        if ($update_result) {
            // Redirect ke halaman profil setelah berhasil update
            header("Location: profil_pelanggan.php");
            exit();
        } else {
            $error = 'Terjadi kesalahan saat mengupdate profil. Silakan coba lagi.';
        }
    }
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="edit-profil" class="edit-profil">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Edit Profil</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $row['nama']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control"><?php echo $row['alamat']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control" value="<?php echo $row['telepon']; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .edit-profil {
        padding: 40px 0;
    }

    .edit-profil h2 {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 40px;
    }
</style>

<?php include 'footer.php'; ?>
