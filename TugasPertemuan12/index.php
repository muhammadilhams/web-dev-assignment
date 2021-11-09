<?php
//koneksi database
$server = "localhost";
$user = "root";
$pass = "";
$database = "dbpertemuan12";

$koneksi = mysqli_connect($server, $user, $pass, $database) or die(mysqli_error($koneksi));

//aktifkan tombol simpan
if (isset($_POST['bsimpan'])) {
    if ($_GET['hal'] == "edit") {
        
        $nim=$_POST['tnim'];
        $nama=$_POST['tnama'];
        $alamat=$_POST['talamat'];
        $prodi=$_POST['tprodi'];
        $gambar=$_FILES['tgambar']['name'];

        if($gambar != "") {
            $ekstensi_diperbolehkan = array('png','jpg'); //ekstensi file gambar yang bisa diupload 
            $x = explode('.', $gambar); //memisahkan nama file dengan ekstensi yang diupload
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['tgambar']['tmp_name'];   
            $angka_acak     = rand(1,999);
            $nama_gambar_baru = $angka_acak.'-'.$gambar; //menggabungkan angka acak dengan nama file sebenarnya
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true)  {
                          move_uploaded_file($file_tmp, 'gambar2/'.$nama_gambar_baru); //memindah file gambar ke folder gambar
                              
                            // jalankan query UPDATE berdasarkan ID yang produknya kita edit
                           $query  = "UPDATE tmhs SET nim = '$nim', nama = '$nama', alamat = '$alamat', prodi = '$prodi', gambar = '$nama_gambar_baru'";
                            $query .= "WHERE id_mhs = '$_GET[id]' ";
                            $result = mysqli_query($koneksi, $query);
                            // periska query apakah ada error
                        
                      } 
        }else {
            // jalankan query UPDATE berdasarkan ID yang produknya kita edit
            $query  =  "UPDATE tmhs SET nim = '$nim', nama = '$nama', alamat = '$alamat', prodi = '$prodi'";
            $query .= "WHERE id_mhs = '$_GET[id]'";
            $result = mysqli_query($koneksi, $query);
            // periska query apakah ada error
          
          }
  
    } else {
        $name_p = $_FILES['tgambar']['name'];
        $sumber_p = $_FILES['tgambar']['tmp_name'];
        move_uploaded_file($sumber_p, 'gambar2/'.$name_p);
        $simpan = mysqli_query($koneksi, "INSERT INTO tmhs 
        VALUES(NULL, '".$_POST['tnim']."','".$_POST['tnama']."','".$_POST['talamat']."','".$_POST['tprodi']."', '".$name_p."')");
        if ($simpan) {
            echo "<script>
            alert('Simpan data sukses');
            document.location='index.php';
            </script>";
        } else {
            echo "<script>
            alert('Simpan data gagal');
            document.location='index.php';
            </script>";
        }
    }
}
//aktifkan tombol edit dan hapus
if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $tampil = mysqli_query($koneksi, "SELECT * FROM tmhs WHERE id_mhs = '$_GET[id]'");
        $data = mysqli_fetch_array($tampil);
        if ($data) {
            $vnim = $data['nim'];
            $vnama = $data['nama'];
            $valamat = $data['alamat'];
            $vprodi = $data['prodi'];
            $vgambar = $data['gambar'];
        }
    } else if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM tmhs WHERE id_mhs='$_GET[id]'");
        echo "<script>
            alert('Hapus data sukses');
            document.location='index.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>PERTEMUAN 12</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">PERTEMUAN 12</h1>
        <h2 class="text-center">Pembuatan CRUD</h2>

        <!--Awal card Form Daftar-->
        <div class="card border-info mt-3">
            <div class="card-header">Form Input Siswa</div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div>
                        <label>NIM</label>
                        <input type="text" name="tnim" value="<?= @$vnim ?>" class="form-control" placeholder="Input NIM Anda" required>
                    </div>

                    <div>
                        <label>NAMA</label>
                        <input type="text" name="tnama" value="<?= @$vnama ?>" class="form-control" placeholder="Input Nama Anda" required>
                    </div>

                    <div>
                        <label>Alamat</label>
                        <textarea name="talamat" class="form-control" placeholder="Input Alamat Anda" required><?= @$valamat ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Prodi</label>
                        <select class="form-control" name="tprodi">
                            <option value="<?= @$vprodi ?>"></option>
                            <option value="S1-MT">S1-MT</option>
                            <option value="S1-SI">S1-SI</option>
                            <option value="S1-AK">S1-AK</option>
                        </select>

                    </div>
                    <div>
                        <p> Silahkan Upload tgambar: </br><p>
                        <input type="file" name="tgambar" value="" class="from-control">
                    </div> 
                    <button type="submit" class="btn btn-success" name="bsimpan">Simpan</button>
                    <button type="reset" class="btn btn-danger" name="breset">Reset</button>
                </form>
            </div>
        </div>
        <!--Akhir Card Form Daftar-->

        <!--Awal card Form List Siswa-->
        <div class="card border-info mt-3">
            <div class="card-header">Daftar Mahasiswa</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>Program Studi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>

                    <?php
                    $no = 1;
                    $tampil = mysqli_query($koneksi, "SELECT * from tmhs order by id_mhs desc");
                    while ($data = mysqli_fetch_array($tampil)) :
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $data['nim']; ?></td>
                            <td><?= $data['nama']; ?></td>
                            <td><?= $data['alamat']; ?></td>
                            <td><?= $data['prodi']; ?></td>
                            <td><img src="gambar2/<?php echo $data['gambar']; ?>"  width="200px"/> </td>
                            <td>
                                <a href="index.php?hal=edit&id=<?= $data['id_mhs'] ?>" class="btn btn-warning">Edit</a>
                                <a href="index.php?hal=hapus&id=<?= $data['id_mhs'] ?>" onclick="return confirm('Apakah Anda yakin menghapus data ini?')" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
        <!--Akhir Card Form list Siswa-->


    </div>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>