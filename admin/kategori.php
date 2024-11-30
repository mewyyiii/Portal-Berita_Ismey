<?php
$kategori = '';
$alias = '';
$terbit = 0;  
$ID = 0;  

if (isset($_POST['tambahkategori'])) {
    global $conn;
    
    $hasil = mysqli_query($conn, "INSERT INTO kategori (Kategori, alias, Terbit) 
    VALUES ('".$_POST['kategori']."', '".$_POST['alias']."', '".$_POST['terbit']."')");
}

// Edit
if (isset($_POST['editkategori']) && isset($_POST['ID'])) {
    $id = (int)$_POST['ID'];
    $kategori = $_POST['kategori'];
    $alias = $_POST['alias'];
    $terbit = $_POST['terbit'];

    global $conn;
    $hasil = mysqli_query($conn, "UPDATE kategori SET 
        Kategori = '$kategori', alias = '$alias', Terbit = '$terbit' 
        WHERE ID = '$id'");
    if ($hasil) {
        header('Location: ./?mod=kategori'); 
        exit;
    }
}

// Hapus 
if (isset($_GET['act']) && $_GET['act'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    global $conn;
    $hasil = mysqli_query($conn, "DELETE FROM kategori WHERE ID = '$id'");

    if ($hasil) {
        header('Location: ./?mod=kategori');
        exit;
    }
}

// edit data kategori
if (isset($_GET['act']) && $_GET['act'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    global $conn;
    $sql = mysqli_query($conn, "SELECT * FROM kategori WHERE ID = '$id'");
    while ($r = mysqli_fetch_array($sql)) {
        extract($r);

        $kategori = $Kategori;
        $alias = $alias;
        $terbit = $Terbit;
        $ID = $ID;
    }
}
?>
<div class="container">
        <div class="form-container">
            <form action="./?mod=kategori" method="POST">
                <input type="hidden" name="ID" value="<?=$ID;?>">

                <fieldset>
                    <legend><?=($ID ? 'Edit' : 'Tambah')?> Kategori</legend>

                    <div class="form-group">
                        <label for="kategori">Kategori:</label>
                        <input type="text" id="kategori" name="kategori" value="<?=$kategori;?>" required>
                    </div>

                    <div class="form-group">
                        <label for="alias">Alias:</label>
                        <input type="text" id="alias" name="alias" value="<?=$alias;?>" required>
                    </div>

                    <div class="form-group">
                        <label for="terbit">Tampilkan:</label>
                        <select id="terbit" name="terbit">
                            <option value="1" <?=($terbit == 1 ? 'selected' : ''); ?>>YES</option>
                            <option value="0" <?=($terbit == 0 ? 'selected' : ''); ?>>NO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="<?=($ID ? 'editkategori' : 'tambahkategori');?>" value="<?=($ID ? 'Edit' : 'Tambah');?>" class="btn btn-primary">
                    </div>
                </fieldset>
            </form>
        </div>

        <!-- List Kategori -->
        <fieldset>
            <legend>List Kategori</legend>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Alias</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $conn;
                    $sql = mysqli_query($conn, "SELECT * FROM kategori ORDER BY ID DESC");
                    while ($r = mysqli_fetch_array($sql)) {
                        extract($r);
                    ?>
                    <tr>
                        <td><?=$ID?></td>
                        <td><?=$Kategori?></td>
                        <td><?=$alias?></td>
                        <td>
                            <a href="./?mod=kategori&act=edit&id=<?=$ID?>" class="btn btn-primary">Edit</a>
                            <a href="./?mod=kategori&act=hapus&id=<?=$ID?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Hapus</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
