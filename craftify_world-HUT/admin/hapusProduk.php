<?php 
    include("koneksi.php");
    session_start();
    $id_produk = $_GET['id_produk'];

    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
        exit;
    }

    try { 
        $query = $db->prepare("delete from produk where id = ?");
        $query->bindParam(1, $id_produk);
        $query->execute();
        $data = $query->fetchAll(); 
        echo "<script> alert('Karya berhasil diHapus!!');
        window.location.replace('beranda_admin.php');</script>"; 
        die();
    }catch (PDOException $exception) {
        die("Connection error: " . $exception->getMessage());
    }
?>