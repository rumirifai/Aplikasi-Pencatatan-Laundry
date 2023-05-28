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

mysqli_close($conn);
?>

<?php include 'header_pelanggan.php'; ?>

<main id="main">
    <section id="pelanggan" class="pelanggan">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Profil Pelanggan</h2>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-3">
                    <div class="profile-image">
                        <?php if (!empty($row['profil_image_url'])) : ?>
                            <img src="<?php echo $row['profil_image_url']; ?>" alt="Foto Profil" class="img-fluid rounded-circle border">
                        <?php else : ?>
                            <div class="no-photo">
                                <span><?php echo substr($row['nama'], 0, 1); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="edit-photo">
                        <a href="edit_foto_profil_pelanggan.php" class="btn btn-primary">Edit Foto Profil</a>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="profile-info">
                        <h3>Data Profil</h3>
                        <p><strong>Nama:</strong> <?php echo $row['nama']; ?></p>
                        <p><strong>Alamat:</strong> <?php echo $row['alamat']; ?></p>
                        <p><strong>Telepon:</strong> <?php echo $row['telepon']; ?></p>
                    </div>
                    <div class="profile-buttons">
                        <a href="edit_profil_pelanggan.php" class="btn btn-primary">Edit Profil</a>
                        <a href="ubah_password.php" class="btn btn-primary">Ubah Password</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .pelanggan {
        padding: 40px 0;
    }

    .pelanggan h2 {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 40px;
    }

    .pelanggan h3 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .pelanggan p {
        margin-bottom: 10px;
    }

    .pelanggan img {
        max-width: 100%;
        height: auto;
    }

    .profile-image {
        text-align: center;
        position: relative;
    }

    .no-photo {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 200px;
        height: 200px;
        background-color: #ffff;
        border-radius: 50%;
        font-size: 72px;
        font-weight: bold;
    }

    .no-photo span {
        color: #fff;
    }

    .edit-photo {
        text-align: center;
        margin-top: 10px;
    }

    .profile-info {
        margin-bottom: 20px;
    }

    .profile-buttons {
        text-align: center;
        margin-top: 20px;
    }

    .profile-buttons .btn {
        font-size: 16px;
        padding: 10px 20px;
        margin-right: 10px;
    }
</style>

<?php include 'footer.php'; ?>
