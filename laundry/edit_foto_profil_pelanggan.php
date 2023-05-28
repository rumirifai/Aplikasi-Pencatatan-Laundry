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

// Proses upload foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/"; // Direktori upload foto
    $uploadFile = $uploadDir . basename($_FILES['foto']['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Validasi tipe file
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowedTypes)) {
        $error = 'Tipe file yang diizinkan hanya JPG, JPEG, dan PNG.';
    }

    // Jika tidak ada kesalahan, upload file
    if (empty($error)) {
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            $fotoUrl = $uploadFile;

            // Update URL foto profil dalam database
            $query = "UPDATE Pelanggan SET profil_image_url = '$fotoUrl' WHERE id_pelanggan = '$id_pelanggan'";
            $updateResult = mysqli_query($conn, $query);

            if ($updateResult) {
                // Redirect ke halaman profil setelah berhasil update
                header("Location: profil_pelanggan.php");
                exit();
            } else {
                $error = 'Terjadi kesalahan saat mengupload foto profil. Silakan coba lagi.';
            }
        } else {
            $error = 'Terjadi kesalahan saat mengupload foto profil. Silakan coba lagi.';
        }
    }
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="edit-foto-profil" class="edit-foto-profil">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Edit Foto Profil</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <input type="file" name="foto" id="foto" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Upload</button>
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
    .edit-foto-profil {
        padding: 40px 0;
    }

    .edit-foto-profil h2 {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 40px;
    }

    .edit-foto-profil .form-group {
        margin-bottom: 20px;
    }
</style>

<?php include 'footer.php'; ?>
