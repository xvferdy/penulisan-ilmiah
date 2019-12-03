<?php
// buat koneksi dengan database mysql
$link = mysqli_connect("localhost", "root", "", "ypjkk");

// ambil kolom nama dari tabel mahasiswa
$query  = "SELECT ekstrakurikuler FROM masterekskul";
$result = mysqli_query($link, $query);

// tambahkan tag <option> untuk setiap nama mahasiswa
while ($data = mysqli_fetch_array($result)) {
  echo "<option value='{$data['ekstrakurikuler']}'>{$data['ekstrakurikuler']}</option>";
}
