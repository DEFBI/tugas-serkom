<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Beasiswa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <div class="menu-bar">
            <div class="menu-item">
                <a href="beasiswa.php">Pilih Beasiswa</a>
            </div>
            <div class="menu-item">
                <a href="index.php">Daftar</a>
            </div>
            <div class="menu-item">
                <a href="hasil.php">Hasil</a>
            </div>
        </div>

        <div class="black-bar"></div>

        <h4 class="form-title">DAFTAR BEASISWA</h4>
        <div class="card-title">Registrasi Beasiswa</div>
         <div class="form-container">
            <form action="server.php" method="post" enctype="multipart/form-data"> 
                <!-- Menampilkan pesan error atau sukses jika ada -->
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p style="color: red;"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                        <?php unset($_SESSION['errors']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <div class="form-grid">
                    <div class="form-item">
                        <label class="form-label" for="nama">Masukkan Nama</label>
                    </div>
                    <div class="form-item">
                        <input class="form-input" type="text" name="nama" id="nama" required>
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="email">Masukkan Email</label>
                    </div>
                    <div class="form-item">
                        <input class="form-input" type="email" name="email" id="email" required>
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="nomor_hp">Nomor HP</label>
                    </div>
                    <div class="form-item">
                        <input class="form-input" type="text" name="nomor_hp" id="nomor_hp" required maxlength="12">
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="semester">Semester saat ini</label>
                    </div>
                    <div class="form-item">
                        <select class="form-select" name="semester" id="semester" required onchange="updateIPK()">
                            <option value="">-- pilih semester --</option>
                            <?php
                            for ($i = 1; $i <= 8; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="ipk">IPK terakhir</label>
                    </div>
                    <div class="form-item">
                        <input class="form-input" type="text" name="calculated_ipk" id="calculated_ipk" value="" disabled>
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="beasiswa">Pilihan Beasiswa</label>
                    </div>
                    <div class="form-item">
                        <select class="form-select" name="beasiswa" id="beasiswa" required>
                            <option value="beasiswa1">Beasiswa 1</option>
                            <option value="beasiswa2">Beasiswa 2</option>
                            <option value="beasiswa3">Beasiswa 3</option>
                        </select>
                    </div>

                    <div class="form-item">
                        <label class="form-label" for="berkas">Upload Berkas Syarat</label>
                    </div>
                    <div class="form-item">
                        <input class="form-input" type="file" name="berkas" id="berkas">
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="form-button form-input" name="submit">Daftar</button>
            <button type="button" class="form-button cancel form-input" onclick="window.location.href='pilih-beasiswa.php'">Batal</button
                    
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateIPK() {
            var semesterSelect = document.getElementById("semester");
            var ipkInput = document.getElementById("calculated_ipk");

            // Tentukan IPK berdasarkan semester
            if (semesterSelect.value >= 4) {
                ipkInput.value = "3.4";
            } else {
                ipkInput.value = "2.9";
            }

            // Cek IPK dan atur status form
            checkIPK();
        }

        function checkIPK() {
            var ipk = parseFloat(document.getElementById("calculated_ipk").value);
            var beasiswaSelect = document.getElementById("beasiswa");
            var berkasInput = document.getElementById("berkas");
            var daftarButton = document.querySelector('button[name="submit"]');

            if (ipk < 3) {
                // Menonaktifkan elemen dan menambahkan kelas disabled-hover untuk menghindari hover
                beasiswaSelect.disabled = true;
                berkasInput.disabled = true;
                daftarButton.disabled = true;

                // Menambahkan kelas CSS untuk menonaktifkan hover dan cursor
                beasiswaSelect.classList.add('disabled-hover');
                berkasInput.classList.add('disabled-hover');
                daftarButton.classList.add('disabled-hover');
            } else {
                // Mengaktifkan elemen dan menghapus kelas disabled-hover
                beasiswaSelect.disabled = false;
                berkasInput.disabled = false;
                daftarButton.disabled = false;

                // Menghapus kelas CSS untuk mengaktifkan hover kembali
                beasiswaSelect.classList.remove('disabled-hover');
                berkasInput.classList.remove('disabled-hover');
                daftarButton.classList.remove('disabled-hover');

                beasiswaSelect.focus(); 
            }
        }


        window.onload = function() {
            updateIPK(); // Panggil fungsi saat halaman dimuat
            checkIPK();
        };
    </script>
</body>

</html>
