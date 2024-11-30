<?php
if (isset($_POST['add']) || isset($_POST['edit'])) {

    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === 0) {
        $gambarfile = $_FILES['gambar']['tmp_name'];
        $gambarfile_name = $_FILES['gambar']['name'];
        $filetype = $_FILES['gambar']['type'];
        $allowtype = array('image/jpeg', 'image/jpg', 'image/png');

        if (!in_array($filetype, $allowtype)) {
            echo 'Tipe file tidak valid. Hanya diperbolehkan JPG atau PNG.';
            exit;
        }

        $path = 'photo/';
        $gambarbaru = preg_replace("/[^a-zA-Z0-9]/", "_", $_POST['judul']);
        $ext = ($filetype == 'image/jpeg' || $filetype == 'image/jpg') ? '.jpg' : '.png';
        $dest1 = '../' . $path . $gambarbaru . $ext;
        $dest2 = $path . $gambarbaru . $ext;

        if (move_uploaded_file($gambarfile, $dest1)) {
            $gambar = $dest2;
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        $gambar = isset($_POST['existing_gambar']) ? $_POST['existing_gambar'] : '';
    }

    // Ambil data dari form
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $teks = mysqli_real_escape_string($conn, $_POST['teks']);
    $terbit = mysqli_real_escape_string($conn, $_POST['terbit']);
    $author = mysqli_real_escape_string($conn, $_SESSION['loginadmin']);

    if (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE berita SET Judul='$judul', Isi='$isi', Kategori='$kategori', Gambar='$gambar', Teks='$teks', Terbit='$terbit' WHERE ID='$id'";
        $hasil = mysqli_query($conn, $sql);
        echo $hasil ? "Berita berhasil diperbarui." : "Gagal memperbarui berita.";
    } else {
        $sql = "INSERT INTO berita (Judul, Isi, Kategori, Gambar, Teks, Tanggal, View, Author, Post_type, Terbit) 
                VALUES ('$judul', '$isi', '$kategori', '$gambar', '$teks', '" . date("Y-m-d H:i:s") . "', 0, '$author', 'berita', '$terbit')";
        $hasil = mysqli_query($conn, $sql);
        echo $hasil ? "Berita berhasil ditambahkan." : "Gagal menambahkan berita.";
    }
}

// Hapus Berita
if (isset($_GET['act']) && $_GET['act'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = mysqli_query($conn, "SELECT * FROM berita WHERE ID = '$id'");
    $data = mysqli_fetch_array($sql);
    $gambar = $data['Gambar'];

    if ($gambar && file_exists('../' . $gambar)) {
        unlink('../' . $gambar);
    }

    $sql = mysqli_query($conn, "DELETE FROM berita WHERE ID = '$id'");
    echo $sql ? "Berita berhasil dihapus." : "Gagal menghapus berita.";
}
?>
<div class="container">
    <div class="content">
        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= isset($editData['ID']) ? $editData['ID'] : '' ?>">
                <input type="hidden" name="existing_gambar" value="<?= isset($editData['Gambar']) ? $editData['Gambar'] : '' ?>">

                <div class="form-group">
                    <label for="judul">Judul:</label>
                    <input type="text" id="judul" name="judul" value="<?= isset($editData['Judul']) ? $editData['Judul'] : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="isi">Isi:</label>
                    <textarea id="isi" name="isi" class="summernote" required><?= isset($editData['Isi']) ? $editData['Isi'] : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" value="<?= isset($editData['Kategori']) ? $editData['Kategori'] : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar:</label>
                    <input type="file" id="gambar" name="gambar">
                    <?php if (isset($editData['Gambar'])): ?>
                        <img src="../<?= $editData['Gambar'] ?>" alt="Gambar Berita" width="100">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="teks">Teks:</label>
                    <input type="text" id="teks" name="teks" value="<?= isset($editData['Teks']) ? $editData['Teks'] : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="terbit">Terbit:</label>
                    <select id="terbit" name="terbit">
                        <option value="1" <?= (isset($editData['Terbit']) && $editData['Terbit'] == 1) ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= (isset($editData['Terbit']) && $editData['Terbit'] == 0) ? 'selected' : '' ?>>Tidak</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" name="<?= isset($editData) ? 'edit' : 'add' ?>" value="<?= isset($editData) ? 'Edit Berita' : 'Tambah Berita' ?>">
                </div>
            </form>
        </div>

        <div class="berita-list">
            <fieldset>
                <legend>List Berita</legend>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Author</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM berita ORDER BY Tanggal DESC");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($sql)) {
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['Judul']}</td>
                                <td>{$row['Kategori']}</td>
                                <td>{$row['Author']}</td>
                                <td>{$row['Tanggal']}</td>
                                <td>
                                    <a href='?act=edit&id={$row['ID']}' class='btn btn-primary'>Edit</a>
                                    <a href='?act=hapus&id={$row['ID']}' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus berita ini?\")'>Hapus</a>
                                </td>
                            </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>
