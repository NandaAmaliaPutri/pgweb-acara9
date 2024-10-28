<?php
// Sesuaikan dengan setting MySQL
$servername = "localhost";
$username = "root";
$password = ""; // Sesuaikan jika ada password
$dbname = "PGWEBACARA8"; // Nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel penduduk
$sql = "SELECT id, kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1px'><tr>
    <th>Kecamatan</th>
    <th>Longitude</th>
    <th>Latitude</th>
    <th>Luas</th>
    <th>Jumlah Penduduk</th>
    <th>Aksi</th></tr>";
    
    // Output data dari setiap baris
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>".$row["kecamatan"]."</td>
        <td>".$row["longitude"]."</td>
        <td>".$row["latitude"]."</td>
        <td>".$row["luas"]."</td>
        <td align='right'>".$row["jumlah_penduduk"]."</td>
        <td>
            <a href='edit.php?id=".$row["id"]."'>Edit</a> | 
            <a href='hapus.php?id=".$row["id"]."' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
        </td></tr>";
    }
    
    echo "</table>";
} else {
    echo "0 results";
}

// Tutup koneksi
$conn->close();
?>