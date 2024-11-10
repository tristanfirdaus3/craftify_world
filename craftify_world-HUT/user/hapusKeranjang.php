<?php 
    include("koneksi.php");
    session_start();
    $id_produk = $_GET['id_produk'];
    $jumlah_Poin = $_GET['jumlah_Poin'];

    $query = $db->prepare("select * from produk where id=?");
    $query->bindParam(1, $id_produk);;
    $query->execute();
    $dataProduk = $query->fetch(); 
    $resistanceBaru = $dataProduk['resistance'] + $jumlah_Poin;
    if($resistanceBaru <= 0){
        $resistanceBaru = 0;
    }
    
    if(!isset($_SESSION['id'])){
        header("Location:index.php");
        exit;
    }

    try { 
        $query = $db->prepare("update produk set resistance=? where id=?");
        $query->bindParam(1, $resistanceBaru);
        $query->bindParam(2, $dataProduk['id']);
        $query->execute();

        $query = $db->prepare("delete from detail_keranjang where id_produk in(?)");
        $query->bindParam(1, $id_produk);
        $query->execute();
        $data = $query->fetchAll(); 
        echo "<script> alert('Karya berhasil diHapus dari keranjang!!');
        window.location.replace('keranjang.php');</script>"; 
        die();
    }catch (PDOException $exception) {
        die("Connection error: " . $exception->getMessage());
    }
?>
