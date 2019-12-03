<table border="1">
  <tr>
  <th>NIS</th>
  <th>Nama</th>
  <th>Kelas</th>
  <th>Asal</th>
  <th>Ekstrakurikuler</th>

  </tr>
<?php
  // buat koneksi dengan database mysql
  $link = mysqli_connect("localhost","root","","ypjkk");
  
  // ambil nama mahasiswa dari query string
  $nama_siswa = $_GET["n"];
  
  // ambil dara dari tabel mahasiswa
  $query  = "SELECT * FROM daftarsiswa WHERE ekstrakurikuler = '$nama_siswa' ";
  $result = mysqli_query($link, $query);

  //buat perulangan untuk element tabel dari data mahasiswa
  while($data = mysqli_fetch_row($result))
  { 
    // konversi date MySQL (yyyy-mm-dd) menjadi dd-mm-yyyy
    $tanggal_php = strtotime($data[3]);
    $tanggal = date("d - m - Y", $tanggal_php);
    
    // tampilkan data dalam bentuk tabel HTML

    echo "<tr>";
    echo "<td>$data[0]</td>";
    echo "<td>$data[1]</td>";
    echo "<td>$data[2]</td>";
    echo "<td>$data[3]</td>";
    echo "<td>$data[4]</td>";
    echo "</tr>";

  }
?>
    </table></br>	