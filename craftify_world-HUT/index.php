<?php
    include 'koneksi.php';
    session_start();
 
    // fungsi untuk mendektesi apakah user sudah pernah login atau belum
    if (isset($_SESSION['id'])) {
        if($_SESSION['tipe'] == 'user'){ // jika yang login adalah user maka pindah ke halaman user
            header("Location: user/beranda.php");
            exit();
        }else{ // jika yang login adalah admin maka berpindah ke halaman admin
            header("Location: admin/beranda_admin.php");
            exit();
        }
    }

    // fungsi untuk login
    if(isset($_POST['submit'])){ 
        $username = $_POST['username'];
        $password = $_POST['password'];
        $admin_id = $_POST['admin_id'];

        try { 
            if($admin_id == null){
                $queryCari = $db->prepare("select * from users where username = ? AND password = ?");
                $queryCari->bindParam(1, $username);
                $queryCari->bindParam(2, $password);
                $queryCari->execute();
                $data = $queryCari->fetch(); 
                
                if($data == null){
                    $username = null;
                    if($username == null){
                        echo "<script> alert('Username atau Password salah!!');</script";
                    } 
                }else{
                    $queryCari=null; 
                    echo "<script> alert('Berhasil Login!!');</script>"; 
                    $_SESSION['id'] = $data[0];
                    $_SESSION['tipe'] = "user";
                    header("Location: user/beranda.php");
                    die(); 
                }
            }else{
                $queryCari = $db->prepare("select * from admin where id_admin = ? AND username = ? AND password = ?");
                $queryCari->bindParam(1, $admin_id);
                $queryCari->bindParam(2, $username);
                $queryCari->bindParam(3, $password);
                $queryCari->execute();
                $data = $queryCari->fetch(); 
                
                if($data == null){
                    $username = null;
                    if($username == null){
                        echo "<script> alert('Username atau Password atau ID salah!!');</script";
                    } 
                }else{
                    $queryCari=null; //tutup koneksi
                    echo "<script> alert('Berhasil Login!!');</script>"; 
                    $_SESSION['id'] = $data[0];
                    $_SESSION['tipe'] = "admin";
                    header("Location: admin/beranda_admin.php");
                    die(); 
                }
            }
        }catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="animatedStars.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.birds.min.js"></script>
    <style>
        /* Pastikan .s-section mengambil seluruh layar */
        .s-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="s-section">
        <div class="flex items-center justify-center text-white gap-20 h-full">
            <div class="flex items-center">
                <img src="img/smktibali.png" alt="" width="150px">
                <h1 class="text-3xl font-bold font-mono">Craftify World</h1>
            </div>

            <div class="relative h-[500px] mt-48">
                <div class="bg-[#1E2933] rounded-md w-[300px] h-[80%] py-6 px-2 font-bold">
                    <h1 class="font-extrabold text-2xl mb-2 max-w-[800px] text-center">Login User</h1><br>
                    <hr class="bg-white h-1 rounded-md"><br>

                    <form action="" method="POST" name="input">
                        <div class="px-4">
                            Username
                            <input class="rounded-sm mb-5 w-full text-black px-1" type="text" name="username">
                            Password
                            <input class="rounded-sm mb-5 w-full text-black px-1" type="password" name="password">
                            Id (Khusus Admin)
                            <input class="rounded-sm mb-5 w-full text-black px-1" type="password" name="admin_id">
                        </div>
                        
                        <div class="flex justify-center gap-5">
                            <button type="submit" name="submit" class="border border-slate-400 rounded-md py-1 w-24 text-center">Login</button>
                            <button class="bg-slate-400 rounded-md py-1 w-24 text-center"><a href="signup.php">Signup</a></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Vanta.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.VANTA) {
                window.VANTA.BIRDS({
                    el: ".s-section",
                    mouseControls: true,
                    touchControls: true,
                    gyroControls: false,
                    minHeight: 200.00,
                    minWidth: 200.00,
                    scale: 1.00,
                    scaleMobile: 1.00,
                    backgroundColor: 0x9092b,
                    color1: 0xffffff,
                    birdSize: 0.70,
                    separation: 79.00,
                    alignment: 1.00,
                    cohesion: 71.00,
                    quantity: 4.00
                });
            }
        });
    </script>
</body>
</html>
