<?php
session_start();
include 'server.php';

// Tentukan folder penyimpanan
$directory = "berkas/";
if (!is_dir($directory)) {
    mkdir($directory, 0755, true); // Membuat folder jika belum ada
}

// Inisialisasi variabel untuk pesan error
$errors = [];

// Validasi Nama
if (empty($_POST['nama'])) {
    $errors[] = "Nama tidak boleh kosong.";
}

// Validasi Email
if (empty($_POST['email'])) {
    $errors[] = "Email tidak boleh kosong.";
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}

// Validasi Nomor HP
if (empty($_POST['nomor_hp'])) {
    $errors[] = "Nomor HP tidak boleh kosong.";
} elseif (!is_numeric($_POST['nomor_hp']) || strlen($_POST['nomor_hp']) < 10 || strlen($_POST['nomor_hp']) > 12) {
    $errors[] = "Nomor HP harus terdiri dari angka dan panjangnya antara 10-12 digit.";
}

// Validasi Semester
if (empty($_POST['semester'])) {
    $errors[] = "Semester harus dipilih.";
} elseif ($_POST['semester'] < 1 || $_POST['semester'] > 8) {
    $errors[] = "Semester harus antara 1 hingga 8.";
}

// Tentukan IPK berdasarkan semester
$ipk = ($_POST['semester'] >= 4) ? 3.4 : 2.9; // IPK otomatis berdasarkan semester

// Validasi upload berkas
if (empty($_FILES['berkas']['name'])) {
    $errors[] = "Berkas syarat harus diupload.";
} else {
    $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'zip'];
    $file_ext = strtolower(pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) {
        $errors[] = "Berkas harus berupa file dengan format: pdf, jpg, jpeg, png, atau zip.";
    }
}

// Jika ada error, simpan pesan error dalam session dan arahkan kembali ke form
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
    exit();
}

// Jika tidak ada error, lanjutkan ke proses upload dan simpan ke database
$file_name = 'berkas_' . time() . '_' . basename($_FILES['berkas']['name']);
$target_file = $directory . $file_name;

// Pindahkan file ke folder "berkas"
if (move_uploaded_file($_FILES['berkas']['tmp_name'], $target_file)) {
    // Query untuk menyimpan data ke database
    $query = "INSERT INTO data_beasiswa 
              (nama, email, nomor_hp, semester, ipk, beasiswa, berkas, status_ajuan) 
              VALUES ('$_POST[nama]', '$_POST[email]', '$_POST[nomor_hp]', '$_POST[semester]', 
                      '$ipk', '$_POST[beasiswa]', '$file_name', 'Belum Diverifikasi')";

    $save = mysqli_query($conn, $query);

    if ($save) {
        $_SESSION['success'] = "Data Pendaftaran Berhasil Dikirim!";
        header('Location: hasil.php');
        exit();
    } else {
        $_SESSION['error'] = "Data Pendaftaran Gagal Dikirim!";
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Upload file gagal. Pastikan file sesuai format!";
    header('Location: index.php');
    exit();
}
?>
