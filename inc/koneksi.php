<?php
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', '19028_psas');

define('URL_SITUS', 'http://localhost/19028PORTALBERITA/');
//define('PATH_LOGO', 'image');
define('PATH_GAMBAR', 'photo');
//define('FILE_LOGO', 'logo.png');
//define('FILE_ICON', 'icon.png');

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if(mysqli_connect_errno()){
    echo "Gagal Koneksi ke Database". mysqli_connect_error();
}

?>