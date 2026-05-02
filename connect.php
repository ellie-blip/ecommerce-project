<?php
    $servername = "127.0.0.1:3308"; //127.0.0.1:3308
    $user = "root";
    $password = "MyNewPassword";
    $dbname = "maison_elegance";

    $conn = mysqli_connect($servername, $user, $password, $dbname);

        if(!$conn){
            die("Connection failed " . mysqli_connect_error());
        }
    mysqli_set_charset($conn, "utf8");
?>