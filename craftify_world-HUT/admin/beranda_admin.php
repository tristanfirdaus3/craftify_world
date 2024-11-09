<?php 
    include 'koneksi.php';
    session_start();
    $dataSemuaProduk = [];

    // fugnsi untuk mencari data, jika tidak ketemu maka tampilkan saja semua data
    if(isset($_GET['keyword'])){ 
        $keyword = "%{$_GET['keyword']}%";

        try { 
            $queryCari = $db->prepare("select * from produk where nama LIKE ? ");
            $queryCari->bindParam(1, $keyword);
            $queryCari->execute();
            $dataSemuaProduk = $queryCari->fetchAll(); 
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }else{
        $query = $db->prepare("select * from produk order by id desc");
        $query->execute();
        $dataSemuaProduk = $query->fetchAll(); 
    }

    // fungsi menampilkan data profile admin dan fungsi untuk mendekteksi apakah admin sudah login
    if(isset($_SESSION['id'])){ 
        $id_admin = $_SESSION['id'];

        try { 
            $query = $db->prepare("select * from admin where id_admin = ?");
            $query->bindParam(1, $id_admin); 
            $query->execute();
            $data = $query->fetch();
            if($_SESSION['tipe'] == "user"){
                header("Location: ../user/beranda.php");
                exit();
            }
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }else{ 
        header("Location: ../index.php");
        exit();
    }

    // fungsi logout
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
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../animatedStars.css">
</head>
<body>
    <!-- Star Background -->
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>

    <!-- Nav bar -->
    <nav class="text-white p-2 flex justify-between fixed -mt-2 z-40 w-full backdrop-blur supports-backdrop-blur:bg-white/60 border-b border-slate-400">
            <div class="flex items-center">
            <a href="beranda_admin.php" class="inline-block w-32">
                <img class="ml-7" src="../img/smktibali.png" alt="logo smkti" width="70%">
                <a class="text-sm font-bold font-mono" href="beranda_admin.php">Craftify World</a>
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

    <!-- Title -->
    <div class="h-full flex items-center justify-center font-sans" >
        <span>
            <p class="text-slate-300 font-bold text-xs text-center">EXPLORE, CREATE AND GIVE POIN</p>
            <h1 class="text-white text-5xl font-bold text-center">Dashboard Admin</h1>
        </span>
    </div>

    <!-- All product -->
    <div class="grid grid-cols-1 grid-rows-2 mt-5 ml-16">
        <h1 class="text-white font-extrabold col-start-1 row-start-1">All Creation</h1>
        
        <div class="flex flex-wrap absolute gap-[35px] mt-14 pb-20">
            <!-- Modal toggle tambah -->
            <button data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" type="button">
            <div class="border bg-[#151D28] rounded-md overflow-hidden border-red-white w-[200px] h-[380px] flex items-center justify-center">
                <span class="text-white inline-block my-2 mx-4">
                        <span class="material-symbols-outlined text-6xl">add</span>
                        <h1>Tambah karya</h1>
                    </span>
                </div>
            </button>

            <?php foreach ($dataSemuaProduk as $value): ?>
                <a href="formEditProduk.php?id_produk=<?php echo$value['id']?>">
                    <div class="border bg-[#151D28] overflow-hidden border-red-white w-[200px] h-[380px] rounded-sm">
                        <?php $gambar = $value['gambar']; ?>
                        <div class="bg-[url('../<?php echo$gambar ?>')] w-full h-60 bg-center bg- bg-cover bg-no-repeat"></div>
                        <span class="text-white inline-block my-3 mx-4">
                            <h1 class="inline-block max-h-[45px] overflow-hidden leading-6 text-sm"><?php echo$value['nama'] ?></h1>
                            <h1 class="font-bold mt-3">give_poin<?php echo$value['give_poin'] ?></h1>
                            <p class="text-sm mt-1 mb-1">resistance  <?php echo$value['resistance'] ?></p>
                            <p class="text-sm">Poin_karya <?php echo$value['Poin_karya'] ?></p>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    
    <!-- Add product modal -->
    <div id="add-product-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Tambah Karya
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-product-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4" method="POST" action="" enctype="multipart/form-data">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label for="give_poin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">give_poin</label>
                            <input type="text" name="give_poin" id="give_poin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label for="resistance" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">resistance</label>
                            <input type="text" name="resistance" id="resistance" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label for="gambar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Gambar</label>
                            <input type="file" name="gambar" id="gambar" onChange="preview()">
                        </div>
                        <div class="col-span-2">
                            <label for="gambar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Preview Gambar</label>
                            <img id="frame" src="" alt="preview" width="250" height="250" class="mx-auto">
                        </div>
                    </div>
                    <input name="tambahBarang" type="submit" class="text-white bg-slate-800 font-medium rounded-lg text-sm w-32 py-2.5 text-center" value="Tambah">
                </form>
            </div>
        </div>
    </div> 

    <!-- Fungsi Tambah Barang -->
    <?php 
        if(isset($_POST['tambahBarang'])){ 
            $name = $_POST['name'];
            $gambar = $_FILES["gambar"]["tmp_name"];
            $give_poin = $_POST['give_poin'];
            $resistance = $_POST['resistance'];

            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
            $noSpasiFile = preg_replace('/\s+/', '', $target_file);
            copy($gambar, "../$noSpasiFile");
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            try { 
                $query = $db->prepare("insert into produk
                (nama, give_poin, resistance, gambar) values (?,?,?,?) ");
                $query->bindParam(1, $name);
                $query->bindParam(2, $give_poin);
                $query->bindParam(3, $resistance);
                $query->bindParam(4, $noSpasiFile);
                $query->execute();
                $query=null; //tutup koneksi
                echo "<script> alert('Produk berhasil diTambah!!');
                window.location.replace('beranda_admin.php');</script>"; 
                die(); 
            }catch (PDOException $exception) {
                die("Connection error: " . $exception->getMessage());
            }
        }
    ?>

    <!-- Profile modal -->
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
                <form class="p-4" method="POST" action="">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$data['username']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$data['password']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Id-Admin</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$data['id_admin']?>">
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

    <!-- ini script untuk profile upload preview -->
    <script>
        function preview() {
            frame.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <!-- Ini script supaya modal pop up nya bisa berjalan  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</html>
