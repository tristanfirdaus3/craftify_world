<?php
    include 'koneksi.php';
    session_start();
 
    // fungsi menampilkan data profile user dan fungsi untuk mendekteksi apakah user sudah login
    if(isset($_SESSION['id'])){ 
        $nim = $_SESSION['id'];

        try { 
            $query = $db->prepare("select * from users where id = ?");
            $query->bindParam(1, $nim); 
            $query->execute();
            $data = $query->fetch();
            if($_SESSION['tipe'] == 'admin'){
                header("Location: beranda_admin.php");
                exit();
            }
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }else{
        header("Location: index.php");
        exit();
    }

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
    <!-- <link rel="stylesheet" href="../animatedStars.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/ScrollTrigger.min.js"></script>


    <style>
        body{
            background-color: rgb(16, 12, 20)
        }
        .container-me {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* #stars, #stars:after, #stars2, #stars2:after, #stars3, #stars3:after {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0; 
        } */

        .s-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            position: ;
            z-index: 1; 
        }

        .s-section-2 {
            position: relative; 
            /* top: 700px; */
            /* left: 0;  */
            /* width: 100%;
            height: 100%; */
        } 

        .text-container {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .text-container p,
        .text-container h1 {
            color: #ffffff;
        }
    </style>
    <style>
        /* body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgb(24, 12, 20);
            font-family: Arial, sans-serif; */
        /* } */
        #three-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; 
        }
        .content {
            position: relative;
            z-index: 1; 
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        footer {
            position: relative;
            z-index: 1;
            color: #ffffff;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Star Background -->
    <!-- <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div> -->

    <!-- Nav bar -->
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
                        <button type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                        <input name="keyword" class="bg-transparent w-full ml-3 focus:outline-none" placeholder="Cari Product" type="text" name="keyword">
                    </div>
                </form>
            </div>
                <div class="flex mr-20 gap-5 items-center font-sans font-bold">
                    <span class="material-symbols-outlined hover:text-red-500 hover:scale-110">
                        <a href="keranjang.php">leaderboard</a> </span> <span class="border-r h-6 border-white">
                    </span>
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="flex gap-2 hover:text-red-500 hover:scale-110" type="button">
                        <span class="material-symbols-outlined">account_circle</span>
                        <p>Profile</p>
                    </button>
                </div>
            </nav>

    <!-- Title -->
    <div class="container-me">
        <!-- Elemen animasi Globe -->
        <div class="s-section"></div>

        
        <div class="text-container h-full flex items-center justify-center font-sans">
            <span>
                <p class="text-slate-300 font-bold text-xs">EXPLORE, FIND AND BUY PRODUCT</p>
                <h1 class="text-white text-5xl font-bold">Join the New Era of <br> Digital Product</h1>
            </span>
        </div>
    </div>

    <script>
        var setVanta = () => {
            if (window.VANTA) {
                window.VANTA.GLOBE({
                    el: ".s-section",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: 200.00,
                    minWidth: 200.00,
                    scale: 1.00,
                    scaleMobile: 1.00,
                    color: 0x5a82ff,
                    size: 1.50,
                    backgroundColor: 0x100c14
                });
            }
        };

        document.addEventListener("DOMContentLoaded", setVanta);
    </script>
    <!-- <script>
        var setVanta = () => {
            if (window.VANTA) {
                window.VANTA.GLOBE({
                    el: ".s-section-2",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: 200.00,
                    minWidth: 200.00,
                    scale: 1.00,
                    scaleMobile: 1.00,
                    color: 0x5a82ff,
                    size: 1.50,
                    backgroundColor: 0x100c14
                });
            }
        };

        document.addEventListener("DOMContentLoaded", setVanta);
    </script> -->
    
    <!-- All product -->
        <div class="-mt-10 relative z-10 w-full h-20 backdrop-blur supports-backdrop-blur:bg-white/60 "></div>

        <div class="bg-transparent ">

            <div id="three-container"></div>
                <h1 class="pl-10 text-white font-extrabold col-start-1 row-start-1 mt-1">All Product</h1>
                <div class="flex flex-wrap gap-[35px] mt-14 pb-20 pl-5">
                
                    <?php foreach ($dataSemuaProduk as $value): ?>
        
                        <?php if($value['resistance'] < 0): ?>
                            <div>
                                <div class="border bg-[#151D28] overflow-hidden border-white w-[200px] h-[380px] rounded-sm card">
                                    <h1 class="text-2xl font-bold text-white absolute z-10 mt-28 ml-16">Habis</h1>
                                    <div class="bg-[url('../<?php echo$value['gambar'] ?>')] w-full h-60 bg-center bg- bg-cover bg-no-repeat brightness-[.40]"></div>
                                    <span class="text-white inline-block my-3 mx-4">
                                        <h1 class="inline-block max-h-[45px] overflow-hidden leading-6 text-sm"><?php echo$value['nama'] ?></h1>
                                        <h1 class="font-bold mt-3">
                                            
                                        <?php echo$value['give_poin'] ?></h1>
                                        <p class="text-sm">Poin karya <?php echo$value['Poin_karya'] ?></p>
                                    </span>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="product.php?id_produk=<?php echo$value['id']?>">
                                <div class="border bg-[#151D28] overflow-hidden border-white w-[200px] h-[380px] rounded-sm card">
                                    <div class="bg-[url('../<?php echo$value['gambar'] ?>')] w-full h-60 bg-center bg- bg-cover bg-no-repeat"></div>
                                    <span class="text-white inline-block my-3 mx-4">
                                        <h1 class="inline-block max-h-[45px] overflow-hidden leading-6 text-sm"><?php echo$value['nama'] ?></h1>
                                        <h1 class="font-bold mt-3">beri poin<?php echo$value['give_poin'] ?></h1>
                                        <p class="text-sm">Poin karya <?php echo$value['Poin_karya'] ?></p>
                                    </span>
                                </div>
                            </a>
                        <?php endif; ?>
        
                    <?php endforeach; ?>
        
                </div>  
                
            </div>
            
        </div>
        
        <!-- Script supaya Pop up Modal nya bisa berjalan -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    
        <!-- footer -->
        <footer class=" px-4 divide-y dark:bg-gray-100 dark:text-gray-800">
            <div class="container flex flex-col justify-between py-10 mx-auto space-y-8 lg:flex-row lg:space-y-0">
                <div class="lg:w-1/3">
                    <a rel="noopener noreferrer" href="#" class="flex justify-center space-x-3 lg:justify-start">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full">
                        <img src="../img/smktibali.png">
                                <path d="M18.266 26.068l7.839-7.854 4.469 4.479c1.859 1.859 1.859 4.875 0 6.734l-1.104 1.104c-1.859 1.865-4.875 1.865-6.734 0zM30.563 2.531l-1.109-1.104c-1.859-1.859-4.875-1.859-6.734 0l-6.719 6.734-6.734-6.734c-1.859-1.859-4.875-1.859-6.734 0l-1.104 1.104c-1.859 1.859-1.859 4.875 0 6.734l6.734 6.734-6.734 6.734c-1.859 1.859-1.859 4.875 0 6.734l1.104 1.104c1.859 1.859 4.875 1.859 6.734 0l21.307-21.307c1.859-1.859 1.859-4.875 0-6.734z"></path>
                            </svg>
                        </div>
                        <span class="self-center text-2xl font-semibold">Craftify World</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 text-sm gap-x-3 gap-y-8 lg:w-2/3 sm:grid-cols-4">
                    <div class="space-y-3">
                        <h3 class="tracking-wide uppercase dark:text-black-900 border-b border-black">DEVELOPERS</h3>
                        <ul class="space-y-1">
                            <li>
                                <a rel="noopener noreferrer" href="#">SANJAYA</a>
                            </li>
                            <li>
                                <a rel="noopener noreferrer" href="#">TRISTAN</a>
                            </li>
                            <li>
                                <a rel="noopener noreferrer" href="#">SURYA NATA</a>
                        </li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="tracking-wide uppercase dark:text-black-900 border-b border-black">Supports BY</h3>
                        <ul class="space-y-1">
                            <li>
                                <a rel="noopener noreferrer" href="#">SMK TI BALI GLOBAL BADUNG</a>
                            </li>
                            <li>
                                <a rel="noopener noreferrer" href="#">XQUANTUM CLASS</a>
                            </li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="uppercase dark:text-gray-900 border-b border-black">Another Project</h3>
                        <ul class="space-y-1">
                            <li>
                                <a rel="noopener noreferrer" href="#">BUMA THE LAST SORCERER</a>
                            </li>
                    </div>
                    <div class="space-y-3">
                        <div class="uppercase dark:text-black-900">SOCIAL MEDIA</div>
                        <div class="flex justify-center space-x-3">
                            <a rel="noopener noreferrer" href="#" title="Facebook" class="flex items-center p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 32 32" class="w-5 h-5 fill-current">
                                    <path d="M32 16c0-8.839-7.167-16-16-16-8.839 0-16 7.161-16 16 0 7.984 5.849 14.604 13.5 15.803v-11.177h-4.063v-4.625h4.063v-3.527c0-4.009 2.385-6.223 6.041-6.223 1.751 0 3.584 0.312 3.584 0.312v3.937h-2.021c-1.984 0-2.604 1.235-2.604 2.5v3h4.437l-0.713 4.625h-3.724v11.177c7.645-1.199 13.5-7.819 13.5-15.803z"></path>
                                </svg>
                            </a>
                            <a rel="noopener noreferrer" href="#" title="Twitter" class="flex items-center p-1">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current">
                                    <path d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"></path>
                                </svg>
                            </a>
                            <a rel="noopener noreferrer" href="#" title="Instagram" class="flex items-center p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="w-5 h-5 fill-current">
                                    <path d="M16 0c-4.349 0-4.891 0.021-6.593 0.093-1.709 0.084-2.865 0.349-3.885 0.745-1.052 0.412-1.948 0.959-2.833 1.849-0.891 0.885-1.443 1.781-1.849 2.833-0.396 1.020-0.661 2.176-0.745 3.885-0.077 1.703-0.093 2.244-0.093 6.593s0.021 4.891 0.093 6.593c0.084 1.704 0.349 2.865 0.745 3.885 0.412 1.052 0.959 1.948 1.849 2.833 0.885 0.891 1.781 1.443 2.833 1.849 1.020 0.391 2.181 0.661 3.885 0.745 1.703 0.077 2.244 0.093 6.593 0.093s4.891-0.021 6.593-0.093c1.704-0.084 2.865-0.355 3.885-0.745 1.052-0.412 1.948-0.959 2.833-1.849 0.891-0.885 1.443-1.776 1.849-2.833 0.391-1.020 0.661-2.181 0.745-3.885 0.077-1.703 0.093-2.244 0.093-6.593s-0.021-4.891-0.093-6.593c-0.084-1.704-0.355-2.871-0.745-3.885-0.412-1.052-0.959-1.948-1.849-2.833-0.885-0.891-1.776-1.443-2.833-1.849-1.020-0.396-2.181-0.661-3.885-0.745-1.703-0.077-2.244-0.093-6.593-0.093zM16 2.88c4.271 0 4.781 0.021 6.469 0.093 1.557 0.073 2.405 0.333 2.968 0.553 0.751 0.291 1.276 0.635 1.844 1.197 0.557 0.557 0.901 1.088 1.192 1.839 0.22 0.563 0.48 1.411 0.553 2.968 0.072 1.688 0.093 2.199 0.093 6.469s-0.021 4.781-0.099 6.469c-0.084 1.557-0.344 2.405-0.563 2.968-0.303 0.751-0.641 1.276-1.199 1.844-0.563 0.557-1.099 0.901-1.844 1.192-0.556 0.22-1.416 0.48-2.979 0.553-1.697 0.072-2.197 0.093-6.479 0.093s-4.781-0.021-6.48-0.099c-1.557-0.084-2.416-0.344-2.979-0.563-0.76-0.303-1.281-0.641-1.839-1.199-0.563-0.563-0.921-1.099-1.197-1.844-0.224-0.556-0.48-1.416-0.563-2.979-0.057-1.677-0.084-2.197-0.084-6.459 0-4.26 0.027-4.781 0.084-6.479 0.083-1.563 0.339-2.421 0.563-2.979 0.276-0.761 0.635-1.281 1.197-1.844 0.557-0.557 1.079-0.917 1.839-1.199 0.563-0.219 1.401-0.479 2.964-0.557 1.697-0.061 2.197-0.083 6.473-0.083zM16 7.787c-4.541 0-8.213 3.677-8.213 8.213 0 4.541 3.677 8.213 8.213 8.213 4.541 0 8.213-3.677 8.213-8.213 0-4.541-3.677-8.213-8.213-8.213zM16 21.333c-2.948 0-5.333-2.385-5.333-5.333s2.385-5.333 5.333-5.333c2.948 0 5.333 2.385 5.333 5.333s-2.385 5.333-5.333 5.333zM26.464 7.459c0 1.063-0.865 1.921-1.923 1.921-1.063 0-1.921-0.859-1.921-1.921 0-1.057 0.864-1.917 1.921-1.917s1.923 0.86 1.923 1.917z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-6 text-sm text-center dark:text-gray-600">Copyright © 2024 Craftify World.</div>
        </footer>


    <script>
        gsap.registegive_poinlugin(ScrollTrigger);
        gsap.fromTo(".card", {
            opacity: 0,
            scaleY: 0,
        }, {
            opacity: 1,
            duration: 0.5,
            delay: 0.5,
            scaleY: 1,
            scrollTrigger: {
                trigger: ".card",
                start: "top 90%",
                end: "top 10%",
            }
        })
        const hoverElements = document.querySelectorAll('.card');
        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                gsap.to(element, {
                    scale: 1.1,
                    backgroundColor: "grey",
                    duration: 0.5
                });
            });
            element.addEventListener('mouseleave', () => {
                gsap.to(element, {
                    scale: 1,
                    backgroundColor: "#151D28",
                    duration: 0.5
                });
            });
        });
    </script>


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
                <form class="p-4" action="" method="POST">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <img src="../img/anonymousProfile.png" width="150px" alt="" class="block rounded-full ml-28 mb-3">
                        <div class="col-span-2 flex justify-between font-bold">
                            <label for="name" class="block mb-2 text-xs text-gray-900 dark:text-white">Point Kredit Mahasiswa</label>
                            <input type="text" name="name" id="name" class="text-gray-900 text-right" value="25">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <input type="text" name="name" id="name" value="<?php echo$data['username']?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NO HP</label>
                            <input type="text" name="name" id="name" value="08789954211" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                            <input type="text" name="name" id="name" value="Kerobokan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="text" name="name" id="name" value="<?php echo$data['password']?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type product name" required="">
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

     <!-- Script Three.js -->
     <script>
        // Inisialisasi Three.js
        const container = document.getElementById('three-container');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(
            75, 
            window.innerWidth / window.innerHeight, 
            0.1, 
            2000 // Memperluas jangkauan far plane untuk ruang vertikal yang luas
        );
        camera.position.z = 100;

        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        // Fungsi untuk membuat kubus acak
        function createRandomCube() {
            const size = Math.random() * 0.7 + 6; // Ukuran cube antara 0.7 dan 6
            const geometry = new THREE.BoxGeometry(size, size, size);
            const material = new THREE.MeshBasicMaterial({
                color: 0xffffff,
                wireframe: true,
                transparent: true
            });
            const cube = new THREE.Mesh(geometry, material);

            // Menyebar posisi kubus di X, Y, Z
            cube.position.set(
                (Math.random() - 0.5) * 500, // X: -500 to 500
                (Math.random() - 0.5) * 2000, // Y: -2000 to 2000 (mengakomodasi scroll)
                (Math.random() - 0.5) * 500  // Z: -500 to 500
            );

            // Kecepatan gerakan dan rotasi
            cube.velocity = new THREE.Vector3(
                (Math.random() - 0.5) * 0.2,
                0, // Tidak bergerak di Y untuk mengikuti scroll
                (Math.random() - 0.5) * 0.2
            );
            cube.rotationSpeed = new THREE.Vector3(
                Math.random() * 0.02,
                Math.random() * 0.02,
                Math.random() * 0.02
            );
            cube.opacitySpeed = Math.random() * 0.005 + 0.002;
            cube.material.opacity = Math.random();
            scene.add(cube);
            return cube;
        }

        // Membuat banyak kubus
        const cubes = Array.from({ length: 200 }, createRandomCube);

        // Fungsi untuk menghubungkan scroll dengan posisi kamera
        function onScroll() {
            const scrollY = window.scrollY;
            camera.position.y = scrollY / 3; // Mengatur kecepatan scroll
        }
        window.addEventListener('scroll', onScroll);

        // Fungsi animasi
        function animate() {
            requestAnimationFrame(animate);

            cubes.forEach(cube => {
                // Menggerakkan kubus
                cube.position.add(cube.velocity);

                // Rotasi kubus
                cube.rotation.x += cube.rotationSpeed.x;
                cube.rotation.y += cube.rotationSpeed.y;
                cube.rotation.z += cube.rotationSpeed.z;

                // Membalik arah jika melewati batas
                if (Math.abs(cube.position.x) > 250 || Math.abs(cube.position.z) > 250) {
                    cube.velocity.x *= -1;
                    cube.velocity.z *= -1;
                }

                // Mengatur opacity untuk efek memudar dan muncul kembali
                cube.material.opacity += cube.opacitySpeed;
                if (cube.material.opacity <= 0 || cube.material.opacity >= 1) {
                    cube.opacitySpeed *= -1;
                }
            });

            renderer.render(scene, camera);
        }

        animate();

        // Mengatur ulang ukuran renderer ketika window di resize
        function adjustRendererSize() {
            renderer.setSize(window.innerWidth, window.innerHeight);
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
        }
        window.addEventListener('resize', adjustRendererSize);
    </script>

</body>
</html>