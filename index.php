<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WebGIS Kabupaten Sleman</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 600px;
        }

        .title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin: 20px 0;
            color: #ffffff;
            background-color: #007BFF;
            padding: 10px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        table {
            width: 80%;
            margin: 20px auto; /* Center table on page */
            border-collapse: collapse;
            text-align: center; /* Center-align text in table cells */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #d1ecf1;
        }

        .input-button {
            text-align: center;
            margin: 20px auto;
        }

        .input-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .input-button button:hover {
            background-color: #218838;
        }

        .table-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .table-header h2 {
            margin: 0;
            text-align: center;
        }

        .button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-button {
            background-color: #007BFF;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="title">WebGIS Kabupaten Sleman</div>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.761324006844154, 110.30906628007445], 10);

        // Tile Layer Base Map
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        osm.addTo(map);
    </script>

    <script>
        // Marker dari Database
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "PGWEBACARA8";

        // Koneksi ke database
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query untuk mengambil data koordinat dan info
        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);

        // Inisialisasi array untuk menyimpan data GeoJSON
        $geojson = ['type' => 'FeatureCollection', 'features' => []];

        if ($result->num_rows > 0) {
            // Looping untuk setiap baris data
            while ($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["kecamatan"];

                // Tambahkan setiap data sebagai feature ke dalam geojson
                $geojson['features'][] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$long, (float)$lat]
                    ],
                    'properties' => [
                        'info' => $info
                    ]
                ];
            }
        } else {
            echo "console.log('Tidak ada data ditemukan');";
        }

        // Menutup koneksi
        $conn->close();
        ?>

        // Menampilkan marker di peta menggunakan GeoJSON
        var geojsonData = <?php echo json_encode($geojson); ?>;

        // Menambahkan GeoJSON ke peta
        L.geoJSON(geojsonData, {
            pointToLayer: function (feature, latlng) {
                return L.marker(latlng).bindPopup(feature.properties.info); // Hanya pop-up
            }
        }).addTo(map);
    </script>

    <!-- Judul untuk Tabel -->
    <div class="table-header">
        <h2>Data Kecamatan</h2>
    </div>

    <!-- Tabel untuk Menampilkan Data -->
    <table>
        <tr>
            <th>Kecamatan</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Luas</th>
            <th>Jumlah Penduduk</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Kembali ke database untuk mengambil data yang sama untuk ditampilkan di tabel
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query untuk mengambil data dari tabel penduduk
        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data dari setiap baris
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["kecamatan"]."</td>
                    <td>".$row["longitude"]."</td>
                    <td>".$row["latitude"]."</td>
                    <td>".$row["luas"]."</td>
                    <td>".$row["jumlah_penduduk"]."</td>
                    <td>
                        <a class='button edit-button' href='edit.php?id=".$row["id"]."'>Edit</a>  
                        <a class='button delete-button' href='hapus.php?id=".$row["id"]."' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }

        // Tutup koneksi
        $conn->close();
        ?>
    </table>

    <!-- Tombol Input Data di Bawah Tabel -->
    <div class="input-button">
        <a href="index.html"><button>Input Data</button></a>
    </div>

</body>

</html>
