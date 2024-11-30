<?php
if (isset($_POST['tambahuser'])) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = mysqli_query($conn, "SELECT * FROM administrator WHERE username = '$username' OR email = '$email'");
    $hasil = mysqli_num_rows($sql); 
    
    if ($hasil > 0) {
        $error = "Username dan email sudah digunakan";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT); 
        $sql = mysqli_query($conn, "INSERT INTO administrator (nama, username, password, email) 
                                    VALUES ('" . $_POST['nama'] . "', '$username', '$password_hashed', '$email')");
        
        $error = $sql ? "User admin baru berhasil ditambahkan" : "Terjadi kesalahan saat menambahkan user";
    }
}

if (isset($_POST['edituser'])) {
    global $conn;

    $id = (int)$_POST['userid'];  
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = mysqli_query($conn, "UPDATE administrator SET username = '$username', nama = '$nama', email = '$email' WHERE ID = '$id'");
    $error = $sql ? "Data user admin telah diperbarui" : "Terjadi kesalahan saat memperbarui user";
}

$act = isset($_GET['act']) ? $_GET['act'] : '';  
if ($act == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = mysqli_query($conn, "SELECT * FROM administrator WHERE ID = '$id'");
    $b = mysqli_fetch_array($sql, MYSQLI_ASSOC);
}

if ($act == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = mysqli_query($conn, "DELETE FROM administrator WHERE ID = '$id'");
    $error = "Data user admin telah dihapus";
}
?>

<div class="user-form-container">
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form action="./?mod=useradmin" method="POST">
        <input type="hidden" name="userid" value="<?= isset($b['ID']) ? $b['ID'] : '' ?>">
        <fieldset>
            <legend><?= isset($b['ID']) ? 'Edit User' : 'Tambah User' ?></legend>

            <div class="form-group">
                <label for="nama">Nama User</label>
                <input type="text" name="nama" id="nama" value="<?= isset($b['nama']) ? $b['nama'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= isset($b['username']) ? $b['username'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required><br>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= isset($b['email']) ? $b['email'] : '' ?>" required>
            </div>

            <input type="submit" name="<?= isset($b['ID']) ? 'edituser' : 'tambahuser' ?>" value="<?= isset($b['ID']) ? 'Edit' : 'Tambah' ?>" class="btn btn-primary">
        </fieldset>
    </form>
</div>

<div class="user-list-container">
    <fieldset>
        <legend>List User</legend>
        <div class="user-table">
            <div class="row header">
                <div class="column">No.</div>
                <div class="column">Username</div>
                <div class="column">Nama</div>
                <div class="column">Email</div>
                <div class="column">Aksi</div>
            </div>

            <?php
            $i = 1; 
            $sql = mysqli_query($conn, "SELECT * FROM administrator ORDER BY ID ASC");
            while ($r = mysqli_fetch_array($sql)) {
                extract($r);
                echo "
                <div class='row'>
                    <div class='column'>$i</div>
                    <div class='column'>$username</div>
                    <div class='column'>$nama</div>
                    <div class='column'>$email</div>
                    <div class='column'>
                        <a href='?mod=useradmin&act=edit&id=$ID' class='btn btn-primary'>Edit</a>
                        <a href='?mod=useradmin&act=hapus&id=$ID' class='btn btn-danger'>Hapus</a>
                    </div>
                </div>";
                $i++;
            }
            ?>
        </div>
    </fieldset>
</div>
