<?php
include("ceklogin.php"); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Administrator</title>
    <link rel="stylesheet" href="../assets/style.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
        $('.summernote').summernote({
            tabsize: 2,
            height: 300,
        });
        });
    </script>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Selamat Datang, Administrator!</h1>
        </header>

        <div class="menu">
            <?php if (isset($_SESSION['loginadmin'])): ?>
                <div class="menu-links">
                    <a href="./">Home</a>
                    <a href="?mod=kategori">Kategori</a>
                    <a href="?mod=berita">Berita</a>
                    <a href="?mod=konfigurasi">Konfigurasi</a>
                    <a href="?mod=useradmin">User Admin</a>
                    <span class="logout"><a href="?keluar=yes">Log Out</a></span>
                </div>
            <?php else: ?>
                <div class="login-container">
                    <div class="login-form">
                        <h2>Login Admin</h2>
                        <form action="ceklogin.php" method="POST">
                            <div class="input-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" required>
                            </div>
                            <div class="input-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" required>
                            </div>
                            <input type="submit" name="submit" value="Login" class="btn btn-primary">
                        </form>
                        <?php if (isset($_GET['error'])): ?>
                            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="content">
            <?php
            $mod = isset($_GET['mod']) ? $_GET['mod'] : '';
            switch ($mod) {
                case 'useradmin':
                    include("useradmin.php");
                    break;
                case 'konfigurasi':
                    include("konfigurasi.php");
                    break;
                case 'berita':
                    include("berita.php");
                    break;
                case 'kategori':
                    include("kategori.php");
                    break;
                default:
                    if (isset($_SESSION['loginadmin'])) {
                        echo "<p>Selamat datang, <strong>" . $_SESSION['loginadmin'] . "</strong></p>";
                    }
                    break;
            }
            ?>
        </div>
    </div>
</body>
</html>
