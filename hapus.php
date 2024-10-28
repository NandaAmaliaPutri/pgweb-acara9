<?php
// Cek apakah parameter id ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Sesuaikan dengan setting MySQL
    $servername = "localhost";
    $username = "root";
    $password = ""; // Sesuaikan jika ada password
    $dbname = "PGWEBACARA8"; // Pastikan nama database sesuai

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk menghapus data berdasarkan id
    $stmt = $conn->prepare("DELETE FROM penduduk WHERE id = ?");
    $stmt->bind_param("i", $id); // i = integer

    if ($stmt->execute()) {
        // Redirect ke index.php setelah penghapusan berhasil
        header("Location: index.php"); // Ganti dengan nama file halaman utama Anda
        exit; // Pastikan script berhenti setelah redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup koneksi dan statement
    $stmt->close();
    $conn->close();
} else {
    echo "ID tidak ditemukan.";
}
?>
