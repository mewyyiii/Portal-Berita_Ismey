<?php
if (isset($_POST['tambahkonfigurasi'])) {
    global $conn;
    $sql = mysqli_query($conn, "INSERT INTO konfigurasi (Nama, Tax, Isi, Link, Tipe) 
        VALUES ('".$_POST['nama']."', '".$_POST['tax']."', '".$_POST['isi']."', '".$_POST['link']."','konfigurasi')");
}

if (isset($_POST['editkonfigurasi']) && isset($_POST['id'])) {
    $count = 0;

    foreach ($_POST['nama'] as $item) {
        if (isset($_POST['id'][$count])) {
            $sql = "UPDATE konfigurasi SET Nama='".$_POST['nama'][$count]."', Tax='".$_POST['tax'][$count]."', 
            Isi='".$_POST['isi'][$count]."', Link='".$_POST['link'][$count]."' WHERE ID='".$_POST['id'][$count]."' ";
            $hasil = mysqli_query($conn, $sql);
        }
        $count++;
    }
}

if (isset($_GET['act']) && $_GET['act'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $hasil = mysqli_query($conn, "DELETE FROM konfigurasi WHERE ID='$id'");

    // Cek penghapusan berhasil
    if ($hasil) {
        header("Location: ./?mod=konfigurasi");
        exit;
    }
}
?>

<div class="container">
    <h2>Tambah Konfigurasi</h2>
    <form action="./?mod=konfigurasi" method="POST">
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" required>
        </div>
        <div class="form-group">
            <label for="tax">Tax:</label>
            <input type="text" name="tax" id="tax" required>
        </div>
        <div class="form-group">
            <label for="isi">Isi:</label>
            <input type="text" name="isi" id="isi" required>
        </div>
        <div class="form-group">
            <label for="link">Link:</label>
            <input type="text" name="link" id="link" required>
        </div>
        <div class="form-group">
            <input type="submit" name="tambahkonfigurasi" value="Tambah Konfigurasi" class="btn btn-primary">
        </div>
    </form>

    <h2>List Konfigurasi</h2>
    <form action="./?mod=konfigurasi" method="POST">
        <?php
        global $conn;
        $hasil = mysqli_query($conn, "SELECT * FROM konfigurasi WHERE Tipe='konfigurasi'");
        while ($r = mysqli_fetch_array($hasil)) {
            extract($r);
        ?>
            <div class="form-group">
                <input type="hidden" name="id[]" value="<?=$ID;?>">
                
                <label for="nama-<?=$ID;?>">Nama:</label>
                <input type="text" id="nama-<?=$ID;?>" name="nama[]" value="<?=$Nama;?>" required>
            </div>

            <div class="form-group">
                <label for="tax-<?=$ID;?>">Tax:</label>
                <input type="text" id="tax-<?=$ID;?>" name="tax[]" value="<?=$Tax;?>" required>
            </div>

            <div class="form-group">
                <label for="isi-<?=$ID;?>">Isi:</label>
                <input type="text" id="isi-<?=$ID;?>" name="isi[]" value="<?=$Isi;?>" required>
            </div>

            <div class="form-group">
                <label for="link-<?=$ID;?>">Link:</label>
                <input type="text" id="link-<?=$ID;?>" name="link[]" value="<?=$Link;?>" required>
                
                <a href="./?mod=konfigurasi&act=hapus&id=<?=$ID;?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this configuration?')">x</a>
            </div>
            <hr> 
        <?php 
        }
        ?>
        <div class="form-group">
            <input type="submit" name="editkonfigurasi" value="Simpan Perubahan" class="btn btn-primary">
        </div>
    </form>
</div>

