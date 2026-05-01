<?php
    $servername = "localhost";
    $user = "root";
    $password = "";
    $dbname = "maison_elegance";

    $conn = mysqli_connect($servername, $user, $password, $dbname);

        if(!$conn){
            die("Connection failed " . mysqli_connect_error());
        }
    mysqli_set_charset($conn, "utf8");
?>