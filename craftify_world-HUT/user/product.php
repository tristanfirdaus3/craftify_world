<?php 
    include 'koneksi.php';
    session_start();

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
        $nim = $_SESSION['id'];

        try { 
            $query = $db->prepare("select * from users where id = ?");
            $query->bindParam(1, $nim); 
            $query->execute();
            $dataUser = $query->fetch();
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    // fungsi untuk mengambil satu data produk dari url/ variable GET['id_produk]
    if(isset($_GET['id_produk'])){ 
        $id = $_GET['id_produk'];

        try { 
            $query = $db->prepare("select * from produk where id = ?");
            $query->bindParam(1, $id); 
            $query->execute();
            $dataProduk = $query->fetch();
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    if(isset($_POST['logout'])){
        session_start();
        session_unset();
        session_destroy();
        
        header("Location: ../index.php");
        exit();
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../animatedStars.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</head>
<body>
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>

    <nav class="text-white p-2 flex justify-between fixed -mt-2 z-40 w-full backdrop-blur supports-backdrop-blur:bg-white/60 border-b border-slate-400">
            <div class="flex items-center">
            <a href="beranda.php" class="inline-block w-32">
                <img class="ml-7" src="../img/smktibali.png" alt="" width="70%">
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
            <span class="material-symbols-outlined pr-5 border-r-2"><a href="keranjang.php">Creation_cart</a></span> 
            <!-- Modal toggle -->
            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="flex gap-2" type="button">
                <span class="material-symbols-outlined">account_circle</span>
                <p>Profile</p>
            </button>
        </div>
    </nav>
    
    <div class="flex text-white items-center justify-center h-[90%] mt-14 gap-20">
        <img class="rounded-md relative block" src="../<?php echo$dataProduk['gambar'] ?>" alt="product img" width="320px">

        <div class="relative h-[350px] -mt-10">
            <div>
                <h1 class="font-extrabold text-2xl mb-2 max-w-[800px]"><?php echo$dataProduk['nama'] ?></h1>
                <p><?php echo$dataProduk['Poin_karya'] ?> Poin karya</p><br>
            </div>
            
            <div class="bg-[#1E2933] rounded-md w-[300px] h-[80%] py-6 px-2">
                <span class="text-center block mb-8">
                    <h1 id="give_poin" class="font-bold text-2xl">Poin-<?php echo$dataProduk['give_poin'] ?></h1>
                    <p class="text-sm">resistance: <?php echo$dataProduk['resistance'] ?></p>
                </span>
                <form class="flex-col mb-8" method="POST" action="">
                    <div class="flex border border-slate-400 rounded-md p-2 w-[35%] mb-3 mx-auto">
                        <button id="min">-</button>
                        <input name="jumlah" id="jumlah" class="bg-transparent w-full ml-3 focus:outline-none text-center mr-2" type="text" value="1">
                        <button id="add">+</button>
                    </div>
                    <div class="flex justify-between mb-8 mx-3">
                        <h1>Subtotal</h1>
                        <input id="subTotal" type="text" disabled class="font-bold bg-transparent text-right" value="poin-<?php echo$dataProduk['give_poin'] ?>">
                    </div>
                    <div class="flex justify-center gap-5">
                        <button name="beli" class="border border-slate-400 rounded-md py-1 w-24 text-center">Beli</button>
                        <button name="keranjang" class="bg-slate-400 rounded-md py-1 w-24 text-center">+Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
        if(isset($_POST['beli'])){
            $quantity = $_POST['jumlah'];
            $saldo_pointBaru = $dataUser['saldo_point'] - $quantity * $dataProduk['give_poin'];

            if($saldo_pointBaru < 0){
                echo "<script> alert('saldo_point Anda Tidak Cukup!!');</script>"; 
            }else{
                try { 
                    // update saldo_point
                    $query = $db->prepare("update users set saldo_point=? where id=?");
                    $query->bindParam(1, $saldo_pointBaru);
                    $query->bindParam(2, $dataUser['id']);
                    $query->execute();

                    // update resistance dan data produk yang telah Poin_karya
                    $resistanceBaru = $dataProduk['resistance'] - $quantity;
                    $Poin_karyaBaru = $dataProduk['Poin_karya'] + $quantity;
                    if($resistanceBaru <= 0){
                        $resistanceBaru = 0;
                    }

                    $query = $db->prepare("update produk set resistance=?, Poin_karya=? where id=?");
                    $query->bindParam(1, $resistanceBaru);
                    $query->bindParam(2, $Poin_karyaBaru);
                    $query->bindParam(3, $dataProduk['id']);
                    $query->execute();
                    $query=null; //tutup koneksi
                    echo "<script> alert('Pembelian Berhasi!!');
                    window.location.replace('beranda.php');</script>"; 
                    die(); 
                }catch (PDOException $exception) {
                    die("Connection error: " . $exception->getMessage());
                }
            }
        }

        if(isset($_POST['keranjang'])){
            $quantity = $_POST['jumlah'];
            $query = $db->prepare("insert into detail_keranjang(id_user, id_produk, jumlah) values (?,?,?)");
            $query->bindParam(1, $dataUser['id']);
            $query->bindParam(2, $dataProduk['id']);
            $query->bindParam(3, $quantity);
            $query->execute();

            $resistanceBaru = $dataProduk['resistance'] - $quantity;
            if($resistanceBaru <= 0){
                $resistanceBaru = 0;
            }

            $query = $db->prepare("update produk set resistance=? where id=?");
            $query->bindParam(1, $resistanceBaru);
            $query->bindParam(2, $dataProduk['id']);
            $query->execute();

            $query=null; //tutup koneksi
            echo "<script> alert('Produk berhasil diTambahkan!!');
            window.location.replace('keranjang.php');</script>"; 
            die(); 
        }
    ?>

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

    <!-- ini untuk event handle click plus minus quantity produk -->
    <script>
        let jumlah = document.querySelector("#jumlah");
        let addBtn = document.querySelector("#add");
        let minBtn = document.querySelector("#min");
        let give_poinText = document.querySelector("#give_poin");
        let give_poin = give_poinText.textContent.replace("Poin-", "");
        let subTotalText = document.querySelector("#subTotal");

        addBtn.addEventListener("click", function(e){
            e.preventDefault(); // inilah Prevent Dafult nya, menghilangkan aksi defaultnya
            jumlah.value = parseInt(jumlah.value) + 1; 
            subTotalText.value = "Poin-"+give_poin * jumlah.value;
        });
        minBtn.addEventListener("click", function(e){
            e.preventDefault(); // inilah Prevent Dafult nya, menghilangkan aksi defaultnya
            if(jumlah.value > 0){
                jumlah.value = parseInt(jumlah.value) - 1; 
                subTotalText.value = "Poin-"+give_poin * jumlah.value;
            }
        });
    </script>

    <!-- Script supaya Pop up Modal nya bisa berjalan -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</body>
</html>
