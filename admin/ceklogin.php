<?php
session_start(); 

if (isset($_GET['keluar']) && $_GET['keluar'] == 'yes') {
    session_destroy(); 
    header('Location: index.php'); 
    exit;
}

include("../inc/koneksi.php"); 

if (isset($_POST['submit'])) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username atau Password tidak boleh kosong";
        header('Location: index.php?error=' . urlencode($error)); 
        exit;
    }

    $sql = "SELECT * FROM administrator WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $numrow = mysqli_num_rows($result);  

    if ($numrow > 0) {
        $r = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (password_verify($password, $r['password'])) {
            $_SESSION['loginadmin'] = $r['username'];  
            $_SESSION['loginadminid'] = $r['ID'];  
            $_SESSION['loginadminemail'] = $r['email'];
            $_SESSION['loginadminnama'] = $r['nama'];

            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau Password salah";
            header('Location: index.php?error=' . urlencode($error)); 
            exit;
        }
    } else {
        $error = "Username tidak ditemukan";
        header('Location: index.php?error=' . urlencode($error)); 
        exit;
    }
}
?>
