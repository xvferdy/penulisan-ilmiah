<?php
// periksa apakah user sudah login, cek kehadiran session name 
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
}

// buka koneksi dengan MySQL
include("koneksi.php");

// cek apakah form telah di submit (untuk menghapus data)
// cek form name submit telah di submit
if (isset($_POST["submit"])) {
  // form telah disubmit, proses data

  // ambil nilai nis 
  $nis = htmlentities(strip_tags(trim($_POST["nis"])));
  // filter data
  $nis = mysqli_real_escape_string($link, $nis);

  //jalankan query DELETE
  $query = "DELETE FROM daftarsiswa WHERE nis='$nis' ";
  $hasil_query = mysqli_query($link, $query);

  //periksa query, tampilkan pesan kesalahan jika gagal
  if ($hasil_query) {
    // DELETE berhasil, redirect ke tampil_siswa.php + pesan
    $pesan = "siswa dengan nis = \"<b>$nis</b>\" sudah berhasil di hapus";
    $pesan = urlencode($pesan);
    header("Location: hapus_siswa.php?pesan={$pesan}");
  } else {
    die("Query gagal dijalankan: " . mysqli_errno($link) .
      " - " . mysqli_error($link));
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>YPJ School | Selamat datang</title>
  <link href="style.css" rel="stylesheet">
  <link rel="icon" href="ypj2.png" type="image/png">
</head>

<body>
  <div class="container">
    <div id="header">
      <h1 id="logo">Sistem Informasi <span>Ekstrakurikuler</span></h1>
      <p id="tanggal"><?php echo date("d M Y"); ?></p>
    </div>
    <hr>
    <nav>
      <a href="tampil_siswa.php">MasterSiswa</a>
      <a href="tampil_ekskul.php">MasterEkskul</a>
      <a href="tambah_ekskul.php"><small>Tambah Ekskul</small></a>
      <a href="tambah_siswa.php"><small>Tambah Siswa</small></a>
      <ul>
        <li><a href="informasi.php">Informasi</a></li>
        <li><a href="daftar_siswa.php">Daftar</a></li>
        <li><a href="hapus_siswa.php">Edit</a></li>
        <li><a href="logout.php">Logout</a>
      </ul>
    </nav>
    <form id="search" action="tampil_siswa.php" method="get">
      <p>
        <label for="nis">Nama : </label>
        <input type="text" name="nama" id="nama" placeholder="search...">
        <input type="submit" name="submit" value="Search">
      </p>
    </form>
    <h2>DATA SISWA</h2>
    <?php
    // tampilkan pesan jika ada
    if ((isset($_GET["pesan"]))) {
      echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
    }
    ?>
    <table border="1">
      <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Asal</th>
        <th>Ekstrakurikuler</th>
        <th>Hapus</th>
        <th>Edit</th>
      </tr>
      <?php
      // buat query untuk menampilkan seluruh data tabel siswa
      $query = "SELECT * FROM daftarsiswa ORDER BY nama ASC";
      $result = mysqli_query($link, $query);

      if (!$result) {
        die("Query Error: " . mysqli_errno($link) .
          " - " . mysqli_error($link));
      }

      //buat perulangan untuk element tabel dari data siswa
      while ($data = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>$data[nis]</td>";
        echo "<td>$data[nama]</td>";
        echo "<td>$data[kelas]</td>";
        echo "<td>$data[asal]</td>";
        echo "<td>$data[ekstrakurikuler]</td>";
        echo "<td>";
        ?>
        <form action="hapus_siswa.php" method="post">
          <input type="hidden" name="nis" value="<?php echo "$data[nis]"; ?>">
          <input type="submit" name="submit" value="Hapus" onclick="myFunction()" title="Hello beautiful">
        </form>
        <?php
        echo "</td>";
        echo "<td>";
        ?>
        <form action="form_edit_siswa.php" method="post">
          <input type="hidden" name="nis" value="<?php echo "$data[nis]"; ?>">
          <input type="submit" name="submit" value="Edit">
        </form>
        <?php
        echo "</td>";
        echo "</tr>";
      }

      // bebaskan memory 
      mysqli_free_result($result);

      // tutup koneksi dengan database mysql
      mysqli_close($link);
      ?>
    </table>
    <div id="footer">
      Copyright © <?php echo date("Y"); ?> YPJ Kuala-Kencana
    </div>
  </div>

  <script>
    // buat modal box (konfirmasi popout)
    var tesfinal = document.querySelectorAll("input[title~=beautiful]");

    function myFunction() {
      var r = confirm(
        "Konfirmasi!\nHapus Data Siswa?"
      );
      for (i = 0; i < tesfinal.length; i++) {
        if (r == true) {
          tesfinal[i].setAttribute('name', 'submit');
        } else {
          tesfinal[i].setAttribute('name', 'tidakSubmit');
        }
      }
    }
  </script>
</body>

</html>