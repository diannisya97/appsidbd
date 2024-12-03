        <?php
        //koneksi ke database
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "app-sidbd2";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        //ambil data pasien dari database berdasarkan ID
        $id = $_GET['id']; // ID pasien dari URL
        $sql = "SELECT * FROM pasien WHERE id = $id";
         ?>

       <div class="container mt-5">
        <?php
        // Pastikan untuk menjalankan query dan menyimpan hasilnya
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<h1>Data Pasien</h1>";
            echo "<table>";
            echo "<tr><th>Nama Lengkap Pasien</th></tr>"; // Header tabel
            while($row = $result->fetch_assoc()) {
                echo "<td>" . $row['namapasien'] . "</td>"; // Data pasien
            }
            echo "</table>";
        } else {
            echo "<h1>Data Pasien tidak ditemukan</h1>";
        }
        ?>
       </div>
    

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
