<?php
session_start();
include "config.php";

// Pastikan pelanggan telah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

// Inisialisasi variabel pesan kesalahan
$error = '';
$success = '';

// Proses ubah password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Fetch data pelanggan
    $query = "SELECT * FROM Pelanggan WHERE id_pelanggan = '$id_pelanggan'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Validasi password lama
    if (password_verify($password_lama, $row['password'])) {
        // Validasi konfirmasi password baru
        if ($password_baru === $konfirmasi_password) {
            // Update password dalam database
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $query = "UPDATE Pelanggan SET password = '$hashed_password' WHERE id_pelanggan = '$id_pelanggan'";
            $updateResult = mysqli_query($conn, $query);

            if ($updateResult) {
                $success = 'Password berhasil diubah.';
            } else {
                $error = 'Terjadi kesalahan saat mengubah password. Silakan coba lagi.';
            }
        } else {
            $error = 'Konfirmasi password baru tidak cocok.';
        }
    } else {
        $error = 'Password lama yang Anda masukkan salah.';
    }
}

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="ubah-password" class="ubah-password">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Ubah Password</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="password_lama">Password Lama</label>
                            <div class="input-group">
                                <input type="password" name="password_lama" id="password_lama" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" data-target="#password_lama">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="password_baru" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" data-target="#password_baru">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" data-target="#konfirmasi_password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                            <a href="profil_pelanggan.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                    <?php if ($error !== '') : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success !== '') : ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Toggle password visibility
    var togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = document.querySelector(this.dataset.target);
            if (target.type === 'password') {
                target.type = 'text';
                this.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                target.type = 'password';
                this.innerHTML = '<i class="fa fa-eye"></i>';
            }
        });
    });
</script>

<style>
    .ubah-password {
        padding: 40px 0;
    }

    .ubah-password h2 {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 40px;
    }

    .ubah-password form {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .ubah-password .form-group {
        margin-bottom: 20px;
    }

    .ubah-password .input-group {
        position: relative;
    }

    .ubah-password .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>

<?php include 'footer.php'; ?>
