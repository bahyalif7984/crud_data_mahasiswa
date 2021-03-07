<?php

    $server= "localhost";
    $user="root";
    $pass="";
    $database="dblatihan";

    $koneksi =mysqli_connect($server,$user,$pass, $database) or die(mysqli_error($koneksi));


    
      // jika tombol simpan diklik
      if(isset($_POST['bsimpan'])){

        // pengujian apakah data di edit/ disimpan baru
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        if($_GET['hal'] == "edit"){
          // data di edit
          $foto_=$_FILES['tfoto']['name'];
          $lokasi_foto_simpan=$_FILES['tfoto']['tmp_name'];
          
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          $tampil=mysqli_query($koneksi,"select * from tmhs where id_mhs='$_GET[id]' ");
          $data=mysqli_fetch_array($tampil);
          $foto=$data['foto'];
          
          if(file_exists("gambar/$foto")){
            unlink("gambar/$foto");
          }

          move_uploaded_file($lokasi_foto_simpan,"gambar/".$foto_);
          $simpan= mysqli_query($koneksi,"update tmhs set nim='$_POST[tnim]',nama='$_POST[tnama]',alamat='$_POST[talamat]',prodi='$_POST[prodi]',foto='$foto_' where id_mhs='$_GET[id]'");
          if($simpan){ //jika simpan sukses
            echo "<script>alert('edit data sukses')
            document.location='index.php'</script>";
          }else{
            echo "<script>alert('edit data gagal')
            document.location='index.php'</script>";
          }
        }else{
          // data disimpan baru
          $foto_=$_FILES['tfoto']['name'];
          $lokasi_foto_simpan=$_FILES['tfoto']['tmp_name'];
          $size_foto=$_FILES['tfoto']['size'];
          $ekstensi_diperbolehkan=array('png','jpg');
          $x=explode('.',$foto_);
          $ekstensi= strtolower(end($x));
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

          if(in_array($ekstensi,$ekstensi_diperbolehkan)===true){
            if($size_foto <  1000000 ){
              error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

              move_uploaded_file($lokasi_foto_simpan,"gambar/".$foto_);
              $simpan= mysqli_query($koneksi,"insert into tmhs(nim,nama,alamat,prodi,foto) values ('$_POST[tnim]','$_POST[tnama]','$_POST[talamat]','$_POST[prodi]','$foto_')");
              if($simpan){ //jika simpan sukses
                echo "<script>alert('simpan data sukses')
                document.location='index.php'</script>";
                
              }else{
        
                echo "<script>alert('simpan data gagal')
                document.location='index.php'</script>";
              }
            }else{
              echo "<script>alert('gambar lebih dari 1 MB'); window.location='index.php';</script>";
            }
          }else{
            echo "<script>alert('ekstensi gambar hanya bisa jpg dan png'); window.location='index.php';</script>";
          }
       
         
        }

   
      }

  
      // pengujian jika btn rdit/hapus di klik
      if(isset($_GET['hal'])){
        // tampil data yg akan di edit
        if($_GET['hal']=="edit"){
          // 
          $tampil=mysqli_query($koneksi,"select * from tmhs where id_mhs='$_GET[id]' ");
          $data=mysqli_fetch_array($tampil);
          if($data){
            // jika data ditemukan , maka data ditampung ke dalam variabel
            $vnim=$data['nim'];
            $vnama=$data['nama'];
            $valamat=$data['alamat'];
            $vprodi=$data['prodi'];
            $vfoto=$data['foto'];
          }
        }else if($_GET['hal'] =="hapus"){
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          $tampil=mysqli_query($koneksi,"select * from tmhs where id_mhs='$_GET[id]' ");
          $data=mysqli_fetch_array($tampil);
          $foto=$data['foto'];
          unlink("gambar/$foto");

          $hapus=mysqli_query($koneksi,"delete from tmhs where id_mhs= '$_GET[id]'");
        
         
          if($hapus){
            echo "<script>alert('hapus data sukses')
            document.location='index.php'</script>";
          }else{
            echo "<script>alert('hapus data gagal')
            document.location='index.php'</script>";
          }
        }
      }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="src/style/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container">
      <h1 class="text-center ">CRUD(create, read, update,delete) PHP & MYSQL </h1>
      <h2 class="text-center ">portofolio bahy alif</h2>
  
      <div class="card">
        <div class="card-header bg-primary text-white">
          Form input data mahasiswa
        </div>
        <div class="card-body">
          <form action="" method="post" enctype="multipart/form-data">
              <section>
              <div class="form-group">
              <label for="">Nim</label>
              <input type="text" name="tnim" class="form-control" placeholder="Input Nim" value="<?=@$vnim ?>" required>
            </div>
            <div class="form-group">
              <label for="">Nama</label>
              <input type="text" name="tnama" class="form-control" placeholder="Input Nama"  value="<?=@$vnama ?>" required>
            </div>
            <div class="form-group">
              <label for="">Alamat</label>
              <textarea name="talamat" id="" cols="30" rows="5" class="form-control" placeholder="Input Alamat" value="" required><?=@$valamat ?></textarea>
            </div>
            <div class="form-group">
              <label for="">Program studi</label>
              <select name="prodi" id="" class="form-control" required>
                <option value="<?=@$vprodi ?>"><?=@$vprodi ?></option>
                <option value="D3-MI">D3-MI</option>
                <option value="S1-TI">S1-TI</option>
                <option value="D3-SI">D3-SI</option>
              </select>
            </div>

            <div class="form-group">
            
            <input type="file" name="tfoto" class="form-control" placeholder="Input Nama"    value="<?=@$vfoto ?>">
            <img src="gambar/<?php echo $data['foto']?>" class="mt-3"  alt="" style="width: 150px;">
            </div>
            <button type="submit" class="btn-success p-3" name="bsimpan" style="cursor: pointer;border: none;">Simpan</button>
       
            <button type="reset" class="btn-danger p-3" name="breset" style="cursor: pointer;border: none;">Reset</button>
              </section>
          </form>

        </div>
      </div>


      <div class="card mt-5 mb-4">
        <div class="card-header bg-success text-white">
          Data Mahasiswa
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
       
              <tr>
                <th scope="col">No</th>
                <th scope="col">Nim</th>
                <th scope="col">Nama</th>
                <th scope="col">Alamat </th>
                <th scope="col">Program Studi</th>
                <th  scope="col-lg-3">foto</th>
                <th  scope="col-lg-3">Aksi</th>
              </tr>
              
            </thead>
            <tbody>
            <?php
              $no=1;
              $tampil= mysqli_query($koneksi,"select * from tmhs order by id_mhs asc");
              while($data =mysqli_fetch_array($tampil)):
            ?>
              <tr>
                <th scope="row"><?php echo $no++; ?></th>
                <td><?php echo $data['nim'];?></td>
                <td><?php echo $data['nama'];?></td>
                <td><?php echo $data['alamat'];?></td>
                <td><?php echo $data['prodi'];?></td>
                <td><img src="gambar/<?php echo $data['foto'];?>" alt="" style="width: 150px;"></td>
                <td><a href="index.php?hal=edit&id=<?=$data['id_mhs'];?>"><i class="far fa-edit btn-edit" name="edit"></i></a>
                <a href="index.php?hal=hapus&id=<?=$data['id_mhs'] ;?>"name="delete" onclick="return confirm('apakah ingin hapus data ?')"><i class="fas fa-trash-alt btn-delete" name="delete"></i></a></td>
             

              </tr>
          <?php
            endwhile; //penutup perulangan
          ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
   
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>