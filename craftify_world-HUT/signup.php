<?php
    include 'koneksi.php';
    session_start();
    
    // fungsi untuk membuat akun untuk user
    if(isset($_POST['submit'])){ 
        $username = $_POST['username'];
        $password = $_POST['password'];

        try { 
            $query = $db->prepare("insert into users (username, password) values (?, ?)");
            $query->bindParam(1, $username);
            $query->bindParam(2, $password);
            $query->execute();
            $query=null; //tutup koneksi
            echo "<script> alert('Akun berhasil dibuat!!');
            window.location.replace('index.php');</script>"; 
            die(); 
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
</head>
<body>
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>

    <div class="flex items-center justify-center text-white gap-20 h-[90%]">
        <div class="flex items-center">
            <img src="img/smktibali.png" alt="" width="150px">
            <h1 class="text-3xl font-bold font-mono">Craftify World</h1>
        </div>

        <div class="relative h-[400px] mt-20">
            <div class="bg-[#1E2933] rounded-md w-[300px] h-[110%] py-6 px-2 font-bold">
                <h1 class="font-extrabold text-2xl mb-2 max-w-[800px] text-center">Signup User</h1><br>
                <hr class="bg-white h-1 rounded-md"><br>

                <form action="" method="POST" name="input">
                    <div class="px-4">
                        Username
                        <input class="rounded-sm mb-5 w-full text-black px-1" type="text" name="username">
                        Password
                        <input class="rounded-sm mb-5 w-full text-black px-1" type="password" name="password">
                        phone
                        <input class="rounded-sm mb-5 w-full text-black px-1" type="phone" name="phone">
                        OTP
                        <input class="rounded-sm mb-5 w-full text-black px-1" type="OTP" name="OTP">
                    </div>
                    
                    <div class="flex justify-center gap-5">
                        <button type="submit" name="submit" class="border border-slate-400 rounded-md py-1 w-24 text-center">SignUp</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>
</html>