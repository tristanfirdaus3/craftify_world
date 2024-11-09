<?php 
    $host = "localhost";
    $dbname = "craftify_world";
    $username = "root";
    $password = "";

    try {
            # MySQL with PDO_MYSQL
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }
      catch(PDOException $e) {
          echo $e->getMessage();
      }      
?>