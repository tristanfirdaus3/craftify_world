<?php 
    include 'koneksi.php';
    session_start();
    $dataPegive_poinroduk = [[]];
    $give_poinSemuaProduk = 0; 
    $jumlahSemuaProduk = 0;
 
    if (!isset($_SESSION['id'])) {
        header("Location: index.php");
        exit();
    }

    if(isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
        echo "<script>window.location.replace('beranda.php?keyword=$keyword');</script>"; 
        die(); 
    }

    if(isset($_SESSION['id'])){ 
        $id = $_SESSION['id'];

        try { 
            $query = $db->prepare("select * from users where id = ?");
            $query->bindParam(1, $id); 
            $query->execute();
            $dataUser = $query->fetch();

            $query = $db->prepare("SELECT k.id_user, k.id_produk, SUM(k.jumlah) as jumlah_barang, p.nama, p.give_poin, p.resistance, p.gambar, p.resistance, p.Poin_karya
            from (SELECT * FROM detail_keranjang WHERE id_user = ?) as k 
            JOIN produk as p on(k.id_produk = p.id) 
            GROUP BY k.id_produk
            ORDER BY k.id DESC;");

            $query->bindParam(1, $id); 
            $query->execute();
            $dataKeranjang = $query->fetchAll();
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    if(isset($_POST['logout'])){
        session_start();
        session_unset();
        session_destroy();
        
        header("Location:index.php");
        exit();
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../animatedStars.css">
</head>
<body>
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>

    <nav class="text-white p-2 flex justify-between fixed -mt-2 z-40 w-full backdrop-blur supports-backdrop-blur:bg-white/60 border-b border-slate-400">
            <div class="flex items-center">
            <a href="beranda.php" class="inline-block w-32">
                <img class="ml-7" src="../img/smktibali.png" alt="logo smkti" width="70%">
                <a class="text-sm font-bold font-mono" href="beranda.php">Craftify World</a>
            </a>
        </div>
        <div class="w-1/2">
            <form action="" method="GET">
                <div class="flex mt-6 border border-slate-400 rounded-sm p-2 w-full">
                    <span class="material-symbols-outlined">search</span>
                    <input name="keyword" class="bg-transparent w-full ml-3 focus:outline-none" placeholder="Cari Product" type="text" name="keyword">
                </div>
            </form>
        </div>
        <div class="flex mr-20 gap-5 items-center font-sans font-bold">
            <!-- Modal toggle -->
            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="flex gap-2" type="button">
                <span class="material-symbols-outlined">account_circle</span>
                <p>Profile</p>
            </button>
        </div>
    </nav>

    <span class="flex justify-between text-white font-extrabold text-2xl mb-2 max-w-[670px] mt-40 ml-10">
        <h1>Daftar Karya</h1>
        <h1><?php echo(count($dataKeranjang)); ?></h1>
    </span>

    
    <div class="flex text-white h-[90%] gap-10">
        <div class="w-[60%] p-10">
            <?php $i = 0; ?>
            <?php foreach ($dataKeranjang as $keranjang): ?>
                <div>
                    <hr class="bg-white">
                    <div class="flex gap-5 mt-5">
                        <img class="rounded-md relative block" src="../<?php echo$keranjang['gambar'] ?>" alt="product img" width="100px">
                        <span class="">
                            <h1 class="text-white font-extrabold mb-2 max-w-[800px]"><?php echo$keranjang['nama'] ?></h1>
                            <h1 class="text-white font-extrabold mb-2 max-w-[800px]">give_poin<?php echo$keranjang['give_poin'] ?></h1>
                        </span>
                    </div>
        
                    <div class="flex justify-end gap-10 mb-5">
                        <a href="hapusKeranjang.php?id_produk=<?php echo$keranjang['id_produk']?>&jumlah_Poin=<?php echo$keranjang['jumlah_Poin']; ?>">
                            <span class="material-symbols-outlined mt-2">
                                delete
                            </span>
                        </a>
                        <div class="flex rounded-md p-2 w-[100px]">
                            <input id="jumlah" class="bg-transparent w-full ml-3 focus:outline-none text-center mr-2" type="text" value="<?php echo$keranjang['jumlah_Poin'] ?>"> Poin
                        </div>
                    </div>
                    <hr class="bg-white">
                </div>

                <?php 
                    $give_poinSemuaProduk += $keranjang['give_poin'] * $keranjang['jumlah_Poin']; 
                    $jumlahSemuaProduk += $keranjang['jumlah_Poin'];
                    $dataPegive_poinroduk[$i]['id_produk'] = $keranjang['id_produk'];
                    $dataPegive_poinroduk[$i]['jumlah_Poin'] = $keranjang['jumlah_Poin'];
                    $dataPegive_poinroduk[$i]['resistance'] = $keranjang['resistance'];
                    $dataPegive_poinroduk[$i]['jumlah_Poin'] = $keranjang['jumlah_Poin'];
                ?>
            <?php $i++; endforeach; ?>
        </div>

        <form action="" method="POST">
            <div class="fixed h-[350px] -mt-10 right-40">
                <h1 class="font-extrabold text-2xl mb-2 max-w-[800px] text-center">Ringkasan daftar</h1><br>
                
                <div class="bg-[#1E2933] rounded-md w-[300px] h-[80%] py-6 px-2 font-bold">
                    <span class="text-center mb-8 flex justify-between p-5">
                        <h1>Jumlah Poin</h1>
                        <h1><?php echo$jumlahSemuaProduk ?></h1>
                    </span>
                    <div class="flex justify-between mb-8 mx-3 p-5">
                        <h1>Total Poin</h1>
                        <h1>give_poin<?php echo$give_poinSemuaProduk ?></h1>
                    </div>
                    <div class="flex justify-center">
                        <button name="beli" class="border border-slate-400 rounded-md py-1 w-24 text-center">Beri</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Main modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Profile
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4" action="" method="POST">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 flex justify-between font-bold">
                            <label for="name" class="block mb-2 text-xl text-gray-900 dark:text-white">saldo_point</label>
                            <input type="text" name="name" id="name" class="text-gray-900 text-right" value="<?php echo$dataUser['saldo_point']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <input type="text" name="name" id="name" value="<?php echo$dataUser['username']?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="text" name="name" id="name" value="<?php echo$dataUser['password']?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button name="logout" type="submit" class="text-white bg-slate-800 font-medium rounded-lg text-sm w-32 py-2.5 text-center">
                            Logout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <?php 
        if(isset($_POST["beli"])){
            $saldo_pointBaru = $dataUser['saldo_point'] - $give_poinSemuaProduk;

            if($saldo_pointBaru < 0){
                echo "<script> alert('saldo_point Anda Tidak Cukup!!');</script>"; 
            }else{
                try { 
                    // update saldo_point
                    $query = $db->prepare("update users set saldo_point=? where id=?");
                    $query->bindParam(1, $saldo_pointBaru);
                    $query->bindParam(2, $dataUser['id']);
                    $query->execute();

                    $index = count($dataPegive_poinroduk);
                    
                    // update resistance dan data produk yang telah Poin_karya   
                    for($j = 0; $j < count($dataPegive_poinroduk); $j++){
                        $jumlahBarang = $dataPegive_poinroduk[$j]['jumlah_barang'];
                        $resistanceBaru = $dataPegive_poinroduk[$j]['resistance'] - $jumlahBarang;
                        if($resistanceBaru <= 0){
                            $resistanceBaru = 0;
                        }
                        $Poin_karyaBaru = $dataPegive_poinroduk[$j]['Poin_karya'] + $jumlahBarang;
                        
                        $query = $db->prepare("update produk set resistance=?, Poin_karya=? where id=?");
                        $query->bindParam(1, $resistanceBaru);
                        $query->bindParam(2, $Poin_karyaBaru);
                        $query->bindParam(3, $dataPegive_poinroduk[$j]['id_produk']);
                        $query->execute();
                    }

                    $query = $db->prepare("delete from detail_keranjang where id_user in(?)");
                    $query->bindParam(1, $dataUser['id']);
                    $query->execute();
                    $data = $query->fetchAll();

                    $query=null; //tutup koneksi
                    echo "<script> alert('Pemberian poin Berhasi!!');
                    window.location.replace('beranda.php');</script>"; 
                    die(); 
                }catch (PDOException $exception) {
                    die("Connection error: " . $exception->getMessage());
                }
            }
        }
    ?>

    <!-- Script supaya Pop up Modal nya bisa berjalan -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</html>
