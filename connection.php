<?php
/*
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $port = '3306';

    $link = mysqli_connect($hostname, $username, $password, "", $port) or die(mysqli_connect_error());
    mysqli_select_db($link, "muscbase") or die(mysqli_error($link));
*/
    $host = "localhost";
	//$host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $dbname = "laboratoire";
    //$port = '3306';
	$port = '3306';
    /*try {
        $link = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    ?>*/
    //$link = new mysqli($host, $user, $pass, $dbname, $port);
	$link = new mysqli($host, $user, $pass, $dbname,3306);
	mysqli_set_charset($link, "utf8");

    // Check connection
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
?>
