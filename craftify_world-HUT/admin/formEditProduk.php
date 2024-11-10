<?php 
    include 'koneksi.php';
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: ../index.php");
        exit();
    }

    if(isset($_SESSION['id'])){ 
        $nim = $_SESSION['id'];

        try { 
            $query = $db->prepare("select * from admin where id_admin = ?");
            $query->bindParam(1, $nim); 
            $query->execute();
            $data = $query->fetch();
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
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../animatedStars.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</head>
<body>
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>

    <!-- Nav Bar -->
    <nav class="text-white p-2 flex justify-between fixed -mt-2 z-40 w-full backdrop-blur supports-backdrop-blur:bg-white/60 border-b border-slate-400">
            <div class="flex items-center">
            <a href="beranda_admin.php" class="inline-block w-32">
                <img class="ml-7" src="../img/smktibali.png" alt="logo smkti" width="70%">
                <a class="text-sm font-bold font-mono" href="beranda_admin.php">Craftify World</a>
            </a>
        </div>
        <div class="pt-6">
            <h1 class="font-extrabold text-2xl text-center">Edit Produk</h1><br>
        </div>
        <div class="flex mr-36 gap-5 items-center font-sans font-bold">
            <!-- Modal toggle -->
            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="flex gap-2" type="button">
                <span class="material-symbols-outlined">account_circle</span>
                <p>Profile</p>
            </button>
        </div>
    </nav>
    
    <form class="p-4" method="POST" action="" enctype="multipart/form-data">
        <div class="flex text-white items-center justify-center h-[90%] mt-14 gap-20">
            <div>
                <img class="rounded-md relative block" id="frameEdit" src="../<?php echo$dataProduk['gambar'] ?>" alt="product img" width="320px">
                <label for="editGambar" class="block mb-2 text-sm font-medium text-gray-900">Upload editGambar</label>
                <input type="file" name="editGambar" id="editGambar" onChange="preview()">
            </div>

            <div class="relative h-[450px] mt-16">
                <div class="bg-[#1E2933] rounded-md w-[300px] h-[80%] py-6 px-2">
                    <!-- Form Edit Produk -->
                    
                    <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-white">Nama Karya</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$dataProduk['nama']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="give_poin" class="block mb-2 text-sm font-medium text-white">give_poin</label>
                            <input type="text" name="give_poin" id="give_poin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$dataProduk['give_poin']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="resistance" class="block mb-2 text-sm font-medium text-white">resistance</label>
                            <input type="text" name="resistance" id="resistance" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$dataProduk['resistance']?>">
                        </div>
                        <div class="col-span-2">
                            <label for="Poin_karya" class="block mb-2 text-sm font-medium text-white">Poin_karya</label>
                            <input type="text" name="Poin_karya" id="Poin_karya" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo$dataProduk['Poin_karya']?>">
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="submit" name="editBarang" class="text-white bg-slate-800 font-medium rounded-lg text-sm w-32 py-2.5 text-center mt-3">
                            Simpan
                        </button>
                        <button type="hapusBarang" class="text-white bg-slate-800 font-medium rounded-lg text-sm w-32 py-2.5 text-center mt-3">
                            <a href="hapusProduk.php?id_produk=<?php echo$id ?>">Hapus Produk</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Fungsi Edit Barang -->
    <?php 
        if(isset($_POST['editBarang'])){ 
            $name = $_POST['name'];
            $gambar = $_FILES["editGambar"]["tmp_name"];
            $give_poin = $_POST['give_poin'];
            $resistance = $_POST['resistance'];

            $noSpasiFile = $dataProduk['gambar'];

            if($gambar != null){
                $target_dir = "img/";
                $target_file = $target_dir . basename($_FILES["editGambar"]["name"]);
                $noSpasiFile = preg_replace('/\s+/', '', $target_file);
                copy($gambar, "../$noSpasiFile");
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            }

            try { 
                $query = $db->prepare("update produk set nama=?, give_poin=?, resistance=?, gambar=? where id=?");
                $query->bindParam(1, $name);
                $query->bindParam(2, $give_poin);
                $query->bindParam(3, $resistance);
                $query->bindParam(4, $noSpasiFile);
                $query->bindParam(5, $id);
                $query->execute();
                $query=null; //tutup koneksi
                echo "<script> alert('Karya berhasil di Edit!!');
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


    <script>
        function preview() {
            frameEdit.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <!-- Script supaya Pop up Modal nya bisa berjalan -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</body>
</html>
